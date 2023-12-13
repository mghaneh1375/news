<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategoryRelations extends Model
{
    protected $guarded = [];
    protected $table = 'news_category_relations';
    public $timestamps = false;
    
    protected $fillable = [
        'id', 'newsId', 'categoryId', 'isMain'
    ];

    
    public function scopeMain($query) {
        return $query->where('isMain', true);
    }

    
    public function category() {
        return $this->hasOne(NewsCategory::class, 'id', 'categoryId');
    }
}
