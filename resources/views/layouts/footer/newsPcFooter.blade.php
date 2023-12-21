<div class="hideOnPhone screenFooterStyle">
    <div style="display: flex;margin: auto;width: 85%;">
        <div class="footerLogoSocialBox" style="margin-right: 10px">
            <a href="{{ route('site.news.main', ['lang' => \App::getLocale()]) }}" class="footerLogo"
                style="display: flex; align-items: center;">
                {{-- <img alt="dornanews" src="{{ URL::asset('images/camping/undp.svg') }}" style="height: 60px"> --}}
                <img alt="dornanews" src="{{ URL::asset('images/icons/mainLogo.svg') }}" class="content-icon"
                    width="100%">
            </a>
        </div>
        <div class="footerRside">
            <div id="aboutShazde" class="aboutShazdeMoreLess">
                <div class="clear-both hideOnScreen"></div>
                <div style="color: #676767">{{ __('main.footer') }}</div>
            </div>
        </div>
    </div>
</div>
<div style="background-color: #232323;width: 100%;height:30px ;"></div>
