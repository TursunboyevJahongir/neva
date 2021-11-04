<?php

namespace App\Services\Product;

use App\Http\Resources\Api\v1\CategoryNameResource;
use App\Http\Resources\Api\v1\CategoryResource;
use App\Models\Category;
use App\Models\HistoryView;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function all($shop_id = false)
    {
        return Product::with('image')
            ->latest('id')
            ->shopOwner($shop_id)
            ->paginate(config('app.per_page'));
    }

    public function categoryProducts(Category $category, Request $request): array
    {
        $size = $request->per_page ?? 10;
        $orderBy = $request->orderby ?? "position";

        $orderBy = $orderBy === 'price' ? "min_price" : $orderBy;
        $orderBy = $orderBy === 'percent' ? "max_percent" : $orderBy;
        $sort = $request->sort ?? "DESC";
        $minimal = $request->min_price;
        $maximal = $request->max_price;
        $sale = $request->sale == 1;

        $search = $request->search ? trim($request->search) : null;

        $childrenIds = $category->children()->active()->pluck('id')->toArray();
        array_push($childrenIds, $category->id);

        $query = Product::query()->active()->whereIn('category_id', $childrenIds)
            ->when($minimal, function ($query) use ($minimal) {
                return $query->where('min_price', '>=', $minimal);
            })
            ->when($maximal, function ($query, $maximal) {
                return $query->where('min_price', '<=', $maximal);
            })
            ->when($sale, function ($query) use ($sale) {
                return $query->where('max_percent', '!=', null);
            })
            ->orderBy($orderBy, $sort);
//        if ($brand != 0) {
//            $query->where('brand_id', $brand);
//        }

        $minimal = $query->min('min_price');
        $maximal = $query->max('min_price');

        /*$brands = Brand::join('products', 'brands.id', '=', 'products.brand_id')
            ->whereIn('products.category_id', $category->getDescendants($category))
            ->distinct()->get('brands.*');*/
        $categories = $category->children;

        $data['products'] = $query->paginate($size);
        $data['append'] = [
            'categories' => CategoryNameResource::collection($categories),
            'minimal' => $minimal,
            'maximal' => $maximal
        ];

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

    public function deleteImage(Product $product, string $fileUrl)
    {
        $image = $product->images()->where('url', $fileUrl)->firstOrFail();
        $image->removeFile();
        $image->delete();
    }

    public function historyView($id)
    {
        if (auth('sanctum')->check())
            HistoryView::query()->firstOrCreate(
                ['user_id' => auth('sanctum')->id(), 'product_id' => $id]
            )->increment('count');
    }

    public function similar(Product $id, $size = 10, $lang = "ru"): LengthAwarePaginator
    {
        $tags = explode(',', $id->tag);

        return Product::query()
            ->where('id', '!=', $id->id)
            ->where(function ($query) use ($tags) {
                foreach ($tags as $tag)
                    $query->orWhere('tag', 'LIKE', "%$tag%");
            })
            ->orderBy('position', 'DESC')
            ->orderBy("name->$lang", 'ASC')
            ->paginate($size);
    }
}
