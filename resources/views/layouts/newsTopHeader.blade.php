<meta content="43970F70216852DDFADD70BBB51A6A8D" name="jetseo-site-verification" rel="verify" />

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="theme-color" content="#30b4a6" />
<meta name="msapplication-TileColor" content="#30b4a6">
<meta name="msapplication-TileImage" content="{{ URL::asset('images/icons/mainIcon.png') }}">
<meta name="twitter:card" content="summary" />
<meta property="og:url" content="{{ Request::url() }}" />
<meta property="og:site_name" content="سامانه جامع گردشگری کوچیتا" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link rel="icon" href="{{ URL::asset('images/icons/KOFAV0.svg') }}" sizes="any" type="image/svg+xml">
<link rel="apple-touch-icon-precomposed" href="{{ URL::asset('images/icons/KOFAV0.svg') }}" sizes="any"
    type="image/svg+xml">

<link rel='stylesheet' type='text/css' href='{{ URL::asset('css/fonts.css?v=1.1') }}' media="all" />
<link rel='stylesheet' type='text/css' href='{{ URL::asset('css/theme2/bootstrap.min.css?v=1.1') }}' media="all" />
<link rel='stylesheet' type='text/css' href='{{ URL::asset('css/theme2/topHeaderStyles.css?v=1.1') }}' media="all" />
<link rel='stylesheet' type='text/css' href='{{ URL::asset('css/shazdeDesigns/icons.css?v1=1.1') }}' media="all" />
<link rel="stylesheet" type='text/css' href="{{ URL::asset('css/theme2/swiper.css?v=1.1') }}" media="all">
<link rel="stylesheet" href="{{ URL::asset('css/component/components.css?v=1.1') }}" media="all">
<link rel="stylesheet" href="{{ URL::asset('css/component/generalFolder.css?v=1.1') }}">

<link rel="stylesheet" href="{{ URL::asset('css/common/header.css?v=.1.1') }}">
<link rel="stylesheet" href="{{ URL::asset('css/common/header1.css?v=1.1') }}">
<link rel="stylesheet" href="{{ URL::asset('css/common/DA.css?v=1.1') }}">
<link rel='stylesheet' type='text/css' href='{{ URL::asset('css/shazdeDesigns/footer.css?v=1.1') }}' />

<link rel="stylesheet" href="{{ URL::asset('packages/fontAwesome6/css/all.min.css') }}">
<script src="{{ URL::asset('packages/fontAwesome6/js/all.min.js') }}"></script>


@if (\App::getLocale() == 'en')
    <link rel="stylesheet" href="{{ URL::asset('css/ltr/mainPageHeader.css?v=1.1') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/ltr/ltrFooter.css?v=1.1') }}">
@endif

<script src="{{ URL::asset('js/jquery-3.4.1.min.js') }}"></script>
<script async src="{{ URL::asset('js/defualt/bootstrap.min.js') }}"></script>

<script src="{{ URL::asset('js/defualt/autosize.min.js') }}"></script>
<script src="{{ URL::asset('js/swiper/swiper.min.js') }}"></script>
<script async src="{{ URL::asset('js/defualt/lazysizes.min.js') }}"></script>

<style>
    @if (\App::getLocale() == 'en')

        * {
            font-family: enFonts;
            direction: ltr !important;
            text-align: left;
        }

        .suggestionPackDetailDiv {
            direction: ltr;
        }
    @else
        * {
            font-family: IRANSansWeb;
        }
    @endif
</style>


@if (config('app.env') != 'local')
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158914626-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-158914626-1');
    </script>
@endif

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name":"سامانه جامع گردشگری کوچیتا",
	"alternateName":"Koochita",
	"url":"https://koochita.com",
	"sameAs": [
         "https://www.facebook.com/Koochita-115157527076374",
         "https://twitter.com/Koochita_Com",
         "https://www.instagram.com/koochita_com/",
         "https://t.me/koochita",
         "https://wa.me/989120239315"
    ],
	"address":[
        {
            "@type": "PostalAddress",
            "addressCountry": "IR",
            "addressRegion": "تهران",
            "streetAddress": "میدان ونک ، قبل از چهارراه جهان کودک ، ساختمان دانشگاه علامه طبابایی، طبقه سوم ، سیسوتک"
        }
    ],
    "email":"info@koochita.com",
    "logo":"{{URL::asset('images/icons/KOFAV0.svg')}}",
	"founder":[
		{
			"@type": "Person",
			"name": "Soore Vahedzade"
        }
    ]
},
</script>


<script type="text/javascript">
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        },
    });


    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function hideElement(e) {
        $(".dark").hide();
        $("#" + e).addClass("hidden");
    }

    function showElement(e) {
        $("#" + e).removeClass("hidden");
        $(".dark").show();
    }

    function setDefaultPic(_element) {
        _element.src = '{{ URL::asset('images/mainPics/noPicSite.jpg') }}';
    }
</script>
