<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

function trueShowForTextArea($text){
    $breaks = array("<br />","<br>","<br/>");
    $text = str_ireplace("\r\n", "", $text);
    $text = str_ireplace($breaks, "\r\n", $text);

    return $text;
}

function makeSlug($name){
    $name = str_replace(':', '', $name);
    $name = str_replace('\\', '', $name);
    $name = str_replace('|', '', $name);
    $name = str_replace('/', '', $name);
    $name = str_replace('*', '', $name);
    $name = str_replace('?', '', $name);
    $name = str_replace('<', '', $name);
    $name = str_replace('>', '', $name);
    $name = str_replace('"', '', $name);
    $name = str_replace(' ', '_', $name);

    return $name;
}

function resizeImage($pic, $size){
    try {
        $image = $pic;
        $fileType = explode('.', $image->getClientOriginalExtension());
        $fileType = last($fileType);

        $fileName = time().'_'.rand(1000, 9999) . '.' . $fileType;

        foreach ($size as $item){
            $input['imagename'] = $item['name'] .  $fileName ;
            $destinationPath = $item['destination'];
            $img = \Image::make($image->getRealPath());
            $width = $img->width();
            $height = $img->height();
            $ration = $width/$height;


            if($item['height'] != null && $item['width'] != null){
                $nWidth = $ration * $item['height'];
                $nHeight = $item['width'] / $ration;
                if($nWidth <= $item['width']) {
                    $height = $nHeight;
                    $width = $item['width'];
                }
                else if($nHeight <= $item['height']) {
                    $width = $nWidth;
                    $height = $item['height'];
                }
            }
            else {
                if ($item['width'] == null || $width > $item['width'])
                    $width = $item['width'];

                if ($item['height'] == null || $height > $item['height'])
                    $height = $item['height'];
            }

            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
        }
        return $fileName;
    }
    catch (Exception $exception){
        return 'error';
    }
}

function resizeUploadedImage($pic, $size, $fileName = ''){
    try {
        $image = $pic;
        if($fileName == '') {
            $randNum = random_int(100, 999);
            if($image->getClientOriginalExtension() == '')
                $fileName = time() . $randNum . '.jpg';
            else
                $fileName = time() . $randNum . '.' . $image->getClientOriginalExtension();
        }

        foreach ($size as $item){
            $input['imagename'] = $item['name'] .  $fileName ;
            $destinationPath = $item['destination'];
            $img = \Image::make($image);
            $width = $img->width();
            $height = $img->height();

            if($item['height'] != null && $item['width'] != null){
                $ration = $width/$height;
                $nWidth = $ration * $item['height'];
                $nHeight = $item['width'] / $ration;
                if($nWidth < $item['width']) {
                    $height = $nHeight;
                    $width = $item['width'];
                }
                else if($nHeight < $item['height']) {
                    $width = $nWidth;
                    $height = $item['height'];
                }
            }
            else if($item['height'] != null || $item['width'] != null) {
                if ($item['width'] == null || $width > $item['width'])
                    $width = $item['width'];

                if ($item['height'] == null || $height > $item['height'])
                    $height = $item['height'];
            }

            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
        }

        return $fileName;
    }
    catch (Exception $exception){
        return 'error';
    }
}


function gregorianToJalali($time, $splite = '-'){
    include_once 'jdate.php';

    $date = explode($splite, $time);
    $date = gregorian_to_jalali($date[0], $date[1], $date[2]);

    return $date;
}

function convertNumber($kind , $number){

    $en = array("0","1","2","3","4","5","6","7","8","9");
    $fa = array("۰","۱","۲","۳","۴","۵","۶","۷","۸","۹");

    if($kind == 'en')
        $number = str_replace($fa, $en, $number);
    else
        $number = str_replace($en, $fa, $number);

    return $number;
}

function convertDateToString($date, $between = '') {
    $subStrD = explode('/', $date);
    if(count($subStrD) == 1)
        $subStrD = explode(',', $date);

    if(strlen($subStrD[1]) == 1)
        $subStrD[1] = "0" . $subStrD[1];

    if(strlen($subStrD[2]) == 1)
        $subStrD[2] = "0" . $subStrD[2];

    return $subStrD[0] .$between. $subStrD[1] .$between. $subStrD[2];
}

function getNewsMinimal($news){
    $locale = App::getLocale();
    $news->pic = URL::asset("assets/news/{$news->id}/{$news->pic}", null, $news->server);
    $news->url = route('site.news.show', ['slug' => getData($locale, $news->slug, $news->slugEn), 'lang' => $locale]);
    
    return $news;
}

function findImagesFromText($text){
    $nowPos = 0;
    $serversFiles = [];
    $maxWhileWork = 500;
    $i = 0;

    while($i < $maxWhileWork){
        $pos = strpos($text, 'koochita.com', $nowPos);
        if($pos === false)
            break;

        $firstImgPos = strpos($text, '_images', $pos);
        $endImgPos = strpos($text, "\"", $firstImgPos);
        $location = substr($text, $firstImgPos, ($endImgPos-$firstImgPos));

        $server = (string)$text[$pos-2];
        if($server != '.') {
            if($server === 'c') $server = '1';

            if (isset($serversFiles[$server]))
                array_push($serversFiles[$server], $location);
            else
                $serversFiles[$server] = [$location];
        }
        $nowPos = $pos+6;
        $i++;
    }

    return $serversFiles;
}

function getData($lang, $faData, $enData) {
    
    if($lang == 'fa' && $faData != null)
        return $faData;

    if($lang == 'en' && $enData != null)
        return $enData;

    if($enData != null)
        return $enData;

    return $faData;
}

?>