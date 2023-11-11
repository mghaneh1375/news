<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [];
    protected $table = 'news';


    protected $fillable = [
        'userId', 'title', 'slug', 'pic', 'video', 'videoServer',
        'text', 'release', 'dateAndTime', 'meta', 'keyword',
        'seoTitle', 'seen', 'topNews', 'confirm', 'updated_at',
        'rtl', 'server', 'isVideoLink', 'titleEn', 'slugEn',
        'textEn', 'metaEn', 'keywordEn', 'seoTitleEn', 'site_id'
    ];

    public function scopeYouCanSee($query, $site=4){

        date_default_timezone_set('Asia/Tehran');

        $time = verta()->format('Y/m/d H:i');

        return $query->where('dateAndTime', '<=', $time)
                     ->where('release', '!=', 'draft')
                     ->where('confirm', 1)->where('site_id', $site);
    }

    public function getTags()
    {
        return $this->belongsToMany(NewsTags::class, 'news_tags_relations', 'newsId', 'tagId');
    }
}
