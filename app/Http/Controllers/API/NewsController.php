<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsCategoryRelations;
use App\Models\NewsTags;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\NewsResource;

class NewsController extends Controller
{

    public function search(Request $request, $lang)
    {
        $postfix = ($lang == 'fa') ? '' : 'En';

        $request->validate([
            'kind' => ['required', Rule::in(['all', 'tag', 'category', 'content'])],
            'content' => 'nullable|string',
            'take' => 'nullable|integer',
            'page' => 'nullable|integer',
        ]);

        $kind = $_GET['kind'];
        $content = $_GET['content'];
        $take = $request->query('take', 10);
        $page = $request->query('page', 1);

        $origin = $request->header('origin');

        if(
            $origin == 'https://tourismfinancialgroup.com' || $origin == 'http://localhost:3000' ||
                $origin == 'https://tit.tourismfinancialgroup.com' || 1 == 1
        ) {
            
            $siteId = 4;

            if($origin == 'https://tourismfinancialgroup.com')
                $siteId = 6;
            else if($origin == 'https://tit.tourismfinancialgroup.com')
                $siteId = 1;

            if($kind == 'all'){
                $news = News::youCanSee($siteId, $lang)->orderByDesc('dateAndTime')->skip(($page - 1)*$take)->take($take)->get();
            }
            else if($kind == 'category'){
                $category = NewsCategory::where('name', $content)->first();
                $news = News::youCanSee($siteId, $lang)->join('news_category_relations', 'news_category_relations.newsId', 'news.id')
                            ->where('news_category_relations.categoryId', $category->id)
                            ->orderByDesc('news.dateAndTime')
                            ->skip(($page-1)*$take)->take($take)
                            ->get();
            }
            else if($kind == 'tag'){
                $tag = NewsTags::where('tag', $content)->first();
                $news = News::youCanSee($siteId, $lang)->join('news_tags_relations', 'news_tags_relations.newsId', 'news.id')
                            ->where('news_tags_relations.tagId', $tag->id)
                            ->orderByDesc('news.dateAndTime')
                            ->skip(($page-1)*$take)->take($take)
                            ->get();
            }
            else if($kind == 'content'){
                $newsIdInTag = NewsTags::join('news_tags_relations', 'news_tags_relations.tagId', 'newsTags.id')->where('newsTags.tag', 'LIKE', "%$content%")->pluck('news_tags_relations.newsId')->toArray();
                $newsIdInKeyWord = News::youCanSee($siteId, $lang)->where('keyword', 'LIKE', "%$content%")->pluck('id')->toArray();

                $newsId = array_merge($newsIdInTag, $newsIdInKeyWord);

                $news = News::youCanSee($siteId, $lang)->whereIn('id', $newsId)
                                        ->orderByDesc('dateAndTime')
                                        ->skip(($page-1)*$take)
                                        ->take($take)
                                        ->get();
            }
            
            $output = [];
            foreach($news as $item)
                array_push($output, getNewsMinimal($item));

            return response()->json(['status' => 'ok', 'data' => $output]);
        }
        
        abort(401);
    }
    

    public function find(Request $request, $lang="fa", $id) {
        return response()->json(['status' => 'ok', 'data' => NewsResource::customMake(News::find($id), $lang)]);
    }


    public function findBySlug(Request $request, $lang="fa", $slug) {

        return response()->json(['status' => 'ok', 'data' => NewsResource::customMake(
            News::where('slugEn', $slug)->orWhere('slug', $slug)->first()
            , $lang)]
        );
    }

    public function slugList(Request $request, $lang) {
        
        $postfix = ($lang == 'fa') ? '' : 'En';
        $origin = $request->header('origin');

        if(
            $origin == 'https://tourismfinancialgroup.com' || $origin == 'http://localhost:3000' ||
                $origin == 'https://tit.tourismfinancialgroup.com' || 1 == 1
        ) {
                
            $siteId = 4;
            
            if($origin == 'https://tourismfinancialgroup.com')
                $siteId = 6;
            else if($origin == 'https://tit.tourismfinancialgroup.com')
                $siteId = 1; 
            if ($lang == 'fa')
                $news = News::youCanSee($siteId, $lang)->select('slug')->where('slug', '!=' , '')->get();
            else
                $news = News::youCanSee($siteId, $lang)->select('slugEn')->where('slugEn', '!=' , '')->get();
                
            return response()->json(['status' => 'ok', 'data' => $news]);
            }    
        
    }

    public function topNews(Request $request, $lang="fa") {

        
        $origin = $request->header('origin');

        if(
            $origin == 'https://tourismfinancialgroup.com' || $origin == 'http://localhost:3000' ||
                $origin == 'https://tit.tourismfinancialgroup.com' || 1 == 1
        ) {
            
            $siteId = 4;

            if($origin == 'https://tourismfinancialgroup.com')
                $siteId = 6;
            else if($origin == 'https://titcompony.com')
                $siteId = 1;

            $news = News::youCanSee($siteId, $lang)
                ->where('news.topNews', true)
                ->orderByDesc('news.dateAndTime')
                ->get();
                
            $output = [];
            foreach($news as $item)
                array_push($output, getNewsMinimal($item));

            return response()->json(['status' => 'ok', 'data' => $output]);
        }

        abort(401);
        
    }

}

?>
