<div class="hideOnPhone screenFooterStyle">
    <div class="footerLogoSocialBox">
        <a href="{{ route('site.news.main', ['lang' => \App::getLocale()]) }}" class="footerLogo"
            style="display: flex; align-items: center;">
            <img alt="کوچیتا، سامانه جامع گردشگری ایران" src="{{ URL::asset('images/camping/undp.svg') }}"
                style="height: 60px">
            <img alt="کوچیتا، سامانه جامع گردشگری ایران" src="{{ URL::asset('images/icons/mainLogo.png') }}"
                class="content-icon" width="100%">
        </a>
    </div>
    <div>
        <div class="footerRside">
            <div id="aboutShazde" class="aboutShazdeMoreLess">
                <div class="clear-both hideOnScreen"></div>
                <div>{{ __('main.footer') }}</div>
            </div>
            <div class="aboutShazdeLinkMargin" style="margin-top: 0">
                <div class="aboutShazdeLink" style="margin-bottom: 5px">

                </div>
            </div>
        </div>
    </div>

    <div class="clear-both"></div>
</div>
