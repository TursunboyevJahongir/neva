<?php

namespace App\Services\Category;

use App\Http\Request;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    /**
     * @var Image
     */
    private $image;
    private $icon;

    public function __construct()
    {
        $this->image = new Image();
        $this->icon = new Image();
    }

    public function all($lang = 'ru')
    {
        return Category::query()->parents()->orderBy("name->$lang)", 'Asc')->get();
    }

    public function Parents($orderBy, $sort, $lang = 'ru'): Collection|array
    {
        return Category::query()
            ->parents()
            ->active()
            ->orderBy($orderBy, $sort)
            ->orderBy("name->$lang", 'Asc')
            ->get();

    }

    public function create(array $attributes)
    {
        $category = Category::create($attributes);
        if (array_key_exists('image', $attributes)) {
            $file = $this->image->uploadFile($attributes['image'], 'category');

            $category->image()->create([
                'url' => '/' . $file
            ]);
        }
        if (array_key_exists('icon', $attributes)) {
            $file = $this->icon->uploadFile($attributes['icon'], 'category');
            $category->ico()->create([
                'cover_image' => true,
                'url' => '/' . $file
            ]);
        }

        return $category;
    }

    public function update(array $attributes, Category $category)
    {
        $category->update($attributes);
        if (array_key_exists('image', $attributes)) {
            if ($category->image()->exists()) {
                $category->image->removeFile();
                $category->image()->delete();
            }

            $file = $this->image->uploadFile($attributes['image'], 'category');

            $category->image()->create([
                'url' => '/' . $file
            ]);
        }

        if (array_key_exists('icon', $attributes)) {
            if ($category->ico()->exists()) {
                $category->ico->removeFile();
                $category->ico()->delete();
            }
            $file = $this->icon->uploadFile($attributes['icon'], 'category');
            $category->ico()->create([
                'cover_image' => true,
                'url' => '/' . $file
            ]);
        }
        return $category;
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}
