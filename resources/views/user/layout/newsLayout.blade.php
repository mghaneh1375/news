<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    @include('layouts.newsTopHeader')

    <link rel="stylesheet" href="{{ URL::asset('css/pages/news.css?v=1.1') }}">

    <style>
        .addNewReviewButtonMobileFooter {
            display: none;
        }
    </style>

    @yield('head')
</head>

<body>

    @include('general.forAllPages')

    @include('layouts.news-header1')


    <div class="container-fluid secHeadMain hideOnPhone">
        <div class="container secHeadNavs">

            <div class="secHeadTabs">
                <a href="{{ route('site.news.main') }}" style="color: white">صفحه اصلی</a>
            </div>

            @foreach ($newsCategories as $category)
                @if (trim($category->name) != 'استان ها' && $category->name != 'اکوسیستم استارتاپی')
                    <div class="secHeadTabs {{ count($category->sub) > 0 ? 'arrowAfter' : '' }}">
                        <a href="{{ route('site.news.list', ['kind' => 'category', 'content' => $category->name]) }}"
                            style="color: white">{{ $category->name }}</a>
                        <div class="secHeadTabsSubList">
                            @foreach ($category->sub as $sub)
                                <a href="#">{{ $sub->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>


    <div class="container">
        @yield('body')
    </div>

    <div class="mobileFiltersButtonTabs hideOnScreen">
        <div class="tabs">
            <div class="tab filterIcon" onclick="openMyModal('newCategoryMobileModal')">دسته بندی</div>
            <div class="tab searchIcon" onclick="openMyModal('newsSearchMobile')">جستجو</div>
        </div>
    </div>

    <div id="newCategoryMobileModal" class="modalBlackBack fullCenter hideOnScreen" style="transition: .7s">
        <div class="gombadi">
            <div class="mobileFooterFilterPic" style="max-height: 400px">
                <img src="{{ URL::asset('images/mainPics/news/news.jpg') }}" style="width: 100%">
                <div class="gradientWhite">
                    <div class="closeThisModal iconClose" onclick="closeMyModal('newCategoryMobileModal')"></div>
                </div>
            </div>
            <div class="newsCategoryListMFooter">
                <div class="list">
                    @foreach ($newsCategories as $cat)
                        <a href="{{ route('site.news.list', ['kind' => 'category', 'content' => $cat->name]) }}"
                            class="categ">
                            <div class="categIcon" style="{{ $cat->icon == 'sogatsanaie.svg' ? 'margin: 0px;' : '' }}">
                                <img src="{{ URL::asset('images/mainPics/news/icons/' . $cat->icon) }}"
                                    alt="{{ $cat->name }}">
                            </div>
                            <div class="title">{{ $cat->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="newsSearchMobile" class="modalBlackBack fullCenter hideOnScreen">
        <div class="gombadi">
            <div class="mobileFooterFilterPic" style="max-height: 400px">
                <img src="{{ URL::asset('images/mainPics/news/news.jpg') }}" style="width: 100%">
                <div class="gradientWhite">
                    <div class="closeThisModal iconClose" onclick="closeMyModal('newsSearchMobile')"></div>
                </div>
            </div>
            <div class="newsCategoryListMFooter searchInMobileNewsBody" style="height: 100%">
                <div class="title">جستجو در اخبار</div>
                <div class="fullyCenterContent">
                    <input type="text" id="newsSearchInputInMobile" class="searchInput"
                        placeholder="عبارت خود را وارد کنید...">
                </div>
                <div class="fullyCenterContent" style="margin-top: 20px">
                    <button class="searchButton" onclick="searchInNews('newsSearchInputInMobile')">جستجو</button>
                </div>
            </div>
        </div>
    </div>


    @yield('script')

    <script>
        var newCategoryMobileModalElement = $('#newCategoryMobileModal');

        function searchInNews(_inputId) {
            var value = $('#' + _inputId).val();

            if (value.trim().length > 2) {
                openLoading();
                location.href = `{{ url('/news/list/content') }}/${value}`;
            }
        }

        function resizeMobileListHeight() {
            var height = newCategoryMobileModalElement.find('.mobileFooterFilterPic').height() + 5;
            newCategoryMobileModalElement.find('.newsCategoryListMFooter').css('height', `calc(100% - ${height}px)`);
        }

        $(window).on('resize', resizeMobileListHeight);
        $(window).ready(() => resizeMobileListHeight());
    </script>

    @include('layouts.footer.newsLayoutFooter')

</body>

</html>
