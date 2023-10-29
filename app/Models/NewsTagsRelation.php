<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsTagsRelation extends Model
{
    protected $guarded = [];
    protected $table = 'news_tags_relations';
    public $timestamps = false;
}
