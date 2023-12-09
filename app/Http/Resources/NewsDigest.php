<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class NewsDigest extends JsonResource
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
        
        $category = $this->load(['categories' => function ($q) {
            $q->where('isMain', '=', true);
        }])->categories[0];

        $category = getData(self::$locale, $category->name, $category->nameEn);

        return [
            'id'  => $this->id,
            'slug' => $slug,
            'title' => getData(self::$locale, $this->title, $this->titleEn),
            'meta' => getData(self::$locale, $this->meta, $this->metaEn),
            'pic' => URL::asset("assets/news/{$this->id}/{$this->pic}"),
            'url' => route('site.news.show', ['slug' => $slug, 'lang' => self::$locale]),
            'dateAndTime' => $this->dateAndTime,
            'keyword' => getData(self::$locale, $this->keyword, $this->keywordEn),
            'video' => $this->video,
            'category'=> $category
        ];
    }
    
    public static function customCollection($resource, $locale): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        self::$locale = $locale;
        return parent::collection($resource);
    }

    public static function customMake($resource, $locale)
    {
        self::$locale = $locale;
        return (object)parent::make($resource)->toArray(null);
    }

}
