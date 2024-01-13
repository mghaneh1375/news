@extends('user.layout.newsLayout')


@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta name="theme-color" content="#30b4a6" />

    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#d2ac68">
    <meta name="msapplication-TileColor" content=" #e7e0d8">
    <meta name="theme-color" content=" #e7e0d8">

    <link rel="canonical" href="{{ Request::url() }}" />
    <meta name="author" content="{{ $news->username }}" />

    <meta content="article" property="og:type" />
    <meta property="og:title" content="{{ $news->seoTitle }}" />
    <meta property="title" content="{{ $news->seoTitle }}" />
    <meta name="twitter:title" content="{{ $news->seoTitle }}" />
    <meta name="twitter:card" content="{{ $news->meta }}" />
    <meta name="description" content="{{ $news->meta }}" />
    <meta name="twitter:description" content="{{ $news->meta }}" />
    <meta property="og:description" content="{{ $news->meta }}" />
    @if (isset($news->category) && $news->category != null)
        @if (\App::getLocale() == 'en')
            <meta property="article:section" content="{{ $news->category->nameEn }}" />
        @else
            <meta property="article:section" content="{{ $news->category->name }}" />
        @endif
    @endif

    <meta name="keywords" content="{{ $news->keyword }}">
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ $news->pic }}" />
    <meta property="og:image:secure_url" content="{{ $news->pic }}" />
    <meta name="twitter:image" content="{{ $news->pic }}" />
    <meta property="og:image:width" content="550" />
    <meta property="og:image:height" content="367" />

    @foreach ($news->tags as $item)
        <meta property="article:tag" content="{{ $item }}" />
    @endforeach

    <title>{{ isset($news->seoTitle) ? $news->seoTitle : $news->title }} </title>
    {{-- 
    <meta name="msapplication-TileColor" content="black">
    <meta property="og:title" content="TITLETEXT" />
    <meta property="og:description" content="descriptionTEXT" />
    <meta property="og:image:alt" content="IMAGEALTTEXT" />
    <meta property="og:url" ontent="URLTEXT" />
    <meta property="og:site_name" content="SITENAMETEXT" />
    <meta property="article:published_time" content="publishtimetext" />
    <meta property="article:modified_time" content="updatetimetext" />
    <meta property="og:image" content="IMAGEURLTEXT" />
    <meta property="og:image:secure_url" content="IMAGEURLTEXT" />
    <meta property="og:image:width" content="imagewidthtext" />
    <meta property="og:image:height" content="imageheighttext" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:type" content="article" /> --}}
    <style>
        .eddsSec {}

        /* .eddsSec.fixedL {
                                                                                                                                                                                                                                                                                                                                position: fixed;
                                                                                                                                                                                                                                                                                                                                bottom: 0px;
                                                                                                                                                                                                                                                                                                                            } */

        .newsVideo {}

        .sideSec .tagSection {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 10px;
            row-gap: 5px;
        }

        ul {
            list-style-type: none;
            padding: 0px;
            margin: 0px
        }

        .row {
            margin: 0px !important;
        }

        .ck-content .image {
            display: table;
            clear: both;
            text-align: center;
            margin: 1em auto;
        }

        .ck-content .image-style-align-left {
            float: left;
            margin-right: 10px;
        }

        .ck-content .image.image_resized {
            max-width: 100%;
            display: block;
            box-sizing: border-box;
        }

        .ck-content .image-style-align-right {
            float: right;
            margin-left: 10px;
        }

        blockquote {
            border-left: 5px solid #6D0606 !important;
        }

        table {
            border-spacing: 2px;
            border-collapse: separate;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        thead th {
            text-align: center;
            background-color: #000 !important;
            color: #fff;
            padding: 16px 0px;
        }

        thead th td {

            text-align: left;
            padding: 16px;

        }

        tbody tr th {

            text-align: center;
            background-color: #000 !important;
            color: #fff;
            padding: 16px 0px;
        }

        tbody td {
            text-align: left;
            padding: 16px;
        }

        tbody tr:nth-child(odd) {
            background-color: rgb(224, 224, 224);
        }
    </style>

    @if (!$news->rtl)
        <style>
            .newsContainer>.body *:not(.title) {
                direction: ltr !important;
            }

            .newsContainer>.title>h1 {
                direction: ltr !important;
            }
        </style>
    @endif
@endsection


@section('body')
    <div class="row" style="margin-top: 20px;margin-bottom: 40px">
        <div id="pcSideAdSection" class="col-md-3 hideOnPhone">
            <div>
                <div class="row sideSec" style="display: flex">
                    <div> {{ $news->showTime }}</div>
                    <div id="bottomOfText "> , By: {{ $news->author }}</div>

                </div>
                <div class="row sideSec">
                    <div class="title">{{ __('main.share') }}</div>
                    <div class="shareSec">
                        <div class="bu">
                            <a target="_blank" class="link mg-tp-5" id="facebook" href="">
                                <img src="{{ URL::asset('images/shareBoxImg/facebook.png') }}" class="pic">
                                <div class="text">{{ __('main.facebook') }}</div>
                            </a>
                        </div>
                        <div class="bu">
                            <a target="_blank" class="link mg-tp-5" id="twitter" href="">
                                <img src="{{ URL::asset('images/shareBoxImg/twitter.png') }}" class="pic">
                                <div class="text"> {{ __('main.twitter') }}</div>
                            </a>
                        </div>
                        <div class="bu">
                            <a target="_blank" id="whatsApp" class="link mg-tp-5 whatsappLink" href="#">
                                <img src="{{ URL::asset('images/shareBoxImg/whatsapp.png') }}" class="pic">
                                <div class="text">{{ __('main.whatsApp') }}</div>
                            </a>
                        </div>
                        <div class="bu">
                            <a target="_blank" class="link mg-tp-5" id="telegram" href="">
                                <img src="{{ URL::asset('images/shareBoxImg/telegram.png') }}" class="pic">
                                <div class="text">{{ __('main.telegram') }}</div>
                            </a>
                        </div>
                        <div class="bu" id="copy-button" style="cursor: pointer">
                            <div style="display: flex;" target="_blank" class="link mg-tp-5" href="#">
                                <img src="{{ URL::asset('images/shareBoxImg/copyLink.png') }}" class="pic">
                                <div class="text">{{ __('main.copyLink') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lastNews">
                <div style="margin-top: 10px">
                    <div class="mostViewHeader">
                        <div style="font-size: 14px !important">{{ __('main.LatestSelections') }}</div>
                    </div>
                    <ul class="lastSpecialNew">
                        @foreach ($topNews as $item)
                            <li class="Point alignItemCen" style="border-left: solid 3px #6D0606;margin-top: 5px;">
                                <a class="pdl10" style="color: black !important" href="{{ $item['url'] }}">
                                    <h6 class="title" style="margin:5px 0 0 0">{{ $item['title'] }}</h6>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- <div id="dsfjk" class="eddsSec">
                <div class="row sideSec">
                    <div class="title">{{ __('main.similarNews') }}</div>
                    <div class="otherNewsInShowSec">
                        <div id="otherNewsSlider" class="swiper-container otherNewsInShow">
                            <div class="swiper-wrapper">
                                @foreach ($news->otherNews as $item)
                                    <div class="swiper-slide">
                                        <a href="{{ $item->url }}" class="picSec">
                                            <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}"
                                                loading="lazy" class="lazyload resizeImgClass" onload="fitThisImg(this)">
                                        </a>
                                        <a href="{{ $item->url }}" class="content">
                                            <h3 class="title">{{ $item->title }}</h3>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>

            </div> --}}

        </div>

        <div class="col-md-9 newsContainer">

            <div class="newsTitleShow hideOnScreen">
                <h1 style="font-weight: bold">{{ $news->title }}</h1>
            </div>
            <div class="mainPic"
                style="max-width: 100%; width: 100%; max-height: 500px; overflow: hidden; display: flex;position: relative;">
                @if ($news->video == null)
                    <img src="{{ $news->pic }}" alt="{{ $news->keyword }}" onload="fitThisImg(this)"
                        style="width: 100%;object-fit: contain">
                    <div class="imgCaption">{{ $news->meta }}</div>
                @else
                    <video src="{{ $news->video }}" poster="{{ $news->pic }}" class="newsVideo" controls
                        style="width: 100%; max-height: 100%;"></video>
                @endif
            </div>
            <div class="title hideOnPhone">
                <h1 style="font-weight: bold">{{ $news->title }}</h1>
            </div>

            <div class="body">
                <div class="descriptionText ck-content">
                    {!! $news->text !!}
                </div>

                <div class="row sideSec" style="border-bottom: 0;display: none">
                    <div class="title">{{ __('main.tags') }}</div>
                    <div class="tagSection">
                        @foreach ($news->tags as $item)
                            <div class="tag">
                                <a
                                    href="{{ route('site.news.list', ['lang' => \App::getLocale(), 'kind' => 'tag', 'content' => $item]) }}">{{ $item }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>


            </div>
        </div>

        <div class="col-md-2 hideOnScreen" style="display: none">
            <div class="row sideSec">
                <div class="title">اخبار مشابه</div>
                <div class="otherNewsInShowSec">
                    <div id="otherNewsSlider" class="swiper-container otherNewsInShow">
                        <div class="swiper-wrapper">
                            @foreach ($news->otherNews as $item)
                                <div class="swiper-slide">
                                    <a href="{{ $item->url }}" class="picSec">
                                        <img data-src="{{ $item->pic }}" alt="{{ $item->keyword }}" loading="lazy"
                                            class="lazyload resizeImgClass" onload="fitThisImg(this)">
                                    </a>
                                    <a href="{{ $item->url }}" class="content">
                                        <h3 class="title">{{ $item->title }}</h3>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        var swiper = new Swiper('.otherNewsInShow', {
            spaceBetween: 10,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 50000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-prev',
                prevEl: '.swiper-button-next',
            },
        });

        $(window).ready(() => {
            let encodeurlShareBox = encodeURIComponent('{{ Request::url() }}');
            let textShareBox = 'whatsapp://send?text=';
            textShareBox += 'در کوچیتا ببینید:' + ' %0a ' + encodeurlShareBox;
            $('.whatsappLink').attr('href', textShareBox);
            fitSideSizes()
        });

        var startFixing = false;
        var sideIsFixed = false;
        var lastScrollPosition = 0;
        var fixingId = 'dsfjk';

        function fitSideSizes() {
            var width = $(`#${fixingId}`).width();
            var leftOfAd = document.getElementById('dsfjk').getBoundingClientRect().left;

            if (!sideIsFixed) {
                $(`#${fixingId}`).css('left', leftOfAd);
                $(`#${fixingId}`).css('width', width);
            }

            startFixing = true;
        }
        $(window).on('resize', fitSideSizes);

        $(window).on('scroll', function() {

            if (!startFixing)
                return;

            var scrollPosition = $(window).scrollTop();
            var positionOfFooter = document.getElementById('bottomOfText').getBoundingClientRect().top - $(window)
                .height();
            var bottomOfAd = document.getElementById('dsfjk').getBoundingClientRect().top + $(`#${fixingId}`)
                .height() - $(window).height();
            var scrollMovement = scrollPosition - lastScrollPosition > 0 ? 'down' : 'up';

            if (bottomOfAd <= 0 && !sideIsFixed) {
                sideIsFixed = true;
                $(`#${fixingId}`).addClass('fixedL');
                $(`#${fixingId}`).css('bottom', 0)
            }

            if (sideIsFixed) {

                if (positionOfFooter <= 0)
                    $(`#${fixingId}`).css('bottom', Math.abs(positionOfFooter));
                else {
                    var absoluteTop = document.getElementById('dsfjk').getBoundingClientRect().top;

                    if (scrollMovement == 'up') {
                        if (absoluteTop < 10) {
                            var bot = parseInt($(`#${fixingId}`).css('bottom'));
                            $(`#${fixingId}`).css('bottom', bot - Math.abs(scrollPosition - lastScrollPosition))
                        }

                        var topOfAdSection = document.getElementById('pcSideAdSection').getBoundingClientRect().top;
                        if (topOfAdSection >= 0) {
                            sideIsFixed = false;
                            $(`#${fixingId}`).removeClass('fixedL');
                        }
                    } else {
                        if (bottomOfAd > 0) {
                            var bot = parseInt($(`#${fixingId}`).css('bottom'));
                            $(`#${fixingId}`).css('bottom', bot + Math.abs(scrollPosition - lastScrollPosition))
                        }
                    }
                }
            }

            lastScrollPosition = scrollPosition;
        });

        var url = $(location).attr('href')
        var decodedUri = decodeURIComponent(url);
        $("#telegram").attr("href", "https://telegram.me/share/url?url=" + decodedUri)
        $("#whatsApp").attr("href", "whatsapp://send?text=" + decodedUri)
        $("#twitter").attr("href", "https://twitter.com/home?status=" + decodedUri)
        $("#facebook").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + decodedUri)

        $(document).ready(function() {
            $('#copy-button').click(function() {
                var textToCopy = decodedUri;
                var tempTextarea = $('<textarea>');
                $('body').append(tempTextarea);
                tempTextarea.val(textToCopy).select();
                document.execCommand('copy');
                tempTextarea.remove();
                $('#copy-button').attr('data-original-title', '{{ __('main.linkCopied') }}').tooltip(
                    'show');
            });
        });
    </script>
@endsection
