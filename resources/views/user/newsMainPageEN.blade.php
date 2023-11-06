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
            border-right: 2px solid #6d0606;
        }

        .selectionNews {
            background-color: #6d0606;
            padding: 6px 8px;
            color: black
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



        .lastNewsCard {
            width: 32% !important;
            border-bottom: 2px solid #6d0606;
            height: 300px !important;
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
        <div class="col-md-3 heightUnset pdL0 pdR0">
            <div style="height: 200px ;width: 100%;">
                <img src="{{ URL::asset('images/shareBoxImg/tabligh2.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: cover;">
            </div>
            <div style="margin-top: 10px">
                <div class="mostViewHeader">
                    <div style="font-size: 18px !important">پربازدیدترین‌های روز </div>
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
        <div class="col-md-3 " style="display: flex;flex-direction: column;padding-right: 30px;">
            <div style="height: 20%;margin-bottom: 10px">
                <img src="{{ URL::asset('images/shareBoxImg/takhfif.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: cover;">
            </div>
            <div class="flexDirColumn">
                <div class="mostViewHeader ">
                    <div style="font-size: 18px !important">ویدئو پیشنهادی</div>
                </div>
                <div style="padding: 10px 10px;background-color: #ebebeb;">
                    @foreach ($lastVideos as $item)
                        @if ($loop->index == 0)
                            <div class="sideNewsCard" style="width: 100% !important;height:290px;">
                                <a href="{{ $item->url }}"
                                    class="picSec fullyCenterContent {{ $item->video != null ? 'playIcon' : '' }}"
                                    style="height: 150px;">
                                    <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                        class="lazyload resizeImgClass" onload="fitThisImg(this)">
                                </a>
                                <a href="{{ $item->url }}" class="colorBlack">
                                    <h5 class="title  ">{{ $item->title }}</h5>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
        <div class="col-md-6" style="padding-left: 0px !important;">
            <div id="mainSlider" class="swiper-container mainPageSlider">
                <div class="swiper-wrapper">
                    @foreach ($sliderNews as $item)
                        <div class="swiper-slide">
                            <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                class="lazyload resizeImgClass" onload="fitThisImg(this)">
                            <a href="{{ $item->url }}" class="content">
                                <h3 class="title">{{ $item->title }}</h3>
                                <p class="summery">{{ $item->meta }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        @if (count($lastVideos) > 0)
            <div class="row inOneRows swiper mySwiper swiper-container"
                style="background-color: black;float:right !important;margin:  20px 15px 0px 0px;width: 73%;height: 250px;overflow: hidden;">
                <div class="title" style="color: white;margin-top: 10px;margin-right: 10px">گفت‌وگوی ویدئویی</div>
                <div class="body swiper-wrapper heightUnset justifyContentUnset" style="position: relative;">
                    @foreach ($lastVideos as $item)
                        <div class="cardDownTitle d-flex flexDirRow swiper-slide alignItemStart">
                            <a href="{{ $item->url }}" class="picSec fullyCenterContent  borderRadiusUnset">
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
        @endif
        <div class="col-md-3 pdL0 pdR0">
            <div style="height: 200px ;width: 100%;">
                <img src="{{ URL::asset('images/shareBoxImg/tabligh2.png') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: cover;">
            </div>
            <div style="margin-top: 10px">
                <div class="selectionNews">
                    <div style="font-size: 16px !important">آخرین برگزیده ها</div>
                </div>
                <ul class="lastSpecialNew">
                    @foreach ($mostViewNews as $item)
                        <li class="pd5">
                            <div style="display: flex;align-items: center;">
                                <div class="keyNumber mrLeft5">{{ $loop->index + 1 }}</div>
                                <a class="col-md-9 padUnset" style="color: white !important" href="{{ $item->url }}">
                                    <h6 class="title " style="margin-top: 20px">{{ $item->title }}</h6>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-9 floatRight pdr15 pdl10 flexDirColumn mgt10 heightUnset">
            <div class="mostViewHeader ">
                <div style="font-size: 18px !important">آخرین اخبار</div>
            </div>
            <div class=" sideCardSec pd10">
                @foreach ($lastNews as $item)
                    <div class="sideNewsCard lastNewsCard boxShadow ">

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
            <div class="col-md-12 floatRight  flexDirColumn mgt10 heightUnset">
                <div class="mostViewHeader  mgR10 w100">
                    <div style="font-size: 18px !important">ویدئو</div>
                </div>
                <div class="mainPageSlider"
                    style="margin: 10px 10px 0px 0px;border: 4px solid black; border-radius: 0px !important">
                    @foreach ($lastVideos as $item)
                        @if ($loop->index == 0)
                            <div class="sideNewsCard" style="width: 100% !important;height:600px;">
                                <a href="{{ $item->url }}" style="height: 100%"
                                    class="picSec fullyCenterContent {{ $item->video != null ? 'playIcon' : '' }}">
                                    <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                        class="lazyload resizeImgClass" onload="fitThisImg(this)"
                                        style="height: 100%;object-fit: fill;">
                                    <a href="{{ $item->url }}" class="content">
                                        <h3 class="title">{{ $item->title }}</h3>
                                        <p class="summery">{{ $item->meta }}</p>
                                    </a>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="row inOneRows swiper mySwiper swiper-container"
                    style="background-color: black;float:right !important;margin:  0px 10px 0px 0px;height: 250px;overflow: hidden;">
                    <div class="d-flex">
                        <div class="bgColorRed colorWhite alignItemCen pd5 mg17"> پربازدید</div>
                        <div class="title" style="color: white;margin-top: 10px;margin-right: 10px">آخرین ویدئوها</div>
                    </div>
                    <div class="body swiper-wrapper heightUnset justifyContentUnset" style="position: relative;">
                        @foreach ($lastVideos as $item)
                            <div class="cardDownTitle d-flex flexDirRow swiper-slide alignItemStart">
                                <a href="{{ $item->url }}" class="picSec fullyCenterContent  borderRadiusUnset">
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
        @endif
    </div>


    @if (count($topNews) > 1)
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
    @endif



    <?php
    $colors = ['green', 'yellow', 'lightGreen', 'red', 'blue'];
    $takenColor = 0;
    ?>

    @foreach ($allCategories as $category)
        @if ($loop->index == 0)
            @if (count($category->news) > 0)
                <div data-index="{{ $takenColor }}" class="row oneBig4SmallRows bgColorRed">
                    <a href="{{ route('site.news.list', ['kind' => 'category', 'content' => $category->name]) }}"
                        class="col-md-12 title colorWhite"> {{ $category->name }} </a>

                    <div class="col-md-4 oneBigSec floatRight">
                        <a href="{{ $category->news[0]->url }}" class="colCard bgColorBlack">
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
                                    <img src="{{ $category->news[$i]->pic }}" alt="{{ $category->news[$i]->keyword }}"
                                        class="resizeImgClass" onload="fitThisImg(this)">
                                </div>
                                <h4 class="content">{{ $category->news[$i]->title }}</h4>
                            </a>
                        @endfor
                    </div>
                </div>

                <?php
                $takenColor++;
                ?>
            @endif
        @endif
    @endforeach

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
    </script>
@endsection
