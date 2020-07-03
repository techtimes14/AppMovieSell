<div class="menu-area">
	<!-- start .top-menu-area -->
	<div class="top-menu-area">
		<!-- start .container -->
		<div class="container">
			<!-- start .row -->
			<div class="row align-content-center align-items-center">
				<!-- start .col-md-3 -->
				<div class="col-lg-3 col-md-3 col-6 v_middle">
					<div class="logo">
						<a href="index.html">
							<img src="{{asset('images/site/logo.png')}}" alt="logo image" class="img-fluid">
						</a>
					</div>
				</div>
				<!-- end /.col-md-3 -->

				<!-- start .col-md-5 -->
				<div class="col-lg-8 offset-lg-1 col-md-9 col-6 v_middle">
					<!-- start .author-area -->
					<div class="author-area">
						<a href="{{route('site.users.login')}}" class="author-area__seller-btn inline-block">Log In</a>
						<a href="{{route('site.users.sign-up')}}" class="author-area__seller-btn inline-block mr-0">Sign Up</a>
					</div>
					<!-- end .author-area -->

					<!-- author area restructured for mobile -->
					<div class="mobile_content ">
						<span class="lnr lnr-user menu_icon"></span>

						<!-- offcanvas menu -->
						<div class="offcanvas-menu closed">
							<span class="lnr lnr-cross close_menu"></span>
							

							<div class="text-center mbl-signbtn">
								<a href="login.html" class="author-area__seller-btn">Log In</a>
								<a href="signup.html" class="author-area__seller-btn">Sign Up</a>
							</div>
						</div>
					</div>
					<!-- end /.mobile_content -->
				</div>
				<!-- end /.col-md-5 -->
			</div>
			<!-- end /.row -->
		</div>
		<!-- end /.container -->
	</div>
	<!-- end  -->

	<!-- start .mainmenu_area -->
	<div class="mainmenu">
		<!-- start .container -->
		<div class="container">
			<!-- start .row-->
			<div class="row">
				<!-- start .col-md-12 -->
				<div class="col-md-12">
					

						<div class="navbar-header navbar-header-mbl">
							
						</div>
					<nav class="navbar navbar-expand-md navbar-light mainmenu__menu">
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
							aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="navbarNav">
							<ul class="navbar-nav">
								<li class="active"><a href="index.html">HOME</a></li>
									<li><a href="about.html">About us</a></li>
									<li><a href="javascript:void(0);">Services</a></li>
									<li><a href="javascript:void(0);">Contact</a></li>
									<li><a href="javascript:void(0);">Legal</a></li>
									
									<li><a href="javascript:void(0);">Market Place</a></li>
								
							</ul>
						</div>
						<!-- /.navbar-collapse -->
					</nav>
				</div>
				<!-- end /.col-md-12 -->
			</div>
			<!-- end /.row-->
		</div>
		<!-- start .container -->
	</div>
	<!-- end /.mainmenu-->
</div>