	<!DOCTYPE html>
	<html lang="zxx" class="no-js">
	<head>
		<!--Csrf TOKNE-->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="img/fav.png">
		<!-- Author Meta -->
		<meta name="author" content="CodePixar">
		<!-- Meta Description -->
		<meta name="description" content="">
		<!-- Meta Keyword -->
		<meta name="keywords" content="">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>TRCBooks - @yield('title')</title>

		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
			<!--
			CSS
			============================================= -->
			<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
			<link rel="stylesheet" href="css/linearicons.css">
			<link rel="stylesheet" href="css/font-awesome.min.css">
			<link rel="stylesheet" href="css/jquery.DonutWidget.min.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/owl.carousel.css">
			<link rel="stylesheet" href="css/main.css">
		</head>
		<body>

			<div id="app">
					@include('sections.header')	
					
					@section('main')
						<!-- start banner Area -->
						<section class="banner-area relative" id="home" data-parallax="scroll" data-image-src="img/header-bg.jpg">
							<div class="overlay-bg overlay"></div>
							<div class="container">
								<div class="row fullscreen  d-flex align-items-center justify-content-end">
									<div class="banner-content col-lg-6 col-md-12">
										<h1>
											"Nunca  <br>
											<span>juzgues un libro</span> por su  <br>
												<span> película."</span>							
										</h1>						 
									</div>												
								</div>
							</div>
						</section>
						<!-- End banner Area -->
						@include('sections.main-content')
					@show
										
					
					@include('sections.footer')
			</div>

			<script src="{{ URL::asset('/js/app.js') }}"></script>
			<script src="https://kit.fontawesome.com/10d46139ed.js"></script>
			<script src="js/vendor/jquery-2.2.4.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
			<script src="js/vendor/bootstrap.min.js"></script>
			<script src="js/jquery.ajaxchimp.min.js"></script>
			<script src="js/parallax.min.js"></script>			
			<script src="js/owl.carousel.min.js"></script>			
			<script src="js/jquery.sticky.js"></script>
			<script src="js/jquery.DonutWidget.min.js"></script>
			<script src="js/jquery.magnific-popup.min.js"></script>			
			<script src="js/main.js"></script>	
			@section('scripts')
    		@show
		</body>
	</html>