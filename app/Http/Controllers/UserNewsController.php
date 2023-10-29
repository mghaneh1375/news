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

class UserNewsController extends Controller
{
    public function newsMainPage($lang="fa")
    {
        \App::setLocale($lang);
        $selectCol = ['id', 'title', 'meta', 'slug', 'keyword', 'pic', 'video', 'server'];
        $sliderNews = News::youCanSee()->orderByDesc('dateAndTime')->select($selectCol)->take(5)->get();
        $sideSliderNews = News::youCanSee()->orderByDesc('dateAndTime')->select($selectCol)->skip(5)->take(4)->get();
        if(count($sideSliderNews) < 4){
            $remaining = 4 - count($sideSliderNews);
            $skip = 5 - $remaining;
            $sideSliderNews = News::youCanSee()->select($selectCol)->orderBy('created_at')->skip($skip)->take(4)->get();
        }

        foreach ([$sliderNews, $sideSliderNews] as $section){
            foreach ($section as $item)
                $item = getNewsMinimal($item);
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
        foreach ($topNews as $item)
            $item = getNewsMinimal($item);

        $lastVideos = News::youCanSee()->where('video', '!=', null)->orderByDesc('dateAndTime')->select($selectCol)->take(5)->get();
        foreach ($lastVideos as $item)
            $item = getNewsMinimal($item);

        return view('user.newsMainPage', compact(['sliderNews', 'sideSliderNews', 'allCategories', 'topNews', 'lastVideos']));
    }

    public function newsShow($slug, $lang="fa")
    {
        \App::setLocale($lang);
        $news = News::youCanSee()->where('slug', $slug)->first();
        if($news == null)
            return redirect(route('site.news.main'));

        $news->tags = $news->getTags->pluck('tag')->toArray();

        $news->pic = URL::asset("assets/news/{$news->id}/{$news->pic}", null, $news->server);
        
        $news->category = NewsCategory::join('news_category_relations', 'news_category_relations.categoryId', 'news_categories.id')
                                    ->where('news_category_relations.isMain', 1)
                                    ->where('news_category_relations.newsId', $news->id)
                                    ->select(['news_categories.id', 'news_categories.name'])
                                    ->first();
        
        $otherNews = NewsCategoryRelations::join('news', 'news.id', 'news_category_relations.newsId')
                            ->where('news_category_relations.categoryId', $news->category->id)
                            ->where('news_category_relations.newsId', '!=', $news->id)
                            ->where('news_category_relations.isMain', 1)
                            ->select(['news.id', 'news.title', 'news.meta', 'news.slug', 'news.keyword', 'news.pic', 'news.server'])
                            ->get();
        foreach ($otherNews as $item)
            $item = getNewsMinimal($item);

        $dateAndTime = explode(' ', $news->dateAndTime);
        
        $date = explode('-', $dateAndTime[0]);
        $times = explode(':', $dateAndTime[1]);

        $news->showTime = Verta::createJalali($date[0], $date[1], $date[2], $times[0], $times[1], 0)->format('%d %B %Y  H:i');
        $news->author = 'کوچیتا';

        if($news->video != null)
            $news->video = URL::asset("assets/news/{$news->id}/{$news->video}", null, $news->videoServer);

        return view('user.newsShow', compact(['news', 'otherNews']));
    }

    public function newsListPage($kind, $content = ''){

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
