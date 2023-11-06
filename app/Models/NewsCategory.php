<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'news_categories';

    protected $fillable = [
        'id', 'name', 'nameEn', 'icon', 'parentId', 'top'
    ];

    public function scopeTop($query) {
        return $query->where(['top' => true, 'parentId' => 0]);
    }

}
