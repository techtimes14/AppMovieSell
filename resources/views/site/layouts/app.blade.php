<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1">

  	<!-- CSRF Token -->
  	<meta name="csrf-token" content="{{ csrf_token() }}">

  	<title>{{ $title }}</title>
  	<meta name="keywords" content="{{ $keyword }}" />
  	<meta name="description" content="{{ $description }}" />
  
	<link rel="shortcut icon" type="image/x-icon" sizes="16x16" href="{{ asset('images/site/favicon.ico') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/bootstrap.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/css3_go_Animations.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/owl.carousel.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/jquery.fancybox.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/style.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/responsive.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/development.css') }}"/>
	<script type="text/javascript" src="{{ asset('js/site/jquery-3.3.1.min.js')}}"></script>
</head>
  	<body>
    	<div class="bodyOverlay"></div>
    	<div class="responsive_nav"></div>
    	<a class="scrollup" href="javascript:void(0);"><i class="fas fa-arrow-up"></i></a>

    	@include('site.elements.header')

    	@yield('content')
  
    	@include('site.elements.footer')

		<script type="text/javascript" src="{{ asset('js/site/owl.carousel.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/site/bootstrap.min.js')}}"></script>
		<script type="text/javascript" src="{{ asset('js/site/css3-animate-it.js')}}"></script>	
		<script type="text/javascript" src="{{ asset('js/site/jquery.fancybox.min.js')}}"></script>
		<script type="text/javascript" src="{{ asset('js/site/jquery.lazy.min.js')}}"></script>
		<script type="text/javascript" src="{{ asset('js/site/custom.js')}}"></script>    
		<script type="text/javascript" src="{{asset('js/site/jquery.validate.min.js')}}"></script>		
		<script type="text/javascript" src="{{asset('js/site/development.js')}}"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
	</body>
</html>
