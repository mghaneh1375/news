<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [];
    protected $table = 'news';

    public function scopeYouCanSee($query){
        date_default_timezone_set('Asia/Tehran');

        $time = verta()->format('Y/m/d H:i');

        return $query->where('dateAndTime', '<=', $time)
                     ->where('release', '!=', 'draft')
                     ->where('confirm', 1);
    }

    public function getTags()
    {
        return $this->belongsToMany(NewsTags::class, 'news_tags_relations', 'newsId', 'tagId');
    }
}
