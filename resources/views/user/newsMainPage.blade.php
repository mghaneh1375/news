@extends('user.layout.newsLayout')


@section('head')
    <?php
    $title = 'اخبار درنا | آخرین و مهمترین اخبار گردشگری، صنایع دستی و میراث فرهنگی ایران و جهان';
    $meta = 'آخرین اخبار ، دقیقترین تحلیل و تازه ترین ویدئوها را درباره ی گردشگری و صنایع دستی، میراث فرهنگی، حوادث، اقتصاد و توریسم را در درنا ببینیدو بخوانید';
    $keyword = 'اخبار , اخبار درنا , اخبار گردشگری, ویدئوهای گردشگری, اخبار صنایع دستی, اخبار میراث فرهنگی';
    ?>


    <title>
        {{ $title }}</title>

    <meta property="og:title" content="{{ $title }}" />
    <meta property="title" content="{{ $title }}" />
    <meta name="twitter:title" content="{{ $title }}" />
    <meta name="twitter:card" content="{{ $meta }}" />
    <meta name="description" content="{{ $meta }}" />
    <meta name="twitter:description" content="{{ $meta }}" />
    <meta property="og:description" content="{{ $meta }}" />
    <meta name="keywords" content="{{ $keyword }}">
    <meta property="og:url" content="{{ Request::url() }}" />

    <meta property="og:image" content="{{ URL::asset('images/mainPics/noPicSite.jpg') }}" />
    <meta property="og:image:secure_url" content="{{ URL::asset('images/mainPics/noPicSite.jpg') }}" />
    <meta name="twitter:image" content="{{ URL::asset('images/mainPics/noPicSite.jpg') }}" />
    <meta property="og:image:width" content="550" />
    <meta property="og:image:height" content="367" />

    <meta property="publisher" content="{{ $title }}" />
    {{--    <meta property="dc.publisher" content="{{$title}}"/> --}}
    <style>
        .container {
            /* padding-right: unset !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    padding-left: unset !important; */
            margin-right: unset !important;
            margin-left: unset !important;
            width: 99% !important;
        }

        .MostVisitedToday {
            color: white !important;
            background-color: #232323;
            padding: 6px 5px;
            margin-bottom: 2px
        }

        .selectionNews {
            background-color: #232323;
            padding: 6px 8px;
            color: white
        }

        .bold {
            font-weight: 900
        }

        .colorBlack {
            color: black
        }

        .content:hover {
            color: #ffad14 !important;
        }

        .content .title:hover {
            color: #ffad14 !important;
        }

        .lastNewsCard .title {
            font-size: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }



        ul {
            list-style-type: none;
            padding: 0px;
            margin: 0px
        }




        .mostViewDay li:hover {
            background-color: #d5d5d5;
        }

        .mostViewDay li:hover h6 {
            color: #03328a !important;
        }
    </style>
@endsection


@section('body')
    <div class="row topSectionMainPageNews">
        <div class="col-md-3 col-xs-12 heightUnset  pdR0 leftNavOne">
            <div class="flexDirColumn" style="height:85%;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);">
                <div class="mostViewHeader ">
                    <div style="font-size: 18px !important">{{ __('main.suggestedVideos') }}</div>
                </div>
                <div class="RecommendedVideo">

                </div>

            </div>
            <div style="height: 200px ;width: 100%;margin-top: 10px">
                <img src="{{ URL::asset('images/shareBoxImg/tabligh3.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: بهمم;border: 1px solid gray;">
            </div>
            <div style="margin-top: 10px">
                <div class="MostVisitedToday">
                    <div style="font-size: 18px !important">{{ __('main.MostVisitedToday') }} </div>
                </div>
                <ul class="mostViewDay">
                    @foreach ($mostViewNews as $item)
                        <li class="Point alignItemCen" style="border-left: solid 3px #6D0606;margin-top: 5px;">

                            <a class="pdl10" style="color: black !important" href="{{ $item->url }}">
                                <h6 class="title" style="margin:5px 0 0 0">{{ $item->title }}</h6>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div style="height: 200px ;width: 100%;margin-top: 10px">
                <img src="{{ URL::asset('images/shareBoxImg/tabligh3.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: fill;border: 1px solid gray;">
            </div>
            {{-- <div style="height: 200px ;width: 100%;">
                <img src="{{ URL::asset('images/shareBoxImg/tabligh2.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: cover;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);border: 1px solid gray;">
            </div> --}}

        </div>
        <div class="col-md-3 col-xs-12 centerNav">


            @foreach ($lastNews2 as $item)
                <div class="flexDirColumn newNews" style="height:225px;position: relative;padding-bottom: 5px">
                    <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" class="lazyload resizeImgClass"
                        style="width: 100% !important;height: auto !important">
                    <a href="{{ $item->url }}" class="content">
                        <div style="display: flex">
                            <div style="background-color: #6D0606 ;width: 18px;">
                            </div>
                            <div
                                style="background-color: #6D0606;margin-left: 2px;width: fit-content;padding: 0px 10px;color: white ">
                                {{ $item->category }}
                            </div>
                        </div>
                        <p class="title">{{ $item->title }}</p>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="col-md-6 col-xs-12 mainSwiper">
            <div id="mainSlider" class="swiper-container mainPageSlider">
                <div class="swiper-wrapper">
                    @foreach ($sliderNews as $item)
                        <div class="swiper-slide">
                            <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                class="lazyload resizeImgClass" onload="fitThisImg(this)">

                            <a href="{{ $item->url }}" class="content">
                                <div style="display: flex">
                                    <div style="background-color: #6D0606 ;width: 18px;">
                                    </div>
                                    <div
                                        style="background-color: #6D0606;margin-left: 2px;width: fit-content;padding: 0px 10px;color: white ">
                                        {{ $item->category }}
                                    </div>
                                </div>
                                <h3 class="title">{{ $item->title }}</h3>
                            </a>‍
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
        <div class="col-md-9 col-xs-12  lastNews">
            <div class="mostViewHeader  bgColorRed">
                <div style="font-size: 18px !important">{{ __('main.LatestNews') }}</div>
            </div>
            <div class=" sideCardSec pd10 bgColorGray" style="padding-top: 0px !important">

                @foreach ($lastNews as $item)
                    <div class="sideNewsCard lastNewsCard ">
                        <div class="picSec fullyCenterContent newNews">
                            <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                class="lazyload resizeImgClass" onload="fitThisImg(this)">
                            <a href="{{ $item->url }}"class="content {{ $item->video != null ? 'playIcon' : '' }}"
                                style="bottom:0px !important">
                                <div style="display: flex;padding-bottom: 5px;">
                                    <div style="background-color: #6D0606 ;width: 18px;">
                                    </div>
                                    <div
                                        style="background-color: #6D0606;margin-left: 2px;width: fit-content;padding: 0px 10px;color: white ">
                                        {{ $item->category }}
                                    </div>
                                </div>
                                <p class="title">{{ $item->title }}</p>
                            </a>
                        </div>

                        <div class="bgColorWhite" style="margin-left: 5px">
                            <a href="{{ $item->url }}" class="colorGray">
                                <div class="title">{{ $item->meta }}</div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        <div id="mainNews" class="col-md-9 col-xs-12  newsCat">
        </div>
        <div id="lineAds" class="col-md-12 col-xs-12">
            <img src="{{ URL::asset('images/shareBoxImg/lineAds.webp') }}"
                alt=""style="width: 100%;height: 100%;object-fit: fill;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);border: 1px solid gray;">
        </div>
        <div class="col-md-12 col-xs-12 topnews" style="height: unset;padding-left: 0px;">

            <div class="col-md-6 col-xs-12  lastNews">
                <div style="margin-top: 10px">
                    <div class="mostViewHeader">
                        <div style="font-size: 14px !important">{{ __('main.LatestSelections') }}</div>
                    </div>
                    <ul class="lastSpecialNew">
                        @foreach ($topNews as $item)
                            <li class="Point alignItemCen" style="border-left: solid 3px #6D0606;margin-top: 5px;">
                                <div class="pdl10 d-flex" style=" box-shadow: 0px 3px 6px #00000029; padding-top: 5px">
                                    <div class="topNewsImg"><img style="width: 100%;height: 100%;object-fit: fill;"
                                            src="{{ $item['pic'] }}" alt="">
                                    </div>
                                    <div style="padding-left: 10px">
                                        <a href="{{ $item['url'] }}">
                                            <div style="display: flex">

                                                <div style="background-color: #6D0606 ;width: 18px;">
                                                </div>
                                                <div
                                                    style="background-color: #6D0606;margin-left: 2px;width: fit-content;padding: 0px 10px;color: white ">
                                                    {{ $item['category'] }}
                                                </div>
                                            </div>
                                            <h6 class="title bold" style="margin:5px 0 0 0;color: #232323;">
                                                {{ $item['title'] }}</h6>
                                            <h6 class="title" style="margin:5px 0 0 0;color: #676767">
                                                {{ $item['meta'] }}
                                            </h6>
                                        </a>
                                    </div>

                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-xs-12  lastNews">
                <div id="sideNews">
                </div>
            </div>
        </div>
        @if (count($lastVideos) > 0)
            <div class="col-md-6 col-xs-12  videoBox">
                <div class="mostViewHeader">
                    <div style="font-size: 14px !important">{{ __('main.LatestVideos') }}</div>
                </div>
                <div class="d-flex " style="justify-content: space-between;margin-top: 5px">
                    @foreach ($lastVideos as $item)
                        <div class="sideNewsCard  ">

                            <a href="{{ $item->url }}" style="position: relative;"
                                class="picSec fullyCenterContent newNews ">
                                <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                    class="lazyload resizeImgClass" onload="fitThisImg(this)">
                            </a>

                            <div style="margin-left: 5px;background-color: #E7E0D8">
                                <a href="{{ $item->url }}" class="colorGray">
                                    <div class="title">{{ $item->title }}</div>
                                    <div class="meta">{{ $item->meta }}</div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>


@endsection


@section('script')
    <script>
        var news = {!! $allCategories !!}
        var cat = [];
        var mainNewscat = [];
        var sideNewscat = [];
        $(document).ready(function() {
            var html = '';
            var text = '';
            for (let i = 0; i < 3; i++) {
                if (news[i].news.length > 0) {
                    mainNewscat.push(news[i]);
                    cat.push(news[i].news.length);
                }
            }
            for (let i = 3; i < news.length; i++) {
                if (news[i].news.length > 0) {
                    sideNewscat.push(news[i]);
                }
            }

            cat = cat.sort();
            for (let i = 0; i < mainNewscat.length; i++) {
                if (cat.length == 3) {
                    html += '<div style="width:33%;margin-right: 5px;">';
                } else if (cat.length == 2) {
                    html += '<div style="width:48%;margin-right: 5px;">';
                } else {
                    html += '<div style="width:100%">';
                }
                html += '<div class="mostViewHeader">';
                html += '' + mainNewscat[i].nameEn + '';
                html += '</div>';
                html += ' <ul class="lastSpecialNew">';
                for (let j = 0; j < cat[0]; j++) {
                    html +=
                        '<li class="Point alignItemCen" style="border-left: solid 3px #6D0606;margin-top: 5px;">';
                    html += '<a class="pdl10" style="color: black !important" href="' + mainNewscat[i].news[j].url +
                        '">';
                    html += '<h6 class="title" style="margin:5px 0 0 0">' + mainNewscat[i].news[j].title + '</h6>';
                    html += ' </a>';
                    html += ' </li>';
                }
                html += '</ul>';
                html += '</div>';
            }
            $('#mainNews').empty().append(html);
            for (let i = 0; i < sideNewscat.length; i++) {
                text += '<div class="pdB10">';
                text += '<div class="mostViewHeader">';
                text += '' + sideNewscat[i].nameEn + '';
                text += '</div>';
                text += ' <ul class="lastSpecialNew">';
                for (let j = 0; j < sideNewscat[i].news.length; j++) {
                    text +=
                        '<li class="Point alignItemCen" style="border-left: solid 3px #6D0606;margin-top: 5px;">';
                    text += '<a class="pdl10" style="color: black !important" href="' + sideNewscat[i].news[j].url +
                        '">';
                    text += '<h6 class="title" style="margin:5px 0 0 0">' + sideNewscat[i].news[j].title + '</h6>';
                    text += ' </a>';
                    text += ' </li>';
                }
                text += '</ul>';
                text += '</div>';
            }
            $('#sideNews').empty().append(text);
        });

        var swiper = new Swiper('#mainSlider', {
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 50000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-prev',
                prevEl: '.swiper-button-next',
            },
        });
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 3,
            // centeredSlides: true,
            spaceBetween: 30,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-prev',
                prevEl: '.swiper-button-next',
            },
        });
        var horizontalSwiper = new Swiper('#horizSlider', {
            slidesPerView: 'auto',
            centeredSlides: true,
            spaceBetween: 10,
            loop: true,
            autoplay: {
                delay: 50000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-prev',
                prevEl: '.swiper-button-next',
            },
        })

        $.ajax({
            type: 'GET',
            url: `{{ route('site.news.list.getElements', ['lang' => \App::getLocale()]) }}?kind=category&content=ویدیو برگزیده&take=1&page=0`,
            headers: {
                accept: 'application/json'
            },
            success: function(myRes) {
                var html = '';
                if (myRes.status == 'ok') {

                    for (let i = 0; i < myRes.result.length; i++) {
                        html += '<div class="sideNewsCard" style="width: 100% !important;height:600px;">';
                        html += '<a href="' + myRes.result[i].url +
                            '" style="height: 100%" class="picSec fullyCenterContent {{ ' + myRes.result[i].video +' != null ? 'playIcon' : '' }}">';
                        html += '<img data-src="' + myRes.result[i].pic + '" alt="' + myRes.result[i]
                            .keyword +
                            '" loading="lazy"class="lazyload resizeImgClass" onload="fitThisImg(this)"style="height: 100%;object-fit: fill;">';
                        html += '<a href="' + myRes.result[i].url + '" class="content">';
                        html += '<h3 class="title">' + myRes.result[i].title + '</h3>';
                        html += '<p class="summery">' + myRes.result[i].meta + '</p>';
                        html += '</a>';
                        html += '</a>';
                        html += '</div>';
                    }


                }
                $('#SelectionsVideos').empty().append(html);
            },
            error: err => {
                // console.log(err);
                getsList();
            }
        })
        $.ajax({
            type: 'GET',
            url: `{{ route('site.news.list.getElements', ['lang' => \App::getLocale()]) }}?kind=category&content=ویدیو پیشنهادی&take=1&page=0`,
            headers: {
                accept: 'application/json'
            },
            success: function(myRes) {
                var html = '';
                if (myRes.status == 'ok') {
                    for (let i = 0; i < myRes.result.length; i++) {
                        html +=
                            '<div class="sideNewsCard" style="width: 100% !important;height:100%;margin-top: 4px;">';
                        html +=
                            ' <a href="' + myRes.result[i].url +
                            '"class="picSec fullyCenterContent {{ '+ myRes.result[i].video+' != null ? 'playIcon' : '' }}"style="height: 150px;">';
                        html += '';
                        html +=
                            '<img data-src="' + myRes.result[i].pic + '" alt="' + myRes.result[i].keyword +
                            '" loading="lazy"class="lazyload resizeImgClass" onload="fitThisImg(this)">';
                        html += '</a>';
                        html += '<a href="' + myRes.result[i].url + '" class="colorBlack">';
                        html += '<h3 class="title" style="color: #232323">' + myRes.result[i].title + '</h3>';
                        html += '<p class="summery"style="color: #676767;">' + myRes.result[i].meta + '</p>';

                        html += '</a>';
                        html += '</div>';
                    }
                }
                $('.RecommendedVideo').empty().append(html);
            },
            error: err => {
                // console.log(err);
                getsList();
            }
        })
        // $.ajax({
        //     type: 'GET',
        //     url: 'https://dornanews.com/api/en/slugList',
        //     headers: {
        //         accept: 'application/json'
        //     },
        //     success: function(myRes) {
        //         var html = '';
        //         if (myRes.status == 'ok') {
        //             console.log(myRes);

        //         }
        //     },
        //     error: err => {
        //         // console.log(err);
        //         getsList();
        //     }
        // })

        function getsList() {
            inTake = false;
            closeLoading();
        }
    </script>
@endsection
