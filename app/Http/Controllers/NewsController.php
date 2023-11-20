<?php

namespace App\Http\Controllers;

use App\Models\Advertisement\Advertisement;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsCategoryRelations;
use App\Models\NewsLimboPics;
use App\Models\NewsTags;
use App\Models\NewsTagsRelation;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class NewsController extends Controller
{
    
    public $newsDir =  __DIR__ .'/../../../public/assets/news';

    public function __construct()
    {
        $location = "{$this->newsDir}/limbo/";
        $limbos = NewsLimboPics::where("created_at", "<", Carbon::now()->subDay())->get();

        $loc = "assets/news/limbo";
        $fileWithServer = [];
        foreach ($limbos as $item){
            if($item->server == config('app.ServerNumber')) {
                if (is_file($location . $item->pic))
                    unlink($location . $item->pic);
            }
            else{
                $servST = $item->server."";
                if(isset($fileWithServer[$servST]))
                    array_push($fileWithServer, "{$loc}/{$item->pic}");
                else
                    $fileWithServer[$servST] = ["{$loc}/{$item->pic}"];
            }
            $item->delete();
        }

        foreach ($fileWithServer as $server => $files)
            Controller::sendDeleteFileApiToServer($files, $server);
    }

    public function newsList(){
        $sites = Site::all();
        $selectCols = ['id', 'title', 'userId', 'release', 'updated_at', 'topNews', 'confirm', 'dateAndTime','site_id'];
        $news = News::where('confirm', 1)->select($selectCols)->orderBy('updated_at', 'desc')->get();
        $noneConfirmNews = News::where('confirm', 0)->select($selectCols)->orderBy('updated_at', 'desc')->get();
        foreach ([$news, $noneConfirmNews] as $nItem) {
            foreach ($nItem as $item) {

                $item->user = User::find($item->userId);
                $item->categories = DB::table('news_category_relations')->join('news_categories', 'news_categories.id', 'news_category_relations.categoryId')
                                        ->where('news_category_relations.newsId', $item->id)
                                        ->orderBy('news_category_relations.isMain', 'desc')
                                        ->select(['news_category_relations.newsId', 'news_category_relations.isMain', 'news_categories.name', 'news_categories.id AS categoryId'])
                                        ->get()->toArray();

                $lastUpdate = gregorianToJalali(Carbon::make($item->updated_at)->format('Y-m-d'), '-');
                $item->lastUpdate = $lastUpdate[0] . '/' . $lastUpdate[1] . '/' . $lastUpdate[2] . ' ' . Carbon::make($item->updated_at)->addMinutes(210)->format('H:i');

                if ($item->confirm == 0)
                    $item->status = 'تایید نشده';
                else
                    switch ($item->release) {
                        case 'draft':
                            $item->status = 'پیش نویس';
                            break;
                        case 'released':
                            $item->status = 'منتشر شده';
                            break;
                        case 'future':
                            $item->status = 'در آینده منتشر می شود';
                            $item->futureDate = $item->dateAndTime;
                            break;
                    }

            }
        }

        
        
        return view('admin.newsList', compact(['news', 'noneConfirmNews','sites']));
    }

    public function newsNewPage(Request $request)
    {
        $category = NewsCategory::where('parentId', 0)->get();
        foreach ($category as $item)
            $item->sub = NewsCategory::where('parentId', $item->id)->get();

        $code = rand(10000, 99999);
        $sites = SiteResource::collection(Site::all())->toArray($request);
        return view('admin.newNews', compact(['category', 'code', 'sites']));
    }

    public function editNewsPage($id, Request $request)
    {
        $code = rand(10000, 99999);
        $news = News::find($id);

        if($news == null)
            return Redirect::route('home');

        if($news->slug == null || $news->slug == '')
            $news->slug = makeSlug($news->title);

        if($news->seoTitle == null || $news->seoTitle == '')
            $news->seoTitle = $news->title;

        if($news->pic !=  null)
            $news->pic = URL::asset("assets/news/{$news->id}/{$news->pic}", null,$news->server);

        if($news->video != null)
            $news->video = URL::asset("assets/news/{$news->id}/{$news->video}", null,$news->videoServer);


        $news->category = NewsCategoryRelations::where('newsId', $news->id)->get();
        $mainCategory = NewsCategoryRelations::where('newsId', $news->id)->where('isMain', 1)->first();
        $news->mainCategory = $mainCategory != null ? $mainCategory->categoryId : 0;
        $news->tags = $news->getTags->pluck('tag')->toArray();
        if($news->getTags != null)
            $news->tagsEn = $news->getTags->pluck('tagEn')->toArray();
        else
            $news->tagsEn =[];




        $category = NewsCategory::where('parentId', 0)->get();
        foreach ($category as $item)
            $item->sub = NewsCategory::where('parentId', $item->id)->get();

        $dateAndTime = explode(' ', $news->dateAndTime);
        $news->time = $dateAndTime[1];
        $news->date = str_replace('/', '-', $dateAndTime[0]);
        $news->date = convertNumber('fa', $news->date);
        $sites = SiteResource::collection(Site::all())->toArray($request);

        return view('admin.newNews', compact(['news', 'category', 'code', 'sites']));
    }

    public function newsTagSearch(){
        if(isset($_GET['text'])){
            $tags = NewsTags::where('tag', 'LIKE', '%'.$_GET['text'].'%')->get()->pluck('tag')->toArray();
            return response()->json(['status' => 'ok', 'result' => $tags, 'value' => $_GET['text']]);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function uploadNewsPic(Request $request)
    {
        $user = auth()->user();
        $news = null;
        $data = json_decode($request->data);
        if(isset($data->code)){
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {

                if(!is_dir($this->newsDir))
                    mkdir($this->newsDir);

                $nLocation = "{$this->newsDir}/limbo";
                if(!is_dir($nLocation))
                    mkdir($nLocation);

                if(isset($data->newsId) && $data->newsId != 0) {
                    $news = News::find($data->newsId);
                    if($news != null) {
                        $nLocation = "{$this->newsDir}/{$news->id}";
                        if(!is_dir($nLocation))
                            mkdir($nLocation);
                    }
                }
                $size = [[
                    'width' => 900,
                    'height' => null,
                    'name' => '',
                    'destination' => $nLocation
                ]];

                $image = $request->file('file');
                $nFileName = resizeImage($image, $size);
                if($nFileName == 'error'){
                    $response = [ 'uploaded' => false, 'error' => [ 'message' => 'error in resize image'] ];
                    return response()->json($response);
                }

                if($news == null) {
                    $limbo = NewsLimboPics::create([
                        'userId' => $user->id,
                        'code' => $data->code,
                        'server' => config('app.ServerNumber'),
                        'pic' => $nFileName,
                    ]);

                    $url = URL::asset("assets/news/limbo/{$nFileName}", null, config('app.ServerNumber'));
                    $limboId =  $limbo->id;
                }
                else {
                    $url = URL::asset("assets/news/{$news->id}/{$nFileName}", null, config('app.ServerNumber'));
                    $limboId =  0;
                }
                $response = [ 'uploaded' => true, 'url' => $url, 'limboId' => $limboId];


//                $nFileName = $user->id.'_'.$nFileName;
//                $resizeLocation = $nLocation.'/'.$nFileName;
//                $destinationLocation = $nLocation.'/'.$mainFileName;

//                $fileType = explode('.', $nFileName);
//                $fileType = end($fileType);
//                $needToConvert = true;
//                if($fileType == 'png')
//                    $needToConvert = false;
//                else if($fileType == 'jpg' || $fileType == 'jpeg')
//                    $img = imagecreatefromjpeg($resizeLocation);
//                else if($fileType == 'gif')
//                    $img = imagecreatefromgif($resizeLocation);
//                else if($fileType == 'webp')
//                    $img = imagecreatefromwebp($resizeLocation);
//                else{
//                    if(is_file($resizeLocation))
//                        unlink($resizeLocation);
//                    $response = [ 'uploaded' => false, 'error' => [ 'message' => 'file type error'] ];
//                    return response()->json($response);
//                }

//                if($needToConvert)
//                    $image = imagepng($img, $destinationLocation, 9);
//                if($image || !$needToConvert){
//
//                    if($news == null) {
//                        $limbo = NewsLimboPics::create([
//                            'userId' => $user->id,
//                            'code' => $data->code,
//                            'pic' => $mainFileName,
//                        ]);
//
//                        $url = URL::asset('assets/news/limbo/'.$mainFileName);
//                        $limboId =  $limbo->id;
//                    }
//                    else {
//                        $url = URL::asset("assets/news/{$news->id}/{$mainFileName}");
//                        $limboId =  0;
//                    }
//
//                    if(is_file($resizeLocation) && $needToConvert)
//                        unlink($resizeLocation);
//
//                    $response = [ 'uploaded' => true, 'url' => $url, 'limboId' => $limboId];
//                }
//                else
//                    $response = [ 'uploaded' => false, 'error' => [ 'message' => 'error in convert'] ];
            }
            else
                $response = [ 'uploaded' => false, 'error' => [ 'message' => 'could not upload this image1'] ];
        }
        else
            $response = [ 'uploaded' => false, 'error' => [ 'message' => 'less data'] ];

        return response()->json($response);
    }

    public function storeNews(Request $request){

        $request->validate([
            'id' => 'required',
            'releaseType' => 'required',
            'category' => 'required',
            'site' => 'required|integer|exists:site,id',
            'createdAt' => 'nullable' // todo: date validator
        ]);

        $news = null;

        if($request->id != 0)
            $news = News::find($request->id);

        if($news == null){
            $news = new News();
            $news->userId = \auth()->user()->id;
            $news->dateAndTime = verta()->now()->format('Y/m/d H:i');
        }

        if($request->releaseType == 'future'){
            $date = convertNumber('en', $request->date);
            $date = convertDateToString($date,'/');
            $news->dateAndTime = $date . ' '. $request->time;
        }
        else if($request->releaseType == 'released' && $news->release != 'released')
            $news->dateAndTime = verta()->now()->format('Y/m/d H:i');
        else if($request->releaseType == 'draft')
            $news->dateAndTime = verta()->now()->format('Y/m/d H:i');

        $news->text = ' ';
        $news->confirm = 1;
        $news->title = $request->title;
        $news->meta = $request->meta;
        $news->keyword = $request->keyword;
        $news->seoTitle = $request->seoTitle;
        $news->textEn = ' ';
        $news->metaEn = $request->metaEn;
        $news->titleEn = $request->titleEn;
        $news->keywordEn = $request->keywordEn;
        $news->seoTitleEn = $request->seoTitleEn;
        $news->slugEn = makeSlug($request->slugEn);
        $news->slug = makeSlug($request->slug);
        $news->release = $request->releaseType;
        $news->site_id = $request->site;

        if($request->has('createdAt'))
            $news->created_at = date('Y-m-d H:m:s', strtotime(ShamsiToMilady(convertNumber('en', $request['createdAt'])) . " 00:00"));

	    $news->rtl = ($request->has('direction') && $request->direction == 'ltr') ? 0 : 1;

        if($request->slug != null)
            $news->slug = makeSlug($request->slug);
        else if($request->keword != null)
            $news->slug = makeSlug($request->keyword);

        $loca = $this->newsDir;
        $limboDestination = "{$loca}/limbo";

        $news->save();

        $newsId = $news->id;

        $newDestination = "{$loca}/{$newsId}";

        if(!is_dir($newDestination))
            mkdir($newDestination);

        $description = $request->description;
        $limbos = explode(',', $request->limboPicIds);
        $limboPics = NewsLimboPics::whereIn('id', $limbos)->where('userId', auth()->user()->id)->get();
        foreach ($limboPics as $item){
            if(is_file("{$limboDestination}/{$item->pic}")) {
                rename( "{$limboDestination}/{$item->pic}",  "{$newDestination}/{$item->pic}");
                $url = URL::asset("assets/news/limbo/{$item->pic}", null, $item->server);
                $newUrl = URL::asset("assets/news/{$newsId}/{$item->pic}", null, config('app.ServerNumber'));
                $description = str_replace($url, $newUrl, $description);
            }
            $item->delete();
        }
        $notUseLimboPics = NewsLimboPics::where('code', $request->code)->where('userId', auth()->user()->id)->get();
        foreach ($notUseLimboPics as $item){
            $locationss = "{$this->newsDir}/limbo/";
            if(is_file($locationss.$item->pic))
                unlink($locationss.$item->pic);
            $item->delete();
        }


        
        $descriptionEn = $request->descriptionEn;
        $limbos = explode(',', $request->limboPicIds);
        $limboPics = NewsLimboPics::whereIn('id', $limbos)->where('userId', auth()->user()->id)->get();
        foreach ($limboPics as $item){
            if(is_file("{$limboDestination}/{$item->pic}")) {
                rename( "{$limboDestination}/{$item->pic}",  "{$newDestination}/{$item->pic}");
                $url = URL::asset("assets/news/limbo/{$item->pic}", null, $item->server);
                $newUrl = URL::asset("assets/news/{$newsId}/{$item->pic}", null, config('app.ServerNumber'));
                $descriptionEn = str_replace($url, $newUrl, $descriptionEn);
            }
            $item->delete();
        }
        $news->textEn = $descriptionEn;
        $news->text = $description;
        $news->save();



        $tagId = [];
        if($request->tags != null){
            $tags = json_decode($request->tags);
            foreach ($tags as $item){
                $tt = NewsTags::firstOrCreate(['tag' => $item]);
                array_push($tagId, $tt->id);
            }
        }
        if($request->tagsEn != null){
            $tagsEn = json_decode($request->tagsEn);
            foreach ($tagsEn as $item){
                $tt = NewsTags::firstOrCreate(['tagEn' => $item]);
                array_push($tagId, $tt->id);
            }
        }

        NewsTagsRelation::where('newsId', $newsId)->whereNotIn('tagId', $tagId)->delete();
        foreach ($tagId as $id)
            NewsTagsRelation::firstOrCreate(['newsId' => $newsId, 'tagId' => $id]);

        $categories = json_decode($request->category);
        $categoryId = [];
        $mainCategoryId = 0;
        foreach ($categories as $item){
            array_push($categoryId, $item->id);
            if($item->thisIsMain == 1)
                $mainCategoryId = $item->id;
        }
        NewsCategoryRelations::where('newsId', $news->id)->whereNotIn('categoryId', $categoryId)->delete();
        foreach ($categoryId as $item){
            $safCatId = NewsCategoryRelations::firstOrCreate(['newsId' => $news->id,'categoryId' => $item]);
            $safCatId->update(['isMain' => 0]);
            if($safCatId->categoryId == $mainCategoryId)
                $safCatId->update(['isMain' => 1]);
        }

        if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
            $location = "{$this->newsDir}/{$news->id}";
            if(!file_exists($location))
                mkdir($location);

            if($news->pic != null && $news->pic != ''){
                if($news->sever == config('app.ServerNumber')){
                    if(file_exists("{$location}/{$news->pic}"))
                        unlink("{$location}/{$news->pic}");
                }
                else
                    Controller::sendDeleteFileApiToServer(["assets/news/{$news->id}/{$news->pic}"], $news->server);
            }

            $size = [[
                'width' => 900,
                'height' => null,
                'name' => '',
                'destination' => $location
            ]];

            $nFileName = resizeImage($request->file('pic'), $size);

            $news->pic = $nFileName;
            $news->server = config('app.ServerNumber');
            $news->save();
        }

        return response()->json(['status' => 'ok', 'result' => $news->id]);
    }

    public function storeNewsVideo(Request $request){
        if(isset($_FILES['video']) && $_FILES['video']['error'] == 0 && isset($request->newsId)){
            $news = News::find($request->newsId);
            if($news == null)
                return response()->json(['status' => 'error2']);

            $newsDir = "{$this->newsDir}/{$news->id}";
            if(!is_dir($newsDir))
                mkdir($newsDir);

            $fileType = explode('.', $_FILES['video']['name']);
            $videoFileName = time().'_'.rand(100,999).'.'.end($fileType);
            $checkUpload = move_uploaded_file($_FILES['video']['tmp_name'], "{$newsDir}/{$videoFileName}");
            if($checkUpload){
                if($news->video != null){
                    if($news->videoServer == config('app.ServerNumber')) {
                        if (is_file("{$newsDir}/{$news->video}"))
                            unlink("{$newsDir}/{$news->video}");
                    }
                    else
                        Controller::sendDeleteFileApiToServer(["assets/news/{$news->id}/{$news->video}"], $news->videoServer);
                }

                $news->video = $videoFileName;
                $news->videoServer = config('app.ServerNumber');
                $news->save();

                return response()->json(['status' => 'ok']);
            }
            else
                return response()->json(['status' => 'error3']);
        }
        else if(isset($_FILES['video']) && $_FILES['video']['error'] == 0)
            return response()->json(['status' => 'max_file_size_error']);
        else
            return response()->json(['status' => 'error1']);
    }

    public function deleteNews(Request $request)
    {
        if(isset($request->newsId)) {
            try {
                $news = News::find($request->newsId);
                if($news != null){
                    NewsTagsRelation::where('newsId', $news->id)->delete();
                    NewsCategoryRelations::where('newsId', $news->id)->delete();

                    $serversFiles = findImagesFromText($news->text);

                    if(isset($serversFiles[(string)$news->server]))
                        array_push($serversFiles[(string)$news->server], "assets/news/{$news->id}/{$news->pic}");
                    else
                        $serversFiles[(string)$news->server] = ["assets/news/{$news->id}/{$news->pic}"];

                    if(isset($serversFiles[(string)$news->videoServer]))
                        array_push($serversFiles[(string)$news->videoServer], "assets/news/{$news->id}/{$news->video}");
                    else
                        $serversFiles[(string)$news->videoServer] = ["assets/news/{$news->id}/{$news->video}"];

                    foreach($serversFiles as $server => $files)
                        Controller::sendDeleteFileApiToServer($files, $server);


                    $news->delete();
                    return response()->json(["status" => "ok"]) ;
                }
                else
                    return response()->json(["status" => "nok2"]) ;
            }
            catch (\Exception $x) {
                return response()->json(["status" => "nok3"]);
            }
        }
        else
            return response()->json(["status" => "nok1"]);
    }

    public function deleteVideoNews(Request $request)
    {
        if(isset($request->newsId)){
            $news = News::find($request->newsId);
            if($news == null)
                return response()->json(['status' => 'error2']);


            if($news->video != null) {
                if ($news->videoServer == config('app.ServerNumber')) {
                    if(is_file("{$this->newsDir}/{$news->id}/{$news->video}"))
                        unlink("{$this->newsDir}/{$news->id}/{$news->video}");
                }
                else
                    Controller::sendDeleteFileApiToServer(["assets/news/{$news->id}/{$news->video}"], $news->videoServer);
            }


            $news->video = null;
            $news->save();

            return response()->json(['status' => 'ok']);
        }
        else
            return response()->json(['status' => 'error1']);
    }

    public function addToTopNews(Request $request){
        $news = News::find($request->id);
        $news->topNews = $news->topNews == 1 ? 0 : 1;
        $news->save();

        return response()->json(['status' => 'ok']);
    }

    public function removeAllTopNews(){

        News::where('topNews', 1)->update(['topNews' => 0]);
        return response()->json(['status' => 'ok']);
    }
}
