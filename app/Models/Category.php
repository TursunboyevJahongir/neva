<?php

namespace App\Models;

use App\Casts\TranslatableJson;
use App\Traits\HasTranslatableJson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

/**
 * Class Client
 * @package App\Models
 * @property int id
 * @property int parent_id
 * @property int position
 * @property TranslatableJson name
 * @property TranslatableJson description
 * @property string slug
 * @property boolean active
 * @property Image ico
 *  * @OA\Schema(
 *     title="Category model",
 *     description="Category model",
 * )
 */
class Category extends Model
{
    use HasFactory, HasTranslatableJson;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'name' => TranslatableJson::class,
    ];
    private $descendants = [];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function ico(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable')->where('cover_image', true)->withDefault(['url' => '/img/no-icon.png']);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');//->with('children.children.children');
    }

    public function hasChildren(): bool
    {
        if ($this->children->count()) {
            return true;
        }
        return false;
    }

    public function scopeParents($q)
    {
        return $q->whereNull('parent_id');
    }

    public function scopeActive($q)
    {
        return $q->where('active', '=', true);
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
