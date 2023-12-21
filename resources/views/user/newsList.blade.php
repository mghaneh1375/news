@extends('user.layout.newsLayout')


@section('head')
    <title>کوچیتا | لیست {{ $header }}</title>
@endsection


@section('body')
    <style>
        .listBody .newsRow {
            float: left;
            height: 200px;
            width: 33%;
            padding: 10px 0px 0px 10px;
        }

        .listBody .newsRow:nth-child(1) {
            height: 400px;
            width: 66.6%;
            padding: 10px 0px 0px 0px;
        }

        ul {
            list-style-type: none;
            padding: 0px;
            margin: 0px
        }

        .newsInRow {
            float: left !important;
            padding: 0px
        }
    </style>
    <div class="directionSite">
        Home - {{ $header }}
    </div>
    <div class="listHeaderRow">
        <h2 style="font-weight: bold;"> {{ $header }}</h2>
    </div>

    <div class="">
        {{-- <div class="col-md-2 hideOnPhone">
            <div data-kind="ver_b" class="edSections edBetween onED"></div>
            <div data-kind="ver_s" class="edSections edBetween onED"></div>
            <div data-kind="ver_s" class="edSections edBetween onED"></div>
        </div> --}}

        <div class="col-md-12"style="padding:0px!important ">
            <div id="listBody" class="listBody"></div>
            <div id="lineAds" style="height: 100px;margin: 30px 0px;float: left;"class="col-md-9 col-xs-12">
                <img src="{{ URL::asset('images/shareBoxImg/lineAds.jpg') }}"
                    alt=""style="width: 100%;height: 100%;object-fit: fill;box-shadow: 0 5px 8px -1px rgba(0, 0, 0, 0.7);border: 1px solid gray;">
            </div>
            <div id="newsBody" class="listBody"></div>
            <div id="bottomMainList"></div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        var mustBeTaken = true;
        var isFinish = false;
        var inTake = false;

        var kind = '{{ $kind }}';
        var content = '{{ $content }}';
        var page = 0;
        var take = 10;

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

                        if (response.status == 'ok')
                            createListElements(response.result);
                        else
                            getsList();
                    },
                    error: err => {
                        console.log(err);
                        getsList();
                    }
                })
            }
        }

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
                html += `<div class="newsRow"><div class="  newNews" style="width: 100%;position: relative;padding-bottom: 5px">
                    <img data-src="${item.pic}" alt="${item.keyword}" class="lazyload resizeImgClass"
                        style="width: 100% !important;height: 100% !important">
                    <a href="${item.url}" class="content">
                        <p class="title">${item.title}</p>
                    </a>
                </div></div>`;
            });
            text += '<div class="col-md-8 newsInRow">';
            text += '<ul class="lastSpecialNew">';
            _news.slice(0, 3).map(item => {
                text += `                    
                            <li class="Point alignItemCen" style="border-left: solid 3px #6D0606;margin-top: 5px;">
                                <div class="pdl10 d-flex" style=" box-shadow: 0px 3px 6px #00000029; padding-top: 5px">
                                    <div style="width:210px;height:140px;"><img
                                            style="width: 100%;height: 100%;object-fit: contain;"
                                            src="${item.pic}" alt="${item.keyword}"">
                                    </div>
                                    <div style="padding-left: 10px">
                                        <a href="${item.url}">
                                            <div style="display: flex">

                                                <div style="background-color: #6D0606 ;width: 18px;">
                                                </div>
                                                <div
                                                    style="background-color: #6D0606;margin-left: 2px;width: fit-content;padding: 0px 10px;color: white ">
                                                    ${item.category}
                                                </div>
                                            </div>
                                            <h6 class="title bold" style="margin:5px 0 0 0;color: #232323;">
                                                ${item.title}</h6>
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


        $(window).on('scroll', e => {
            var bottomOfList = document.getElementById('bottomMainList').getBoundingClientRect().top;
            var windowHeight = $(window).height();

            if (bottomOfList - windowHeight < 0 && !inTake && (!isFinish || mustBeTaken))
                getListElemes();
        });

        $(window).ready(getListElemes);
    </script>
@endsection
