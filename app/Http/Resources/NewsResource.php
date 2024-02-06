<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Hekmatinasser\Verta\Facades\Verta;
use App\Models\NewsCategory;
use App\Models\User;
use App\Models\News;
use App\Models\NewsCategoryRelations;

class NewsResource extends JsonResource
{
    private static $locale;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $slug = getData(self::$locale, $this->slug, $this->slugEn);
        
        $dateAndTime = explode(' ', $this->dateAndTime);
        
        $date = explode('-', $dateAndTime[0]);
        $times = explode(':', $dateAndTime[1]);

        $video = null;
        
        if($this->video != null)
            $video = URL::asset("assets/news/{$this->id}/{$this->video}", null, $this->videoServer);

        $category = NewsCategory::join('news_category_relations', 'news_category_relations.categoryId', 'news_categories.id')
                        ->where('news_category_relations.isMain', 1)
                        ->where('news_category_relations.newsId', $this->id)
                        ->select(['news_categories.id', 'news_categories.name', 'news_categories.nameEn'])
                        ->first();
                        
        $otherNewsArr = [];

        if($category != null) {
            $otherNews = NewsCategoryRelations::join('news', 'news.id', 'news_category_relations.newsId')
                ->where('site_id', $this->site_id )
                ->where('news_category_relations.categoryId', $category->id)
                ->where('news_category_relations.newsId', '!=', $this->id)
                ->where('news_category_relations.isMain', 1)
                ->select([
                    'news.id', 'news.pic', 'news.server',
                    'news.title', 'news.meta', 'news.slug', 'news.keyword',
                    'news.titleEn', 'news.metaEn', 'news.slugEn', 'news.keywordEn',
                ])
                ->get();
        
                foreach ($otherNews as $item)
                array_push($otherNewsArr, getNewsMinimal(News::find($item->id)));
        }

        $time =getData(self::$locale,  Verta::createJalali($date[0], $date[1], $date[2], $times[0], $times[1], 0)->format('%d %B %Y  H:i'), date('d M Y ', strtotime($this->updated_at)));
        $createAt=getData(self::$locale,  Verta::createJalali($date[0], $date[1], $date[2], $times[0], $times[1], 0)->format('%d %B %Y  H:i'), date('Y/m/d ', strtotime($this->created_at)));

        return [
            'id' => $this->id,
            'title' => getData(self::$locale, $this->title, $this->titleEn),
            'slug' => $slug,
            'seen' => $this->seen,
            'site_id' => $this->site_id,
            'text' => getData(self::$locale, $this->text, $this->textEn),
            'meta' => getData(self::$locale, $this->meta, $this->metaEn),
            'pic' => URL::asset("assets/news/{$this->id}/{$this->pic}"),
            'url' => route('site.news.show', ['slug' => $slug, 'lang' => self::$locale]),
            'dateAndTime' => $this->created_at,
            'keyword' => getData(self::$locale, $this->keyword, $this->keywordEn),
            'seoTitle' => getData(self::$locale, $this->seoTitle, $this->seoTitleEn),
            'video' => $video,
            'showTime' => $time,
            'createdAt'=> $createAt,
            'author' => isset($this->userId) ? User::find($this->userId)->name : '',
            'username' => 'dorna',
            'rtl' => $this->rtl,
            'tags' => array_filter($this->getTags->pluck(self::$locale == 'fa' ? 'tag' : 'tagEn')->toArray(), function($var) {
                return !empty($var);
            }),

            'category' => $category,
            'otherNews' => (object)$otherNewsArr
        ];
    }
    
    public static function customMake($resource, $locale)
    {
        self::$locale = $locale;
        return (object)parent::make($resource)->toArray(null);
    }
}
