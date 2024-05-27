<!DOCTYPE html>
<html>
<head>
  <title>Login Pages</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
{{--    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">--}}
  <!-- plugin css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css')}}">
  <!-- end plugin css -->

  <!-- plugin css -->

  <!-- end plugin css -->

  <!-- common css -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <!-- end common css -->


</head>
<body data-base-url="{{url('/')}}">

  <div class="container-scroller" id="app">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      @yield('content')
    </div>
  </div>
{{--  <script src="{{asset('assets/js/app.js')}}"></script>--}}
    <!-- base js -->
  <script src="{{asset('js/app.js')}}"></script>
    <!-- end base js -->

    <!-- plugin js -->

    <!-- end plugin js -->


</body>
</html>
