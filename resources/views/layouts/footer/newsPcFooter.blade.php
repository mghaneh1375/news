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
                <div>
                    کوچیتا، پلتفرم و شبکه‌ای اجتماعی در حوزه گردشگری است که با هدف ارتقاء سواد گردشگری، افزایش کیفیت سفر
                    و سهولت استفاده افراد جامعه، اعم از داخلی و بین‌المللی، از خدمات گردشگری ایجاد شده است.
                </div>
            </div>
            <div class="aboutShazdeLinkMargin" style="margin-top: 0">
                <div class="aboutShazdeLink" style="margin-bottom: 5px">

                </div>
                <div class="aboutShazdeLink">
                    {{ __('این سایت متعلق به مجموعه کوچیتا می باشد؛') }}
                    {{ __('بیشتر بدانید.') }}
                    {{ __('کوچیتا محصولی از') }}
                    <a href="http://www.sisootech.com/"
                        style="color: var(--koochita-dark-green) !important;">{{ __('شتاب دهنده سی سو تک') }} </a>
                    {{ __('و') }}
                    <a href="http://www.bogenstudio.com/" style="color: var(--koochita-dark-green) !important;">
                        {{ __('بوگن دیزاین') }} </a>
                    {{ __('می باشد؛ ما را بیشتر بشناسید.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="clear-both"></div>
</div>
