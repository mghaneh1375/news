@if (!Request::is('main') && !Request::is('main/*') && !Request::is('/'))
    <style>
        ::placeholder {
            color: white;
            opacity: 1;
            /* Firefox */
        }

        .headerSecondSection {
            display: none;
        }

        .headerIconCommon:before {
            color: white;
        }

        .nameOfIconHeaders {
            color: white;
        }

        .headerAuthButton:hover .headerIconCommon:before {
            color: white;
        }

        .headerAuthButton:hover .nameOfIconHeaders {
            color: white;
        }

        #time {
            color: white;
            margin-left: 5px
        }

        #enTime {
            background-color: #6D0606;
            margin-left: 5px;
            direction: ltr;
            color: white;
        }
    </style>
@endif

{{-- pc header --}}
<div class="mainHeader hideOnPhone">
    <div class="headerContainer">
        <a href="{{ route('site.news.main', ['lang' => \App::getLocale()]) }}" class="headerPcLogoDiv">
            <img src="{{ URL::asset('images/icons/mainLogo.svg') }}" alt="{{ __('درنا') }}" class="headerPcLogo" />
        </a>

        <div class="headerButtonsSection flexDirColumn">

            <div style="color: #232323;font-size: 15px;width: 100%;display: flex;justify-content: center;">
                About-policies-advertisement - <a href=""id="lang"
                    style="color: #232323;">{{ __('main.lang') }}</a>
            </div>
            <div class="d-flex">
                <div style="background-color: #6D0606" class="flexRowRev alignItemCen">
                    <input type="text" placeholder="{{ __('main.TypeHere') }}"
                        style="background-color: #6D0606;border: unset;color:#ffffff ;height: 30px;width: 120px;">
                    <span class="searchIcon colorWhite"
                        style="font-size: 30px;margin-left: 3px;margin-right: 3px"></span>
                </div>
                <div id="enTime"style="background-color: #6D0606;margin-left:5px;" class="alignItemCen pd10"></div>
            </div>
        </div>
    </div>

</div>


{{-- mobile header --}}
<div class="hideOnScreen mobileHeader">

    <div class="filterIcon menuIcon" onclick="openMyModal('newCategoryMobileModal')"></div>
    {{-- <div class="tab searchIcon" onclick="openMyModal('newsSearchMobile')">جستجو</div> --}}


    <a href="{{ route('site.news.main', ['lang' => \App::getLocale()]) }}" class="global-nav-logo"
        style="height: 100%; display: flex; align-items: center">
        <img src="{{ URL::asset('images/icons/mainLogo.svg') }}" alt="{{ __('درنا') }}"
            style="height: 80%; width: auto;" />
    </a>
</div>

<div id="campingHeader" class="modalBlackBack" style="z-index: 999;  display: none">
    <div class="headerCampaignModalBody">
        <span class="iconClose closeLanding" onclick="$('#campingHeader').hide();"></span>
        <div class="headerCampingTop" onclick="goToLanding()">
            <img alt="Dorna News | The latest news and article in Economy, Tourism, International, Social, Handicrafts and Heritage, Art and culture and technology"
                src="{{ URL::asset('images/camping/undp.svg') }}"
                style="position: absolute; width: 60px; top: 10px; right: 2%;">
            <img alt="Dorna News | The latest news and article in Economy, Tourism, International, Social, Handicrafts and Heritage, Art and culture and technology"
                src="{{ URL::asset('images/camping/' . app()->getLocale() . '/landing.webp') }}" class="resizeImgClass"
                style="width: 100%;" onload="fitThisImg(this)">
        </div>
        <div class="headerCampingBottom">

            <div onclick="$('#campingHeader').hide(); openUploadPost()">
                <img alt="Dorna News | The latest news and article in Economy, Tourism, International, Social, Handicrafts and Heritage, Art and culture and technology"
                    src="{{ URL::asset('images/camping/' . app()->getLocale() . '/nAxasi.webp') }}"
                    class="resizeImgClass" onload="fitThisImg(this)">
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('js/jalaliDate.js') }}"></script>
<script script src="https://momentjs.com/downloads/moment.js"></script>
<script>
    var bookMarksData = null;
    var locked = false;
    var superAccess = false;
    var time = getPersianDate();
    if ($('html').is(':lang(en')) {
        var enTime = moment().format('D MMMM YYYY');
        $('#enTime').append(enTime);
        $('#lang').prop('href', 'https://dornanews.com/fa');
    } else {
        $('#enTime').append(time);
        $('#lang').prop('href', 'https://dornanews.com/fa');
        $('#lang').prop('href', 'https://dornanews.com/en');
    }
</script>

<script src="{{ URL::asset('js/pages/layout/header1.js?v=1.1') }}"></script>
