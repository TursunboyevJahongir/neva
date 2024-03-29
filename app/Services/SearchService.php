<?php

namespace App\Services;

use App\Http\Resources\Api\v1\CategoryNameResource;
use App\Http\Resources\Api\v1\CategoryResource;
use App\Models\Category;
use App\Models\HistorySearch;
use App\Models\HistoryView;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Services\LatinToCyrillic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SearchService
{

    public function __construct(
        private LatinToCyrillic $latinToCyrillic
    )
    {
    }

    public function search(string $search, Request $request): array
    {
        $size = $request->per_page ?? 10;
        $orderBy = $request->orderby ?? "position";
        $orderBy = $orderBy === 'price' ? "min_price" : $orderBy;
        $orderBy = $orderBy === 'percent' ? "max_percent" : $orderBy;
        $sort = $request->sort ?? "DESC";
        $minimal = $request->min_price;
        $maximal = $request->max_price;
        $sale = $request->sale == 1;

        $cyrillic = $this->latinToCyrillic->LatinToCyrillic($search);
        $latin = $this->latinToCyrillic->CyrillicToLatin($search);


        $query = Product::query()
            ->where('name->uz', 'like', "%$latin%")
            ->orWhere('name->ru', 'like', "%$latin%")
            ->orWhere('name->en', 'like', "%$latin%")
            ->orWhere('content->uz', 'like', "%$latin%")
            ->orWhere('content->ru', 'like', "%$latin%")
            ->orWhere('content->en', 'like', "%$latin%")
            ->orWhere('name->uz', 'like', "%$cyrillic%")
            ->orWhere('name->ru', 'like', "%$cyrillic%")
            ->orWhere('name->en', 'like', "%$cyrillic%")
            ->orWhere('content->uz', 'like', "%$cyrillic%")
            ->orWhere('content->ru', 'like', "%$cyrillic%")
            ->orWhere('content->en', 'like', "%$cyrillic%")
            ->orWhere('tag', 'like', "%$latin%")
            ->orWhere('tag', 'like', "%$cyrillic%")
            ->orWhereHas('shop', function ($query) use ($latin, $cyrillic) {
                $query->where('name', 'like', '%' . $latin . '%')
                    ->orWhere('name', 'like', "%$cyrillic%");
            })
            ->orWhereHas('category', function ($query) use ($latin, $cyrillic) {
                $query->where('name->uz', 'like', "%$latin%")
                    ->orWhere('name->ru', 'like', "%$latin%")
                    ->orWhere('name->en', 'like', "%$latin%")
                    ->orWhere('name->uz', 'like', "%$cyrillic%")
                    ->orWhere('name->ru', 'like', "%$cyrillic%")
                    ->orWhere('name->en', 'like', "%$cyrillic%");
            })
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

        $minimal = $query->min('min_price');
        $maximal = $query->max('min_price');

        $data['products'] = $query->paginate($size);
        $data['append'] = [
            'minimal' => $minimal,
            'maximal' => $maximal
        ];
        return $data;
    }

    public function render(Category $category, $subcategory = 'all', $shop = 0, $sort = 'asc', $brand = 0, $minPrice = 0, $maxPrice = 0)
    {
        $data = [];
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
        $data['products'] = $query->paginate(10);
        $data['appends']['categories'] = CategoryResource::collection($categories);
        $data['appends']['minimal'] = $minimal;
        $data['appends']['maximal'] = $maximal;

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

    public function historyView($id)
    {
        if (auth('sanctum')->check())
            HistoryView::query()->firstOrCreate(
                ['user_id' => auth('sanctum')->id(), 'product_id' => $id]
            )->increment('count');
    }

    public function userSearchDelete($string)
    {
        return HistorySearch::query()
            ->where('user_id', Auth::id())
            ->where('query', $string)
            ->first();
    }

    public function userSearch()
    {
        return HistorySearch::query()
            ->where('user_id', Auth::id())
            ->orderBy('updated_at', 'DESC')
            ->limit(10)
            ->get();
    }

    public function suggestText($query): Collection|array
    {
        return HistorySearch::withTrashed()
            ->selectRaw('sum(count) as count,query')
            ->groupBy('query')
            ->where('query', 'like', "%$query%")
            ->orderBy('count', 'DESC')
            ->orderBy('query', 'ASC')
            ->limit(10)
            ->get();
    }

    public function Popular(): Collection|array
    {
        return HistorySearch::withTrashed()
            ->selectRaw('sum(count) as count,query')
            ->groupBy('query')
            ->orderBy('count', 'DESC')
            ->orderBy('query', 'ASC')
            ->limit(10)
            ->get();
    }

    public function historySearch($query)
    {
        HistorySearch::query()->firstOrCreate(
            ['query' => $query, 'user_id' => auth('sanctum')->id()]
        )->increment('count');
    }
}
