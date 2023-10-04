<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@lang('cmn.project_name')</title>
    <link rel="icon" href="{{ asset('/favicon.ico') }}" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- flag-icon -->
	  <link rel="stylesheet" href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- pace-progress -->
	  <!-- <link rel="stylesheet" href="{{ asset('assets/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}"> -->
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- mapbox -->
    {{-- <link href='https://api.mapbox.com/mapbox-gl-js/v2.8.1/mapbox-gl.css' rel='stylesheet' /> --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])

  </head>
  <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <!-- pace-primary -->
    <div id="app"></div>
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- pace -->
	  <!-- <script src="{{ asset('assets/plugins/pace-progress/pace.min.js') }}"></script> -->
    <!-- overlayScrollbars -->
    <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

    <!-- AdminLTE for demo purposes -->
    <!-- <script src="{{ asset('assets/dist/js/demo.js') }}"></script> -->
  </body>
</html>
