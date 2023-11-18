@extends('user.layout.newsLayout')


@section('head')
    <?php
    $title = 'اخبار کوچیتا | آخرین و مهمترین اخبار گردشگری، صنایع دستی و میراث فرهنگی ایران و جهان';
    $meta = 'آخرین اخبار ، دقیقترین تحلیل و تازه ترین ویدئوها را درباره ی گردشگری و صنایع دستی، میراث فرهنگی، حوادث، اقتصاد و توریسم را در کوچیتا ببینیدو بخوانید';
    $keyword = 'اخبار , اخبار کوچیتا , اخبار گردشگری, ویدئوهای گردشگری, اخبار صنایع دستی, اخبار میراث فرهنگی';
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
            width: 95% !important;
        }

        .mostViewHeader {
            color: white !important;
            background-color: #232323;
            padding: 6px 20px;
            border-right: 4px solid #6d0606;
        }

        .selectionNews {
            background-color: #6d0606;
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
            font-size: 15px;
            padding-right: 10px;
            padding-top: 10px
        }

        .lastSpecialNew li:nth-child(2n+1) {
            background-color: black;
        }

        .lastSpecialNew li:nth-child(2n) {
            background-color: #232323;
        }

        ul {
            list-style-type: none;
            padding: 0px;
            margin: 0px
        }

        .lastSpecialNew li .keyNumber {
            /* position: absolute; */
            background-color: #6d0606;
            color: #fff;
            border-radius: 50%;
            font-size: 0.8em;
            width: 29px;
            height: 29px;
            text-align: center;
            padding-top: 5px;
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
        <div class="col-md-3 col-xs-12 heightUnset pdL0 pdR0 leftNavOne">
            <div style="height: 200px ;width: 100%;">
                <img src="{{ URL::asset('images/shareBoxImg/tabligh2.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: contain;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);border: 1px solid gray;">
            </div>
            <div style="margin-top: 10px">
                <div class="mostViewHeader">
                    <div style="font-size: 18px !important">{{ __('main.MostVisitedToday') }} </div>
                </div>
                <ul class="mostViewDay">
                    @foreach ($mostViewNews as $item)
                        <li class="Point alignItemCen">
                            <div class="leftArrowIcon "></div>
                            <a style="color: black !important" href="{{ $item->url }}">
                                <h6 class="title " style="margin-top: 20px">{{ $item->title }}</h6>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 centerNav">
            <div
                style="height: 15%;margin-bottom: 10px;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);border: 1px solid gray;">
                <img src="{{ URL::asset('images/shareBoxImg/takhfif.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: cover;">
            </div>
            <div class="flexDirColumn" style="height:85%;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);">
                <div class="mostViewHeader ">
                    <div style="font-size: 18px !important">{{ __('main.suggestedVideos') }}</div>
                </div>
                <div class="RecommendedVideo">

                </div>

            </div>
        </div>
        <div class="col-md-6 col-xs-12 mainSwiper">
            <div id="mainSlider" class="swiper-container mainPageSlider">
                <div class="swiper-wrapper">
                    @foreach ($sliderNews as $item)
                        <div class="swiper-slide">
                            <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                class="lazyload resizeImgClass" onload="fitThisImg(this)">
                            <a href="{{ $item->url }}" class="content">
                                <h3 class="title">{{ $item->title }}</h3>
                                <p class="summery">{{ $item->meta }}</p>
                            </a>‍
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        @if (count($lastVideos) > 0)
            <div class="row inOneRows swiper mySwiper swiper-container videoConv">
                <div class="videosHeader">
                    <div class="bgColorRed colorWhite alignItemCen pd5 mg17">{{ __('main.MostViewed') }}</div>
                    <div class="title" style="margin-top: 10px;margin-right: 10px">
                        {{ __('main.VideoCoversation') }}</div>
                </div>

                <div class="body swiper-wrapper heightUnset justifyContentUnset" style="position: relative;">
                    @foreach ($lastVideos as $item)
                        <div class="cardDownTitle swiper-slide">
                            <a href="{{ $item->url }}" class="picSec fullyCenterContent  borderRadiusUnset">
                                <img src="{{ $item->pic }}" alt="{{ $item->keyword }}" class="resizeImgClass"
                                    onload="fitThisImg(this)">
                            </a>
                            <a href="{{ $item->url }}" class="content">
                                <p> {{ $item->title }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        @endif
        <div class="col-md-3 col-xs-12 pdL0 pdR0 lastSelectedNews">
            <div style="height: 200px ;width: 100%;">
                <img src="{{ URL::asset('images/shareBoxImg/tabligh3.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: contain;border: 1px solid gray;">
            </div>
            <div style="margin-top: 10px">
                <div class="selectionNews">
                    <div style="font-size: 16px !important">{{ __('main.LatestSelections') }}</div>
                </div>
                <ul class="lastSpecialNew">
                    @foreach ($topNews as $item)
                        <li class="pd5">
                            <div class="alignItemCen">
                                <div class="keyNumber mrLeft5">{{ $loop->index + 1 }}</div>
                                <a class="col-md-9 padUnset" style="color: white !important" href="{{ $item->url }}">
                                    <h6 class="title ">{{ $item->title }}</h6>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-9 col-xs-12  lastNews">
            <div class="mostViewHeader  bgColorRed">
                <div style="font-size: 18px !important">{{ __('main.LatestNews') }}</div>
            </div>
            <div class=" sideCardSec pd10 bgColorBlack">
                @foreach ($lastNews as $item)
                    <div class="sideNewsCard lastNewsCard ">

                        <a href="{{ $item->url }}"
                            class="picSec fullyCenterContent {{ $item->video != null ? 'playIcon' : '' }}">
                            <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                class="lazyload resizeImgClass" onload="fitThisImg(this)">
                        </a>
                        <div class="bgColorWhite height">
                            <a href="{{ $item->url }}" class="colorBlack ">
                                <div class="title">{{ $item->title }}</div>
                        </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @if (count($lastVideos) > 0)
            <div class="col-md-12 col-xs-12  secendVideoBox">
                <div class="mostViewHeader  bgColorBlack  w100">
                    <div style="font-size: 18px !important">{{ __('main.video') }}</div>
                </div>
                <div id="SelectionsVideos" class="mainPageSlider"
                    style="border: 4px solid black; border-radius: 0px !important">
                </div>
                <div class="row inOneRows swiper mySwiper swiper-container"
                    style="background-color: black;float:right !important;height: 250px;overflow: hidden;">
                    <div class="videosHeader">
                        <div class="bgColorRed colorWhite alignItemCen pd5 mg17">{{ __('main.MostViewed') }}</div>
                        <div class="title" style="color: white;margin-top: 10px;margin-right: 10px">
                            {{ __('main.LatestVideos') }}</div>
                    </div>
                    <div class="body swiper-wrapper heightUnset justifyContentUnset" style="position: relative;">
                        @foreach ($lastVideos as $item)
                            <div class="cardDownTitle swiper-slide">
                                <a href="{{ $item->url }}" class="picSec fullyCenterContent  borderRadiusUnset">
                                    <img src="{{ $item->pic }}" alt="{{ $item->keyword }}" class="resizeImgClass"
                                        onload="fitThisImg(this)">
                                </a>
                                <a href="{{ $item->url }}" class="content">
                                    <div>
                                        {{ $item->title }}</div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        @endif


        @foreach ($allCategories as $category)
            @if ($loop->index == 0)
                @if (count($category->news) > 0)
                    <div class="oneBig4SmallRows bgColorRed" style="display: inline-block;">
                        <a href="{{ route('site.news.list', ['kind' => 'category', 'content' => $category->name, 'lang' => \App::getLocale()]) }}"
                            class="col-md-12 title colorWhite"> {{ __('main.TourismNewsList') }} </a>

                        <div class="col-md-4 oneBigSec floatRight">
                            <a href="{{ $category->news[0]->url }}" class="colCard bgColorGray">
                                <div
                                    class="picSec fullyCenterContent {{ $category->news[0]->video != null ? 'playIcon' : '' }}">
                                    <img src="{{ $category->news[0]->pic }}" alt="{{ $category->news[0]->keyword }}"
                                        class="resizeImgClass" onload="fitThisImg(this)">
                                </div>
                                <div class="content">
                                    <h3 class="title">{{ $category->news[0]->title }}</h3>
                                    <p class="summery">{{ $category->news[0]->meta }}</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8 sideDown">
                            @for ($i = 1; $i < 7 && $i < count($category->news); $i++)
                                <a href="{{ $category->news[$i]->url }}" class="rowCard">
                                    <div
                                        class="picSec fullyCenterContent {{ $category->news[$i]->video != null ? 'playIcon' : '' }}">
                                        <img src="{{ $category->news[$i]->pic }}"
                                            alt="{{ $category->news[$i]->keyword }}" class="resizeImgClass"
                                            onload="fitThisImg(this)">
                                    </div>
                                    <h3 class="content">{{ $category->news[$i]->title }}</h3>
                                </a>
                            @endfor
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>


    {{-- @if (count($topNews) > 1)
        <div class="row inOneRows">
            <div class="title">اخبار برگزیده</div>
            <div class="body">
                <div id="horizSlider" class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach ($topNews as $item)
                            <div class="swiper-slide cardDownTitle">
                                <a href="{{ $item->url }}"
                                    class="picSec fullyCenterContent {{ $item->video != null ? 'playIcon' : '' }}">
                                    <img src="{{ $item->pic }}" alt="{{ $item->keyword }}" class="resizeImgClass"
                                        onload="fitThisImg(this)">
                                </a>
                                <a href="{{ $item->url }}" class="content">{{ $item->title }}</a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    @endif --}}





@endsection


@section('script')
    <script>
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
            url: `{{ route('site.news.list.getElements', ['lang' => \App::getLocale()]) }}?kind=category&content=ویدیو پیشنهادی&take=1&page=0`,
            headers: {
                accept: 'application/json'
            },
            success: function(myRes) {
                var html = '';
                if (myRes.status == 'ok') {
                    console.log(myRes);
                    for (let i = 0; i < myRes.result.length; i++) {
                        html += '<div class="sideNewsCard" style="width: 100% !important;height:100%;">';
                        html +=
                            ' <a href="' + myRes.result[i].url +
                            '"class="picSec fullyCenterContent {{ '+ myRes.result[i].video+' != null ? 'playIcon' : '' }}"style="height: 150px;">';
                        html += '';
                        html +=
                            '<img data-src="' + myRes.result[i].pic + '" alt="' + myRes.result[i].keyword +
                            '" loading="lazy"class="lazyload resizeImgClass" onload="fitThisImg(this)">';
                        html += '</a>';
                        html += '<a href="' + myRes.result[i].url + '" class="colorWhite">';
                        html += '<h3 class="title">' + myRes.result[i].title + '</h3>';
                        html += '<p class="summery">' + myRes.result[i].meta + '</p>';

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
        $.ajax({
            type: 'GET',
            url: `{{ route('site.news.list.getElements', ['lang' => \App::getLocale()]) }}?kind=category&content=ویدیو برگزیده&take=1&page=0`,
            headers: {
                accept: 'application/json'
            },
            success: function(myRes) {
                var html = '';
                if (myRes.status == 'ok') {
                    console.log(myRes);
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

        function getsList() {
            inTake = false;
            closeLoading();
        }
    </script>
@endsection
