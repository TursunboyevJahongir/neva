<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class HistorySearch
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int count
 * @property string query
 *  * @OA\Schema(
 *     title="HistorySearch model",
 *     description="HistorySearch model",
 * )
 */
class HistorySearch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'query', 'count'];
}
