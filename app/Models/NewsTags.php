<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsTags extends Model
{
    protected $guarded = [];
    
    protected $fillable = [
        'tag', 'tagEn'
    ];

    protected $table = 'news_tags';
    public $timestamps = false;
}
