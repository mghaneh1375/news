<?php

namespace App\Http\Resources;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use App\Models\User;

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
        $dateAndTime = explode(' ', $this->dateAndTime);
        $date = explode('-', $dateAndTime[0]);
        $times = explode(':', $dateAndTime[1]);
        $slug = getData(self::$locale, $this->slug, $this->slugEn);
        $time =getData(self::$locale,  Verta::createJalali($date[0], $date[1], $date[2], $times[0], $times[1], 0)->format('%d %B %Y  H:i'), date('Y-m-d', strtotime($this->updated_at)));
        $category = $this->catogoryRelations()->main()->first()->category;
        if($category != null)
            $category = getData(self::$locale, $category->name, $category->nameEn);
        $createAt=getData(self::$locale,  Verta::createJalali($date[0], $date[1], $date[2], $times[0], $times[1], 0)->format('%d %B %Y  H:i'), date('Y/m/d ', strtotime($this->created_at)));
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
            'category'=> $category,
            'dateAndTimeEn' => $time,
            'createdAt'=> $createAt,
            'siteId'=>$this->site_id,
            'author' => isset($this->userId) ? User::find($this->userId)->name : ''
            
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
