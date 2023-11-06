<?php

namespace App\Http\Resources;

use App\Models\NewsCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsCategoryResource extends JsonResource
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
        return [
            'sub' => NewsCategory::where('parentId', $this->id)->get(),
            'name' => self::$locale == 'fa' ? $this->name : $this->nameEn,
            'icon' => $this->icon
        ];
    }

    public static function customCollection($resource, $locale): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        self::$locale = $locale;
        return parent::collection($resource);
    }

}
