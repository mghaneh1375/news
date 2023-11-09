<footer>
    @include('layouts.footer.newsPcFooter')

    <script>

        if (typeof(Storage) !== "undefined") {
            seeLoginHelperFunction = localStorage.getItem('loginButtonHelperNotif1');
            if(seeLoginHelperFunction == null || seeLoginHelperFunction == false){
                setTimeout(() => {
                    setTimeout( openLoginHelperSection, 1000);
                    localStorage.setItem('loginButtonHelperNotif1', true);
                }, 5000);
            }
        }
        else
            console.log('your browser not support localStorage');
    </script>

    <script src="{{URL::asset('js/pages/layout/placeFooter.js?v=1.1')}}"></script>
</footer>


