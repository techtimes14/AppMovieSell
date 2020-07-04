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
	{{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/site/bootstrap.min.css') }}"/> --}}
	
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/plugins.min.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/style.css') }}"/>	
	<link rel="stylesheet" type="text/css" href="{{ asset('css/site/development.css') }}"/>

	<script type="text/javascript" src="{{ asset('js/site/jquery-3.3.1.min.js')}}"></script>
</head>
  	<body class="preload home1 mutlti-vendor">
    	
    	@include('site.elements.header')

    	@yield('content')
  
    	@include('site.elements.footer')

		{{-- <script type="text/javascript" src="{{asset('js/site/bootstrap.min.js')}}"></script> --}}
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0C5etf1GVmL_ldVAichWwFFVcDfa1y_c"></script>
    	<!-- inject:js -->
    	<script type="text/javascript" src="{{ asset('js/site/plugins.min.js')}}"></script>		
		<script type="text/javascript" src="{{ asset('js/site/script.min.js')}}"></script>	
		
		<script type="text/javascript" src="{{asset('js/site/jquery.validate.min.js')}}"></script>		
		<script type="text/javascript" src="{{asset('js/site/development.js')}}"></script>
		<script type="text/javascript" src="{{asset('js/site/sweetalert2.js')}}"></script>
		{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script> --}}

		@if(Route::current()->getName() == 'site.users.add-payment-method' || Route::current()->getName() == 'site.users.affiliated-payment')
		<script type="text/javascript" src="{{asset('js/site/creditCardValidator.js')}}"></script>
		@endif
		
	</body>
</html>