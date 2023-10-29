
<div class="nSideBar">
    <div class="logoSide">
        <img src="{{URL::asset('img/mainLogo.png')}}" >
        <div class="closeMobileMenu" onclick="openMenuSide()">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
    </div>
    <div class="sideLinksSection">
        @if(auth()->check())
            <a href="{{url('/')}}" class="navs">
                <div class="header">
                    <i class="fa big-icon fa-home icon"></i>
                    <span class="text">خانه</span>
                </div>
            </a>

            <div class="navs" onclick="openSubMenu(this)">
                <div class="header">
                    <i class="fa big-icon fa-home icon"></i>
                    <span class="text">اخبار</span>
                </div>
                <div class="subMenu">
                    <a href="{{route('news.list')}}" class="subNavs">لیست اخبار</a>
                </div>
            </div>

            <a class="navs" href="{{route('changePass')}}">
                <div class="header">
                    <i class="fa big-icon fa-home icon"></i>
                    <span class="text">تغییر رمزعبور</span>
                </div>
            </a>

            @can('userAclGate', 'news')
                <div class="navs" onclick="openSubMenu(this)">
                    <div class="header">
                        <i class="fa big-icon fa-home icon"></i>
                        <span class="text">اخبار</span>
                    </div>
                    <div class="subMenu">
                        <a href="{{route('news.list')}}" class="subNavs">لیست اخبار</a>
                        <a href="{{route('advertisement', ['kind' => 'news'])}}" class="subNavs">تبلیغ بخش اخبار</a>
                    </div>
                </div>
            @endcan


            <a href="{{ 'https://tour.bogenstudio.com/cas/logout?redirectUrl=' . route('logout') }}"  role="button" class="navs">
                <div class="header">
                    <i class="fa fa-sign-out icon"></i>
                    <span class="mini-dn text">خروج</span>
                </div>
            </a>
        @else
            <a href="{{route('login')}}" aria-expanded="false" class="navs">
                <div class="header">
                    <i class="fa big-icon fa-login icon"></i>
                    <span class="text">ورود</span>
                    <span class="indicator-right-menu mini-dn">
                    <i class="fa indicator-mn fa-angle-left"></i>
                </span>
                </div>
            </a>
        @endif


    </div>
</div>

<script>
    function openSubMenu(_element) {
        if($(_element).hasClass('active'))
            $('.sideLinksSection').find('.active').removeClass('active');
        else{
            $('.sideLinksSection').find('.active').removeClass('active');
            $(_element).addClass('active')
        }
    }
</script>
