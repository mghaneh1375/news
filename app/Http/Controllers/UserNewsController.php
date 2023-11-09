<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsCategoryRelations;
use App\Models\NewsTags;
use App\Models\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\NewsResource;

class UserNewsController extends Controller
{
    public function newsMainPage($lang="fa")
    {
        $selectCol = [
            'id', 'pic', 'video', 'server','dateAndTime',
            'title', 'meta', 'slug', 'keyword',
            'titleEn', 'metaEn', 'slugEn', 'keywordEn',
        ];

        $sliderNews = News::youCanSee()->orderByDesc('dateAndTime')->select($selectCol)->take(5)->get();
        $sideSliderNews = News::youCanSee()->orderByDesc('dateAndTime')->select($selectCol)->skip(5)->take(2)->get();
        if(count($sideSliderNews) < 4){
            $remaining = 4 - count($sideSliderNews);
            $skip = 5 - $remaining;
            $sideSliderNews = News::youCanSee()->select($selectCol)->orderBy('created_at')->skip($skip)->take(2)->get();
        }

        foreach ([$sliderNews, $sideSliderNews] as $section){
            foreach ($section as $item)
                $item = getNewsMinimal($item);
        }

        $mostViewNews = News::youCanSee()->orderBy('seen','desc')->select($selectCol)->take(6)->get();
        if(count($mostViewNews) < 4){
            $remaining = 4 - count($mostViewNews);
            $skip = 5 - $remaining;
            $mostViewNews = News::youCanSee()->select($selectCol)->orderBy('created_at')->skip($skip)->take(4)->get();
        }

        $allCategories = NewsCategory::where('parentId', 0)->get();
        foreach ($allCategories as $category){
            $category->allSubIds = NewsCategory::where('id', $category->id)->orWhere('parentId', $category->id)->pluck('id')->toArray();
            $category->news = News::youCanSee()->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
                                    ->where('news_category_relations.categoryId', $category->id)
                                    ->where('news_category_relations.isMain', 1)
                                    ->select(['news.id', 'news.title', 'news.meta', 'news.slug', 'news.keyword', 'news.pic', 'news.server', 'news.video'])
                                    ->orderByDesc('news.dateAndTime')
                                    ->take(7)->get();

            foreach ($category->news as $item)
                $item = getNewsMinimal($item);
        }

        $topNews = News::youCanSee()->where('topNews', 1)->orderByDesc('dateAndTime')->select($selectCol)->get();
        $topNewsOutput = [];

        foreach ($topNews as $item)
            array_push($topNewsOutput, getNewsMinimal($item));

        $topNews = $topNewsOutput;

        $lastVideos = News::youCanSee()->whereNotNull('video')->orderByDesc('dateAndTime')->select($selectCol)->take(10)->get();
        
        foreach ($lastVideos as $item)
            $item = getNewsMinimal($item);

        $lastNews = News::youCanSee()->whereNull('video')->orderBy('dateAndTime')->select($selectCol)->take(6)->get();
        $lastNewsOutput = [];

        foreach ($lastNews as $item)
            array_push($lastNewsOutput, getNewsMinimal($item));

        $lastNews = $lastNewsOutput;

        return view('user.newsMainPage', compact(['sliderNews', 'sideSliderNews','mostViewNews', 'allCategories', 'topNews', 'lastVideos','lastNews']));
    }

    public function newsShow($lang, $slug)
    {
        $news = News::youCanSee()->where('slug', $slug)->orWhere('slugEn', $slug)->first();
        if($news == null)
            return redirect(route('site.news.main', ['lang' => $lang]));

        $news = NewsResource::customMake($news, $lang);
        return view('user.newsShow', compact(['news']));
    }

    public function newsListPage($lang, $kind, $content = ''){

        $header = '';
        if($kind == 'all')
            $header = 'آخرین اخبار';
        else if($kind == 'category')
            $header = 'اخبار ' . $content;
        else if($kind == 'tag')
            $header = 'اخبار مرتبط با  ' . $content;
        else if($kind == 'content')
            $header = 'اخبار مرتبط با  ' . $content;

        return view('user.newsList', compact(['kind', 'content', 'header']));
    }

    public function newsListElements()
    {
        $selectCol = ['id', 'title', 'meta', 'slug', 'dateAndTime', 'keyword', 'pic', 'server', 'video'];
        $joinSelectCol = ['news.id', 'news.title', 'news.meta', 'news.slug', 'news.dateAndTime', 'news.keyword', 'news.pic', 'news.server', 'news.video'];

        $kind = $_GET['kind'];
        $content = $_GET['content'];
        $take = $_GET['take'];
        $page = $_GET['page'];

        if($kind == 'all'){
            $news = News::youCanSee()->orderByDesc('dateAndTime')->select($selectCol)->skip($page*$take)->take($take)->get();
        }
        else if($kind == 'category'){
            $category = NewsCategory::where('name', $content)->first();
            $news = News::youCanSee()->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
                        ->where('news_category_relations.categoryId', $category->id)
                        ->orderByDesc('news.dateAndTime')
                        ->select($joinSelectCol)
                        ->skip($page*$take)->take($take)
                        ->get();
        }
        else if($kind == 'tag'){
            $tag = NewsTags::where('tag', $content)->first();
            $news = News::youCanSee()->join('news_tags_relations', 'news_tags_relations.newsId', 'news.id')
                        ->where('news_tags_relations.tagId', $tag->id)
                        ->orderByDesc('news.dateAndTime')
                        ->select($joinSelectCol)
                        ->skip($page*$take)->take($take)
                        ->get();
        }
        else if($kind == 'content'){
            $newsIdInTag = NewsTags::join('news_tags_relations', 'news_tags_relations.tagId', 'newsTags.id')->where('newsTags.tag', 'LIKE', "%$content%")->pluck('news_tags_relations.newsId')->toArray();
            $newsIdInKeyWord = News::youCanSee()->where('keyword', 'LIKE', "%$content%")->pluck('id')->toArray();

            $newsId = array_merge($newsIdInTag, $newsIdInKeyWord);

            $news = News::youCanSee()->whereIn('id', $newsId)
                                    ->orderByDesc('dateAndTime')
                                    ->select($selectCol)
                                    ->skip($page*$take)
                                    ->take($take)
                                    ->get();
        }
        
        foreach($news as $item)
            $item = getNewsMinimal($item);

        return response()->json(['status' => 'ok', 'result' => $news]);
    }
}
