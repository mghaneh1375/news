@if(!Request::is('main') && !Request::is('main/*') && !Request::is('/'))
    <style>
        .headerSecondSection{
            display: none;
        }
        .mainHeader{
            background: var(--koochita-light-green);
        }
        .headerIconCommon:before{
            color: white;
        }
        .nameOfIconHeaders{
            color: white;
        }
        .headerAuthButton:hover .headerIconCommon:before{
            color: white;
        }
        .headerAuthButton:hover .nameOfIconHeaders{
            color: white;
        }
    </style>
@endif

    {{--pc header--}}
<div class="mainHeader hideOnPhone">
    <div class="container headerContainer">
        <a href="{{route('site.news.main')}}" class="headerPcLogoDiv" >
            <img src="{{URL::asset('images/icons/mainLogo.png')}}" alt="{{__('کوچیتا')}}" class="headerPcLogo"/>
        </a>

        <div class="headerButtonsSection">


        </div>
    </div>

</div>


{{--mobile header--}}
<div class="hideOnScreen mobileHeader">
    <a href="{{route('site.news.main')}}" class="global-nav-logo" style="height: 100%; display: flex; align-items: center">
        <img src="{{URL::asset('images/camping/undp.svg')}}" alt="{{__('کوچیتا')}}" style="height: 50px; width: auto;"/>
        <img src="{{URL::asset('images/icons/mainLogo.png')}}" alt="{{__('کوچیتا')}}" style="height: 80%; width: auto;"/>
    </a>
</div>

<div id="campingHeader" class="modalBlackBack" style="z-index: 999;  display: none">
    <div class="headerCampaignModalBody">
        <span class="iconClose closeLanding" onclick="$('#campingHeader').hide();"></span>
        <div class="headerCampingTop" onclick="goToLanding()">
            <img  alt="کوچیتا، سامانه جامع گردشگری ایران" src="{{URL::asset('images/camping/undp.svg')}}" style="position: absolute; width: 60px; top: 10px; right: 2%;">
            <img alt="کوچیتا، سامانه جامع گردشگری ایران" src="{{URL::asset('images/camping/' . app()->getLocale() . '/landing.webp')}}" class="resizeImgClass" style="width: 100%;" onload="fitThisImg(this)">
        </div>
        <div class="headerCampingBottom">
            
            <div onclick="$('#campingHeader').hide(); openUploadPost()">
                <img alt="کوچیتا، سامانه جامع گردشگری ایران" src="{{URL::asset('images/camping/' . app()->getLocale() . '/nAxasi.webp')}}" class="resizeImgClass" onload="fitThisImg(this)">
            </div>
        </div>
    </div>
</div>

<script>
    var bookMarksData = null;
    var locked = false;
    var superAccess = false;
</script>

<script src="{{URL::asset('js/pages/layout/header1.js?v=1.1')}}"></script>
