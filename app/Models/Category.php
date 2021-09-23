<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasHeaders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,
        HasHeaders;

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'slug',
        'active',

    ];

    protected $casts = [
        'active' => 'boolean',
        'name' => TranslatableJson::class,
        'description' => TranslatableJson::class
    ];
    private $descendants = [];
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('cover_image', false)->withDefault(['url' => '/img/no-icon.png']);
    }
    public function ico()
    {
        return $this->morphOne(Image::class, 'imageable')->where('cover_image', true)->withDefault(['url'=>'/img/no-icon.png']);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');//->with('children.children.children');
    }
    public function hasChildren(){
        if($this->children->count()){
            return true;
        }

        return false;
    }

    public function findDescendants(Category $category){
        $this->descendants[] = $category->id;

        if($category->hasChildren()){
            foreach($category->children as $child){
                $this->findDescendants($child);
            }
        }
    }

    public function getDescendants(Category $category){
        $this->findDescendants($category);
        return $this->descendants;
    }
}
