<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsCategoryRelations;
use App\Models\NewsTags;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use App\Http\Resources\NewsDigest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\NewsResource;

class UserNewsController extends Controller
{
    public function newsMainPage($lang="fa",Request $request)
    {
        $selectCol = [
            'id', 'pic', 'video', 'server','dateAndTime',
            'title', 'meta', 'slug', 'keyword',
            'titleEn', 'metaEn', 'slugEn', 'keywordEn',
        ];
        $news =  News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderByDesc('dateAndTime')->get();

        $sliderNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderByDesc('dateAndTime')->select($selectCol)->take(5)->get();
        // $sliderNews =NewsDigest::customMake($news,$lang)->take(5)->get();

        News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderByDesc('dateAndTime')
            ->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
            ->join('news_categories', 'news_categories.id', 'news_category_relations.categoryId')
            ->where('news_category_relations.isMain', 1)
            ->select([
                'news.id', 'news.pic', 'news.server',
                'news.title', 'news.meta', 'news.slug', 'news.keyword',
                'news.titleEn', 'news.metaEn', 'news.slugEn', 'news.keywordEn','news_categories.name','news_categories.nameEn',
            ])->take(5)->get();

        $sideSliderNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderByDesc('dateAndTime')->select($selectCol)->skip(5)->take(2)->get();
        // $sideSliderNews =NewsDigest::customMake($news,$lang)->skip(5)->take(2)->get();
        News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderByDesc('dateAndTime')
            ->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
            ->join('news_categories', 'news_categories.id', 'news_category_relations.categoryId')
            ->where('news_category_relations.isMain', 1)
            ->select([
                'news.id', 'news.pic', 'news.server',
                'news.title', 'news.meta', 'news.slug', 'news.keyword',
                'news.titleEn', 'news.metaEn', 'news.slugEn', 'news.keywordEn','news_categories.name','news_categories.nameEn',
            ])->skip(5)->take(2)->get();
        if(count($sideSliderNews) < 4){
            $remaining = 4 - count($sideSliderNews);
            $skip = 5 - $remaining;
            $sideSliderNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->select($selectCol)->orderBy('created_at')->skip($skip)->take(2)->get();
            // $sideSliderNews =NewsDigest::customMake($news,$lang)->skip($skip)->take(2)->get();
            News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderByDesc('dateAndTime')
                ->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
                ->join('news_categories', 'news_categories.id', 'news_category_relations.categoryId')
                ->where('news_category_relations.isMain', 1)
                ->select([
                    'news.id', 'news.pic', 'news.server',
                    'news.title', 'news.meta', 'news.slug', 'news.keyword',
                    'news.titleEn', 'news.metaEn', 'news.slugEn', 'news.keywordEn','news_categories.name','news_categories.nameEn',
                ])->skip($skip)->take(2)->get();
        }

        $sectionOutput = [];
        foreach ([$sliderNews, $sideSliderNews] as $section){
            foreach ($section as $item)
                array_push($sectionOutput, getNewsMinimal($item));
        }
        $sliderNews = $sectionOutput;

        $mostViewNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderBy('seen','desc')->select($selectCol)->take(5)->get();
        if(count($mostViewNews) < 4){
            $remaining = 4 - count($mostViewNews);
            $skip = 5 - $remaining;
            $mostViewNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->select($selectCol)->orderBy('created_at')->skip($skip)->take(4)->get();
        }
        $mostViewNewsOutput = [];

        foreach ($mostViewNews as $item)
            array_push($mostViewNewsOutput, getNewsMinimal($item));

        $mostViewNews = $mostViewNewsOutput;


        $allCategories = NewsCategory::where('parentId', 0)->where('top', 1)->get();

        foreach ($allCategories as $category){
            $category->allSubIds = NewsCategory::where('id', $category->id)->orWhere('parentId', $category->id)->pluck('id')->toArray();
            
            $category->news = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
                                    ->where('news_category_relations.categoryId', $category->id)
                                    ->where('news_category_relations.isMain', 1)
                                    ->orderByDesc('news.dateAndTime')
                                    ->take(7)->get();
            
            $allNews = [];

            foreach ($category->news as $item)
                array_push($allNews, getNewsMinimal($item));

            $category->news = $allNews;

        }
        $tpNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->where('topNews', 1)->orderByDesc('dateAndTime')->take(5)->get();
        $topNews = NewsDigest::customCollection($tpNews , $lang)->toArray($request);
        // dd($topNews);   
        // $topNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->where('topNews', 1)->orderByDesc('dateAndTime')->select($selectCol)->get();
        // $topNewsOutput = [];

        // foreach ($topNews as $item)
        //     array_push($topNewsOutput, getNewsMinimal($item));
        // $topNews = $topNewsOutput;

    $lastVideos = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->whereNotNull('video')->orderByDesc('dateAndTime')->select($selectCol)->take(10)->get();


        $lastVideosOutput = [];
        foreach ($lastVideos as $item)
            array_push($lastVideosOutput, getNewsMinimal($item));

        $lastVideos = $lastVideosOutput;
        $lastNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->whereNull('video')->orderBy('dateAndTime')->select($selectCol)->skip(2)->take(6)->get();
        $lastNewsOutput = [];

        foreach ($lastNews as $item)
            array_push($lastNewsOutput, getNewsMinimal($item));

        $lastNews = $lastNewsOutput;


        
        $lastNews2 = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->whereNull('video')->orderBy('dateAndTime')->select($selectCol)->take(2)->get();
        $lastNewsOutput = [];

        foreach ($lastNews2 as $item)
            array_push($lastNewsOutput, getNewsMinimal($item));

        $lastNews2 = $lastNewsOutput;
        return view('user.newsMainPage', compact(['sliderNews','lastNews2', 'sideSliderNews','mostViewNews', 'allCategories', 'topNews', 'lastVideos','lastNews']));
    }

    public function newsShow($lang, $slug,Request $request)
    {
        $tpNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->where('topNews', 1)->orderByDesc('dateAndTime')->take(5)->get();
        $topNews = NewsDigest::customCollection($tpNews , $lang)->toArray($request);
        $news = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->where('slug', $slug)->orWhere('slugEn', $slug)->where('site_id', "4")->first();
        if($news == null)
            return redirect(route('site.news.main', ['lang' => $lang]));
        $news = NewsResource::customMake($news, $lang);
        return view('user.newsShow', compact(['news','topNews']));
    }

    public function newsListPage($lang, $kind, $content = '',Request $request){
        $tpNews = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->where('topNews', 1)->orderByDesc('dateAndTime')->take(5)->get();
        $topNews = NewsDigest::customCollection($tpNews , $lang)->toArray($request);

        $header = '';
        if($kind == 'all')
            $header = 'last news';
        else if($kind == 'category')
            $header = $content;
        else if($kind == 'tag')
            $header = 'Related News  ' . $content;
        else if($kind == 'content')
            $header = 'Related News  ' . $content;

        return view('user.newsList', compact(['kind', 'content', 'header','topNews']));
    }

    public function newsListElements()
    {
        $selectCol = [
            'id', 'pic', 'video', 'server','dateAndTime',
            'title', 'meta', 'slug', 'keyword',
            'titleEn', 'metaEn', 'slugEn', 'keywordEn',
        ];

        $joinSelectCol = ['news.id', 'news.title', 'news.meta', 'news.slug', 'news.dateAndTime', 'news.keyword', 'news.pic', 'news.server', 'news.video','news.titleEn', 'news.metaEn', 'news.slugEn','news.keywordEn',];
        $lang= App::getLocale();
        $kind = $_GET['kind'];
        $content = $_GET['content'];
        $take = $_GET['take'];
        $page = $_GET['page'];

        if($kind == 'all'){
            $news = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->orderByDesc('dateAndTime')->select($selectCol)->skip($page*$take)->take($take)->get();
        }
        else if($kind == 'category'){
            $category = NewsCategory::where('name', $content)->orWhere('nameEn', $content)->first();
            $news = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
                        ->where('news_category_relations.categoryId', $category->id)
                        ->orderByDesc('news.dateAndTime')
                        ->select($joinSelectCol)
                        ->skip($page*$take)->take($take)
                        ->get();
        }
        else if($kind == 'tag'){
            $tag = NewsTags::where('tag', $content)->first();
            $news = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->join('news_tags_relations', 'news_tags_relations.newsId', 'news.id')
                        ->where('news_tags_relations.tagId', $tag->id)
                        ->orderByDesc('news.dateAndTime')
                        ->select($joinSelectCol)
                        ->skip($page*$take)->take($take)
                        ->get();
        }
        else if($kind == 'content'){
            $newsIdInTag = NewsTags::join('news_tags_relations', 'news_tags_relations.tagId', 'newsTags.id')->where('newsTags.tag', 'LIKE', "%$content%")->pluck('news_tags_relations.newsId')->toArray();
            $newsIdInKeyWord = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->where('keyword', 'LIKE', "%$content%")->pluck('id')->toArray();

            $newsId = array_merge($newsIdInTag, $newsIdInKeyWord);

            $news = News::youCanSee(self::$DEFAULT_SITE_ID, $lang)->whereIn('id', $newsId)
                                    ->orderByDesc('dateAndTime')
                                    ->select($selectCol)
                                    ->skip($page*$take)
                                    ->take($take)
                                    ->get();
        }
        


        $newsOutput = [];
        foreach ($news as $item)
            array_push($newsOutput, getNewsMinimal($item));

        $newsItem = $newsOutput;

        return response()->json(['status' => 'ok', 'result' => $newsItem]);
    }
}
