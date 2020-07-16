@if (Route::current()->getName() !== 'site.contact')
	<section class="breadcrumb-area">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="breadcrumb">
						<ul>
							<li>
								<a href="{{url('/')}}">Home</a>
							</li>
							<li class="active">
								<a href="javascript: void(0);">{{$pageTitle}}</a>
							</li>
						</ul>
					</div>
					<h1 class="page-title">{{$pageTitle}}</h1>
				</div>
				<!-- end /.col-md-12 -->
			</div>
			<!-- end /.row -->
		</div>
		<!-- end /.container -->
	</section>
@else
	<section class="breadcrumb-area breadcrumb--center breadcrumb--smsbtl">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page_title">
                        <h3>{{$pageTitle}}</h3>
                        <p class="subtitle">You came to the right place</p>
                    </div>
                    <div class="breadcrumb">
                        <ul>
                            <li>
                                <a href="{{url('/')}}">Home</a>
                            </li>
                            <li class="active">
                                <a href="#">{{$pageTitle}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end /.row -->
        </div>
        <!-- end /.container -->
    </section>
@endif