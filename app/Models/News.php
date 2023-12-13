<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [];
    protected $table = 'news';

    public function catogoryRelations() {
        return $this->belongsToMany(
            NewsCategoryRelations::class, 
            table: 'news', relatedKey: 'newsId', relatedPivotKey: 'id',
            foreignPivotKey: 'id'
        );
    }

    protected $fillable = [
        'userId', 'title', 'slug', 'pic', 'video', 'videoServer',
        'text', 'release', 'dateAndTime', 'meta', 'keyword',
        'seoTitle', 'seen', 'topNews', 'confirm', 'updated_at',
        'rtl', 'server', 'isVideoLink', 'titleEn', 'slugEn',
        'textEn', 'metaEn', 'keywordEn', 'seoTitleEn', 'site_id'
    ];

    public function scopeYouCanSee($query, $site=4, $lang='fa'){

        date_default_timezone_set('Asia/Tehran');

        $time = verta()->format('Y/m/d H:i');

        if($lang == 'fa')
            return $query->where('dateAndTime', '<=', $time)
                        ->whereNotNull('slug')
                        ->whereNotNull('title')
                        ->whereNotNull('text')
                        ->where('release', '!=', 'draft')
                        ->where('confirm', 1)->where('site_id', $site);
                        
        return $query->where('dateAndTime', '<=', $time)
                    ->whereNotNull('slugEn')
                    ->whereNotNull('titleEn')
                    ->whereNotNull('textEn')
                    ->where('release', '!=', 'draft')
                    ->where('confirm', 1)->where('site_id', $site);
    }

    public function getTags()
    {
        return $this->belongsToMany(NewsTags::class, 'news_tags_relations', 'newsId', 'tagId');
    }
}
