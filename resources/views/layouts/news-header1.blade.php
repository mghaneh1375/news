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
            font-family: 'Courier New', Courier, monospace;
            direction: ltr;
            color: white;
        }
    </style>
@endif

{{-- pc header --}}
<div class="mainHeader hideOnPhone">
    <div class="headerContainer">
        <a href="{{ route('site.news.main', ['lang' => \App::getLocale()]) }}" class="headerPcLogoDiv">
            <img src="{{ URL::asset('images/icons/mainLogo.svg') }}" alt="{{ __('کوچیتا') }}" class="headerPcLogo" />
        </a>

        <div class="headerButtonsSection flexDirColumn">
            {{-- <div id="time"></div> --}}
            <div style="color: #232323;font-size: 14px;text-align: end;width: 100%;"> فارسی - About - policies -
                advertizement</div>
            <div class="d-flex">
                <div id="enTime"style="background-color: #6D0606;margin-left:5px;" class="alignItemCen pd10"></div>
                <div style="background-color: #6D0606" class="flexRowRev alignItemCen">
                    <span class="searchIcon colorWhite" style="font-size: 30px;margin-left: 3px;"></span>
                    <input type="text" placeholder="Type Here"
                        style="background-color: #6D0606;border: unset;color:#ffffff ;height: 30px;">
                </div>
            </div>
        </div>
    </div>

</div>


{{-- mobile header --}}
<div class="hideOnScreen mobileHeader">
    <a href="{{ route('site.news.main', ['lang' => \App::getLocale()]) }}" class="global-nav-logo"
        style="height: 100%; display: flex; align-items: center">
        <img src="{{ URL::asset('images/icons/mainLogo.png') }}" alt="{{ __('کوچیتا') }}"
            style="height: 80%; width: auto;" />
    </a>
</div>

<div id="campingHeader" class="modalBlackBack" style="z-index: 999;  display: none">
    <div class="headerCampaignModalBody">
        <span class="iconClose closeLanding" onclick="$('#campingHeader').hide();"></span>
        <div class="headerCampingTop" onclick="goToLanding()">
            <img alt="کوچیتا، سامانه جامع گردشگری ایران" src="{{ URL::asset('images/camping/undp.svg') }}"
                style="position: absolute; width: 60px; top: 10px; right: 2%;">
            <img alt="کوچیتا، سامانه جامع گردشگری ایران"
                src="{{ URL::asset('images/camping/' . app()->getLocale() . '/landing.webp') }}" class="resizeImgClass"
                style="width: 100%;" onload="fitThisImg(this)">
        </div>
        <div class="headerCampingBottom">

            <div onclick="$('#campingHeader').hide(); openUploadPost()">
                <img alt="کوچیتا، سامانه جامع گردشگری ایران"
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
    $('#time').append(time);
    var enTime = moment().format('D MMMM YYYY');
    $('#enTime').append(enTime);
</script>

<script src="{{ URL::asset('js/pages/layout/header1.js?v=1.1') }}"></script>
