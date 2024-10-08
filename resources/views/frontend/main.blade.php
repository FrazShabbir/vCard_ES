<!DOCTYPE html>
<html lang="en">


<head>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3329597755027709"
        crossorigin="anonymous"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VHCMR9NB1F"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-VHCMR9NB1F');
    </script>

    @include('frontend.partial._header')
</head>

<body>

    <!-- page wrapper start -->

    <div class="page-wrapper">

        <!-- preloader start -->

        @include('frontend.partial._preloader')

        <!-- preloader end -->

        <!--header start-->
        @yield('banner')


        <!--body content start-->

        <div class="page-content">

            <!--feature start-->
            @yield('content')



        </div>

        <!--body content end-->


        <!--footer start-->

        @include('frontend.partial._footer')

        <!--footer end-->

    </div>

    <!-- page wrapper end -->

    @include('frontend.partial._scripts')
    @include('sweetalert::alert')

</body>



</html>
