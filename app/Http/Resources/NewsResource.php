<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        $slug = self::$locale == 'fa' ? $this->slug : $this->slugEn;
        
        return [
            'id' => $this->id,
            'title' => getData(self::$locale, $this->title, $this->titleEn),
            'text' => getData(self::$locale, $this->text, $this->textEn),
            'meta' => getData(self::$locale, $this->meta, $this->metaEn),
            'pic' => URL::asset("assets/news/{$this->id}/{$this->pic}", null, $this->server),
            'url' => route('site.news.show', ['slug' => $slug, 'lang' => self::$locale]),
            'dateAndTime' => $this->dateAndTime,
            'keyword' => getData(self::$locale, $this->keyword, $this->keywordEn),
            'seoTitle' => getData(self::$locale, $this->seoTitle, $this->seoTitleEn),
            'video' => $this->video
        ];
    }
    
    public static function customMake($resource, $locale)
    {
        self::$locale = $locale;
        return (object)parent::make($resource)->toArray(null);
    }
}
