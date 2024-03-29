@extends('user.layout.newsLayout')


@section('head')
    <title>{{ __('main.listTitle') }} {{ $content }}</title>

    <meta property="og:title" content="{{ $content }}" />
    <meta property="title" content="{{ $content }}" />
    <meta name="twitter:title" content="{{ $content }}" />
    @if (\App::getLocale() == 'en')
        <meta name="twitter:card"
            content="Stay up-to-date on the latest {{ $content }} news and information. From article to news and ,  you'll find it all in our {{ $content }} section." />
        <meta name="description"
            content="Stay up-to-date on the latest {{ $content }} news and information. From article to news and ,  you'll find it all in our {{ $content }} section." />
        <meta name="twitter:description"
            content="Stay up-to-date on the latest {{ $content }} news and information. From article to news and ,  you'll find it all in our {{ $content }} section." />
        <meta property="og:description"
            content="Stay up-to-date on the latest {{ $content }} news and information. From article to news and ,  you'll find it all in our {{ $content }} section." />
    @else
        <meta name="twitter:card"
            content="آخرین اخبار و اطلاعات {{ $content }} را در دسته بندی «{{ $content }} » بخوانید. از خبر و تحلیل گرفته تا نقد و پیش بینی دسته بندی {{ $content }} همه چیز را پیدا خواهید کرد." />
        <meta name="description"
            content="آخرین اخبار و اطلاعات {{ $content }} را در دسته بندی «{{ $content }} » بخوانید. از خبر و تحلیل گرفته تا نقد و پیش بینی دسته بندی {{ $content }} همه چیز را پیدا خواهید کرد." />
        <meta name="twitter:description"
            content="آخرین اخبار و اطلاعات {{ $content }} را در دسته بندی «{{ $content }} » بخوانید. از خبر و تحلیل گرفته تا نقد و پیش بینی دسته بندی {{ $content }} همه چیز را پیدا خواهید کرد." />
        <meta property="og:description"
            content="آخرین اخبار و اطلاعات {{ $content }} را در دسته بندی «{{ $content }} » بخوانید. از خبر و تحلیل گرفته تا نقد و پیش بینی دسته بندی {{ $content }} همه چیز را پیدا خواهید کرد." />
    @endif

    <meta name="keywords" content="{{ $content }}">
    <meta property="og:url" content="{{ Request::url() }}" />

    <meta property="og:image" content="{{ URL::asset('images/icons/mainLogofav.png') }}" />
    <meta property="og:image:secure_url" content="{{ URL::asset('images/icons/mainLogofav.png') }}" />
    <meta name="twitter:image" content="{{ URL::asset('images/icons/mainLogofav.png') }}" />
    <meta property="og:image:width" content="550" />
    <meta property="og:image:height" content="367" />

    <meta property="publisher" content="{{ $content }}" />
@endsection


@section('body')
    <style>
        .listBody .newsRow {
            float: left;
            height: 200px;
            padding: 10px 0px 0px 10px;
        }

        .listBody .newsRow img:hover {
            cursor: pointer;
            transition-timing-function: linear;
            transition-duration: 0.3s;
            transform: scale(1.03);
            -webkit-transform: scale(1.03);
            -moz-transform: scale(1.03);
            -ms-transform: scale(1.03);
            -o-transform: scale(1.03);
        }

        .mostViewDay li:hover {
            background-color: #d5d5d5;
        }

        .mostViewDay li:hover .title {
            color: #6D0606 !important;
        }

        .listBody .newsRow:hover .title {
            color: #6d0606 !important;
        }

        .listBody .newsRow:nth-child(1) {
            height: 400px;
            width: 66.6%;
            padding: 10px 0px 0px 0px;
        }

        .listBody .newsRow:nth-child(1) .title {
            padding-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0px;
            margin: 0px
        }

        .newsInRow {
            padding-bottom: 20px;
            padding-right: 20px;
            float: left !important;

        }

        .mostViewHeader {
            color: white !important;
            background-color: #6D0606;
            padding: 6px 5px;
            margin-bottom: 2px
        }

        .active a {
            color: #c71414 !important;
        }

        .active {
            border-bottom: 1px solid #c71414;
        }

        .topnews {
            color: white !important;
            background-color: #232323;
            padding: 6px 5px;
            margin-bottom: 2px;
            width: 100%;
        }

        .lastSpecialNew li:hover img {
            transition-timing-function: linear;
            transition-duration: 0.3s;
            transform: scale(1.03);
            -webkit-transform: scale(1.03);
            -moz-transform: scale(1.03);
            -ms-transform: scale(1.03);
            -o-transform: scale(1.03);
        }

        .lastSpecialNew li:hover .title {
            color: #6D0606 !important;
        }
    </style>
    <div class="directionSite">
        <a href="{{ route('site.news.main', ['lang' => \App::getLocale()]) }}">{{ __('main.mainPage') }} -&nbsp;</a>
        <div class="kind">{{ $kind }}</div>&nbsp; -&nbsp;{{ $content }}
    </div>
    <div class="listHeaderRow">
        <h2 style="font-weight: bold;"> {{ $content }}</h2>
    </div>

    <div class="">
        {{-- <div class="col-md-2 hideOnPhone">
            <div data-kind="ver_b" class="edSections edBetween onED"></div>
            <div data-kind="ver_s" class="edSections edBetween onED"></div>
            <div data-kind="ver_s" class="edSections edBetween onED"></div>
        </div> --}}

        <div class="col-md-12"style="padding:0px!important ">
            <div id="listBody" class="listBody"></div>
            <div id="lineAds" style="float: left;"class="col-md-12 col-xs-12">
                <img src="{{ URL::asset('images/shareBoxImg/lineAds.webp') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: fill;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);border: 1px solid gray;">
            </div>
            <div id="newsBody" class="listBody"></div>
            <div id="bottomMainList"></div>
            <div class="col-md-4 col-xs-12 centerNav" style="padding-bottom: 20px">
                <div style="padding-bottom: 20px">
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
                <div>
                    <div class="topnews">
                        <div style="font-size: 18px !important">{{ __('main.LatestSelections') }} </div>
                    </div>
                    <ul class="mostViewDay">
                        @foreach ($topNews as $item)
                            <li class="Point alignItemCen" style="margin-top: 5px;">
                                <a class="pdl10" style="color: black !important" href="{{ $item['url'] }}">
                                    <h2 class="title" style="margin:5px 0 0 0">{{ $item['title'] }}</h2>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        var mustBeTaken = true;
        var isFinish = false;
        var inTake = false;

        var kind = '{{ $kind }}';
        var header = '{{ $header }}';
        var content = '{{ $content }}';

        var page = 0;
        var take = 10;

        function preloadFunc() {
            // $("head").append('<meta property="news:tag" content="sina" />');

        }

        function getListElemes() {

            openLoading();
            if (!inTake && !isFinish) {
                inTake = true;
                $.ajax({
                    type: 'GET',
                    url: `{{ route('site.news.list.getElements', ['lang' => \App::getLocale()]) }}?kind=${kind}&content=${content}&take=${take}&page=${page}`,
                    headers: {
                        accept: 'application/json'
                    },
                    success: response => {

                        if (response.status == 'ok') {
                            for (let i = 0; i < response.result.length; i++) {
                                $("head").append('<meta property="news:tag" content="' + response.result[i]
                                    .keyword + '" />');
                            }
                            createListElements(response.result);
                        } else
                            getsList();
                    },
                    error: err => {
                        console.log(err);
                        getsList();
                    }
                })
            }
        }

        window.onload = preloadFunc();

        function getsList() {
            inTake = false;
            closeLoading();
        }

        function createListElements(_news) {

            var html = '';
            var text = '';
            mustBeTaken = false;

            if (_news.length != take)
                isFinish = true;

            _news.slice(0, 3).map(item => {
                // html += `<div class="newsRow">
            //             <a href="${item.url}" class="picSec fullyCenterContent">
            //                 <img src="${item.pic}" alt="${item.keyword}" class="resizeImgClass" onload="fitThisImg(this)">
            //             </a>
            //             <div class="content">
            //                 <a href="${item.url}" class="title">${item.title}</a>
            //                 <div class="summery">${item.meta}</div>
            //                 <div class="time">${item.dateAndTime}</div>
            //             </div>
            //         </div>`;
                html += `<div class="newsRow"><div class="newNews" style="width: 100%;position: relative;padding-bottom: 5px">
                    <img data-src="${item.pic}" alt="${item.keyword}" class="lazyload resizeImgClass"
                        style="width: 100% !important;height: 100% !important">
                    <a href="${item.url}" class="content">
                        <div style="display: flex;padding-bottom: 5px;">
                            <div style="background-color: #6D0606 ;width: 18px;">
                            </div>
                                <div style="background-color: #6D0606;margin-left: 2px;width: fit-content;padding: 0px 10px;color: white "> 
                                    ${content}
                                </div>
                        </div>
                        <p class="title">${item.title}</p>
                    </a>
                </div></div>`;
            });
            text += '<div class="col-lg-8 col-md-12 newsInRow" >';
            text += '<div class="mostViewHeader  bgColorRed">';
            text += '<div style="font-size: 18px !important">{{ __('main.LatestNews') }}</div>';
            text += '</div>';
            text += '<ul class="lastSpecialNew">';
            _news.slice(3, 10).map(item => {
                text += `                    
                            <li class="Point alignItemCen" style="margin-top: 5px;">
                                <div class="pdl10 d-flex" style=" box-shadow: 0px 3px 6px #00000029; padding-top: 5px">
                                    <div class="topNewsImg"><img
                                            style="width: 100%;height: 100%;object-fit: cover;"
                                            src="${item.pic}" alt="${item.keyword}"">
                                    </div>
                                    <div style="padding-left: 10px;width:70%">
                                        <a href="${item.url}">
                                            <div style="display: flex">

                                                <div style="background-color: #6D0606 ;width: 18px;">
                                                </div>
                                                <div
                                                    style="background-color: #6D0606;margin-left: 2px;width: fit-content;padding: 0px 10px;color: white ">
                                                    ${content}
                                                </div>
                                            </div>
                                            <h2 class="title bold" style="margin:5px 0 0 0;color: #232323;">
                                                ${item.title}</h2>
                                            <h6 class="title" style="margin:5px 0 0 0;color: #676767">
                                                ${item.meta}
                                            </h6>
                                        </a>
                                    </div>

                                </div>
                            </li>
                    `;
            });
            text += '</ul>';
            text += '</div>';

            closeLoading();

            $('#listBody').append(html);
            $('#newsBody').append(text);
            page++;

            inTake = false;
        }
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
                        html += '<h2 class="title" style="color: #232323">' + myRes.result[i].title + '</h2>';
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
        $(window).on('scroll', e => {
            var bottomOfList = document.getElementById('bottomMainList').getBoundingClientRect().top;
            var windowHeight = $(window).height();

            if (bottomOfList - windowHeight < 0 && !inTake && (!isFinish || mustBeTaken))
                getListElemes();
        });
        $(window).ready(getListElemes);

        var url = window.location.href;
        $(document).ready(function() {


            // passes on every "a" tag 
            $(".secHeadTabs a").each(function() {
                // checks if its the same on the address bar
                if (url == (this.href)) {
                    $(this).closest(".secHeadTabs").addClass("active");
                }
            });
        });
        if ($('html').is(':lang(en')) {

        } else {
            kind = 'دسته بندی';
            $('.kind').empty().append(kind);
        }
    </script>
@endsection
