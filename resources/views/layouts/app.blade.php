<!doctype html>
<html lang="en">

<head>
    <base href="{{ asset('/') }}">
    <!-- Required meta tags -->
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Wieldy - A fully responsive, HTML5 based admin template">
	<meta name="keywords" content="Responsive, HTML5, admin theme, business, professional, jQuery, web design, CSS3, sass">
    <!--favicon-->
    <link rel="icon" href="storage/{{ FAVICON_PATH.config('settings.favicon') }}" type="image/png" />

	<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- stylesheets start -->
	@include('layouts.partials.styles')
    <!-- stylesheets end -->

    <title>@yield('title')</title>

	@stack('styles')
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <x-sidebar />
        <!--end sidebar wrapper -->
        @include('includes.header')
        <!--start header -->

        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">

				<!-- content area start -->
                @yield('content')
				<!-- content area end -->

            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        @include('includes.footer')
    </div>
    <!--end wrapper-->

    <!--start switcher-->
    <x-aside />
    <!--end switcher-->

	<!-- scripts start -->
    @include('layouts.partials.scripts')
	<!-- scripts end -->
	<script>
		var _token = "{{ csrf_token() }}";
	</script>

	@stack('scripts')
</body>

</html>
