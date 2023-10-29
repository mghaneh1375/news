<link rel='stylesheet' type='text/css' media='screen, print' href='{{URL::asset('css/shazdeDesigns/abbreviations.css?v=1.1')}}'/>
<link rel='stylesheet' type='text/css' media='screen, print' href='{{URL::asset('css/shazdeDesigns/proSearch.css?v=1.1')}}'/>

<div id="darkModal" class="display-none" role="dialog"></div>

<div id="darkModeMainPage" class="ui_backdrop dark" ></div>

<script>
    function resizeFitImg(_class) {
        let imgs = $('.' + _class);
        for(let i = 0; i < imgs.length; i++)
            fitThisImg(imgs[i]);
    }

    function fitThisImg(_element){
        var img = $(_element);
        var imgW = img.width();
        var imgH = img.height();

        var secW = img.parent().width();
        var secH = img.parent().height();

        if(imgH < secH){
            img.css('height', '100%');
            img.css('width', 'auto');
        }
        else if(imgW < secW){
            img.css('width', '100%');
            img.css('height', 'auto');
        }
    }
</script>

@include('general.loading')

@include('general.alerts')


<script>
    var openHeadersTab = false;
    var seenToZero = false;
    var csrfTokenGlobal = '{{csrf_token()}}';
    var hasLogin = {{auth()->check() ? 1 : 0}};
    

    window.seenRelatedId = sessionStorage.getItem("lastPageLogId") == null ? 0 : sessionStorage.getItem("lastPageLogId");
    window.seenPageLogId = 0;
    window.userScrollPageLog = [];
    var userWindowInScrolling = null;
    var seenLogStartTime = new Date().getTime();
    var lastSeenLogScroll = 0;


    var playTvImg = "{{URL::asset('images/icons/play.webp')}}";
    var eyeTvImg = "{{URL::asset('images/icons/eye.png')}}";

    $(document, window).ready(() => {
        window.isMobile = window.mobileAndTabletCheck() ? 1 : 0;

        resizeFitImg('resizeImgClass');
        showLastPages();

        $(".login-button").click(() => checkLogin());
    });
</script>

<script src="{{URL::asset('js/component/answerPack.js?v=1.1')}}"></script>
<script src="{{URL::asset('js/pages/forAllPages.js?v=1.1')}}"></script>
<script defer src="{{URL::asset('js/component/load-image.all.min.js')}}"></script>

