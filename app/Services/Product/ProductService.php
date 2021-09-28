<?php

namespace App\Services\Product;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Arr;

class ProductService
{
    public function all($shop_id = false)
    {
        return Product::with('image')
            ->latest('id')
            ->shopOwner($shop_id)
            ->paginate(config('app.per_page'));
    }

    public function render(Category $category,$subcategory='all',$shop=0,$sort= 'asc',$brand=0,$minPrice=0,$maxPrice=0)
    {
       $data=[];
        if ($shop) {
            $query = $shop->products();
            if ($subcategory != "all") {
                $category = Category::find($subcategory);
                $category_ids = $category->getDescendants($category);
                $query->whereIn('category_id', $category_ids);
            }
            if ($brand != 0) {
                $query->where('brand_id', $brand);
            }
            $minimal = $query->min('min_price');
            $maximal = $query->max('max_price');

            if ($minPrice && $maxPrice) {
                $query->whereBetween('min_price', [$minPrice, $maxPrice]);
            } else {
                $minPrice = $minimal;
                $maxPrice = $maximal;
            }
            $query->orderBy('min_price', $sort);

            $categories = [];
            /*$brands = Brand::join('products', 'brands.id', '=', 'products.brand_id')
                ->where('products.shop_id', $shop->id)->distinct()->get('brands.*');*/
        } else {

            $category_ids = $category->getDescendants($category);
            if ($subcategory != "all") {
                $category = Category::find($subcategory);
                $category_ids = $category->getDescendants($category);
            }
            $query = Product::whereIn('category_id', $category_ids);

            if ($brand != 0) {
                $query->where('brand_id', $brand);
            }
            $minimal = $query->min('min_price');
            $maximal = $query->max('max_price');

            if ($minPrice && $maxPrice) {
                $query->whereBetween('min_price', [$minPrice, $maxPrice]);
            } else {
                $minPrice = $minimal;
                $maxPrice = $maximal;
            }
            $query->orderBy('min_price', $sort);

            /*$brands = Brand::join('products', 'brands.id', '=', 'products.brand_id')
                ->whereIn('products.category_id', $category->getDescendants($category))
                ->distinct()->get('brands.*');*/
            $categories = $category->children;
        }
        $data['products' ]=$query->paginate(10);
        $data['categories' ]=$categories;
        $data['minimal' ]=$minimal;
        $data['maximal' ]=$maximal;

        return $data;
    }

    public function create(array $attributes)
    {
        $product = new Product([
            'shop_id' => $attributes['shop_id'],
            'category_id' => $attributes['category_id'],
            'brand_id' => Arr::get($attributes, 'brand_id'),
            'pickup' => Arr::has($attributes, 'pickup'),
            'delivery_price' => $attributes['delivery_price'],
            'refund' => $attributes['refund'],
            'product_attribute_ids' => Arr::get($attributes, 'attributes')
        ]);

        $product->setTranslations('name', $attributes['name']);
        $product->setTranslations('content', $attributes['content']);
        $product->save();

        // cover image of product
        $product->images()->create([
            'cover_image' => true,
            'url' => Image::uploadFile($attributes['cover'], 'products')
        ]);

        if (!Arr::has($attributes, 'attributes')) {
            $product->single()->create([
                'quantity' => $attributes['quantity'][0],
                'price' => $attributes['price'][0]
            ]);
        } else {
            $product->product_attribute_ids = $attributes['attributes'];
            $variationsCount = count($attributes['values']);

            for ($i = 0; $i < $variationsCount; $i++) {
                $values = explode(',', $attributes['values'][$i]);
                $variation = $product->variations()->create([
                    'product_attribute_value_ids' => $values,
                    'quantity' => $attributes['quantity'][$i],
                    'price' => $attributes['price'][$i]
                ]);

                // optional variation images
                if (Arr::has($attributes, "img.$i")) {
                    $variation->image()->create([
                        'url' => Image::uploadFile($attributes['img'][$i], 'products')
                    ]);
                }
            }
        }
        $this->calculateMinMaxPrice($product);
        $product->save();
        return $product;
    }

    public function update(array $attributes, Product $product)
    {
        $product->fill([
            'shop_id' => $attributes['shop_id'],
            'category_id' => $attributes['category_id'],
            'brand_id' => Arr::get($attributes, 'brand_id'),
            'pickup' => Arr::has($attributes, 'pickup'),
            'delivery_price' => $attributes['delivery_price'],
            'refund' => $attributes['refund']
        ]);
        $product->setTranslations('name', $attributes['name']);
        $product->setTranslations('content', $attributes['content']);
        $product->save();

        // check cover photo uploaded
        if (Arr::has($attributes, 'cover')) {

            if ($product->image()->exists()) {
                $product->image->removeFile();
                $product->image()->delete();
            }

            $product->image()->create([
                'cover_image' => true,
                'url' => Image::uploadFile($attributes['cover'], 'products')
            ]);
        }

        // edit its variation
        $editable = array();
        for ($i = 0; $i < count($attributes['variations']); $i++) {
            $variation = ProductVariation::find($attributes['variations'][$i]);
            if (!$variation) {
                $variation = new ProductVariation();
            } else {
                array_push($editable, $attributes['variations'][$i]);
            }
            $values = Arr::has($attributes, "values.$i") ? explode(',', $attributes['values'][$i]) : null;
            // check variation photo uploaded, delete old if true
            if (Arr::has($attributes, "img.$i")) {
                if ($variation->image()->exists()) {
                    $variation->image->removeFile();
                    $variation->image()->delete();
                }
                $variation->image()->create([
                    'url' => Image::uploadFile($attributes['img'][$i], 'products')
                ]);
            }
            // fill
            $variation->fill([
                'product_id' => $product->id, // for new ones
                'product_attribute_value_ids' => $values,
                'quantity' => $attributes['quantity'][$i],
                'price' => $attributes['price'][$i],
            ]);
            $variation->save();
            array_push($editable, $variation->id); // save to editable
        }
        // delete variations from db that are deleted on view
        $productVariations = $product->variations;
        foreach ($productVariations as $productVariation) {
            if (!in_array($productVariation->id, $editable)) {
                $productVariation->delete();
            }
        }
        $this->calculateMinMaxPrice($product);
        $product->save();
        return $product;
    }

    public function addImage(Product $product, UploadedFile $image)
    {
        $product->images()->create([
            'url' => Image::uploadFile($image, 'products')
        ]);
    }

    public function getImages(Product $product)
    {
        $images = array();
        foreach ($product->images as $image) {
            array_push($images, array(
                'file' => $image->url,
                'size' => Storage::size($image->url)
            ));
        }
        return $images;
    }

    public function deleteImage(Product $product, string $fileUrl)
    {
        $image = $product->images()->where('url', $fileUrl)->firstOrFail();
        $image->removeFile();
        $image->delete();
    }

    public function calculateMinMaxPrice(Product $product)
    {
        $max = 0;
        $min = 999999999;
        foreach ($product->variations as $variation) {
            if ($variation->price < $min) {
                $min = $variation->price;
            }
            if ($variation->price > $max) {
                $max = $variation->price;
            }
        }
        $product->min_price = $min;
        $product->max_price = $max;
        return $product;
    }
}
