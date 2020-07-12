@extends('site.layouts.app', [])
  @section('content')
  
  <!--================================
    START HERO AREA
    =================================-->
    <section class="hero-area bgimage">
        <div class="banner-slider ">
            <div class="">
                <img src="{{asset('images/site/hero_area_bg1.jpg')}}" alt="background-image">
            </div>
            <div class="">
                <img src="{{asset('images/site/hero_bnner_2.jpg')}}" alt="background-image">
            </div>
            <div class="">
                <img src="{{asset('images/site/hero_banner_3.jpg')}}" alt="background-image">
            </div>
        </div>
        
        <!-- start hero-content -->
        <div class="hero-content content_above new-content-above">
            <!-- start .contact_wrapper -->
            <div class="content-wrapper">
                <!-- start .container -->
                <div class="container">
                    <!-- start row -->
                    <div class="row">
                        <!-- start col-md-12 -->
                        <div class="col-md-12">
                            <div class="hero__content__title">
                                <h1>
                                    <span class="light">Market Place </span>
                                    <span class="bold">For Movies, Files & Apps</span>
                                </h1>
                                <p class="tagline">Lorem ipsum dolor sit amet, consectetur adipisicing elit. consectetur adipisicing elit.</p>
                            </div>

                            <!-- start .hero__btn-area-->
                            <div class="hero__btn-area">
                                <a href="all-products.html" class="btn btn--round btn--lg">View All Products</a>
                                
                            </div>
                            <!-- end .hero__btn-area-->
                        </div>
                        <!-- end /.col-md-12 -->
                    </div>
                    <!-- end /.row -->
                </div>
                <!-- end /.container -->
            </div>
            <!-- end .contact_wrapper -->
        </div>
        <!-- end hero-content -->       
    </section>    
    <!--================================
    END HERO AREA
    =================================-->

    <!--================================
    START FEATURE AREA
    =================================-->
    <section class="features section--padding">
        <!-- start container -->
        <div class="container">
            <!-- start row -->
            <div class="row">
                <!-- start search-area -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature">
                        <div class="feature__img">
                            <img src="images/f1.png" alt="feature">
                        </div>
                        <div class="feature__title">
                            <h3>Upcoming Movies</h3>
                        </div>
                        <div class="feature__desc">
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>
                    <!-- end /.feature -->
                </div>
                <!-- end /.col-lg-4 col-md-6 -->

                <!-- start search-area -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature">
                        <div class="feature__img">
                            <img src="images/feature1.png" alt="feature">
                        </div>
                        <div class="feature__title">
                            <h3>Secure Files</h3>
                        </div>
                        <div class="feature__desc">
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>
                    <!-- end /.feature -->
                </div>
                <!-- end /.col-lg-4 col-md-6 -->

                <!-- start search-area -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature">
                        <div class="feature__img">
                            <img src="images/f3.png" alt="feature">
                        </div>
                        <div class="feature__title">
                            <h3>Popular Apps</h3>
                        </div>
                        <div class="feature__desc">
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,
                                leo quam aliquet diam congue is laoreet elit metus.</p>
                        </div>
                    </div>
                    <!-- end /.feature -->
                </div>
                <!-- end /.col-lg-4 col-md-6 -->
            </div>
            <!-- end /.row -->
        </div>
        <!-- end /.container -->
    </section>
    <!--================================
    END FEATURE AREA
    =================================-->


    <!--================================
    START FEATURED PRODUCT AREA
    =================================-->
    <section class="featured-products bgcolor  section--padding">
        <!-- start /.container -->
        <div class="container">
            <!-- start row -->
            <div class="row">
                <!-- start col-md-12 -->
                <div class="col-md-12">
                    <div class="product-title-area ">
                        <div class="product__title">
                            <h2>Current Featured Products</h2>
                        </div>

                        <div class="product__slider-nav rounded">
                            <span class="lnr lnr-chevron-left nav_left"></span>
                            <span class="lnr lnr-chevron-right nav_right"></span>
                        </div>
                    </div>
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end /.row -->
        </div>
        <!-- start .featured-product-slider -->

        <div class="container">
            <div class="row">
                <div class="col-md-12 no0-padding">
                    <div class="featured-product-slider prod-slider1">
                        <div class="featured__single-slider">
                            <div class="featured__preview-img">
                                <img src="images/featprod.jpg" alt="Featured products">
                                <div class="prod_btn">
                                    <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                    <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                </div>
                            </div>
                            <!-- end /.featured__preview-img -->

                            <div class="featured__product-description">
                                <div class="product-desc desc--featured">
                                    <a href="single-product.html" class="product_title">
                                        <h4>Lorem Ipsum</h4>
                                    </a>
                                    <ul class="titlebtm">
                                      
                                        <li class="product_cat">
                                            <a href="#">
                                                <span class="lnr lnr-book"></span> Files</a>
                                        </li>
                                    </ul>
                                    <!-- end /.titlebtm -->

                                    <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                        mattis, leo quam aliquet congue placerat mi id nisi interdum mollis. Praesent pharetra,
                                        justo ut scelerisque the mattis, leo quam aliquet congue justo ut scelerisque.</p>
                                </div>
                                <!-- end /.product-desc -->

                                <div class="product_data">
                                    <div class="tags tags--round">
                                        <ul>
                                            <li>
                                                <a href="#">app</a>
                                            </li>
                                            <li>
                                                <a href="#">movies</a>
                                            </li>
                                            <li>
                                                <a href="#">files</a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- end /.tags -->
                                    <div class="product-purchase featured--product-purchase d-sm-flex justify-content-between align-content-center align-items-center">
                                        <div class="price_love">
                                            <span>$10 </span>                                            
                                        </div>                                        
                                        <div class="prod_btn nw-prdct-btn">
                                            <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                            <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                        </div>
                                    </div>
                                    <!-- end /.product-purchase -->
                                </div>
                            </div>
                            <!-- end /.featured__product-description -->
                        </div>

                         <div class="featured__single-slider">
                            <div class="featured__preview-img">
                                <img src="images/featprod.jpg" alt="Featured products">
                                <div class="prod_btn">
                                    <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                    <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                </div>
                            </div>
                            <!-- end /.featured__preview-img -->

                            <div class="featured__product-description">
                                <div class="product-desc desc--featured">
                                    <a href="single-product.html" class="product_title">
                                        <h4>Lorem Ipsum</h4>
                                    </a>
                                    <ul class="titlebtm">
                                      
                                        <li class="product_cat">
                                            <a href="#">
                                                <span class="lnr lnr-book"></span> Files</a>
                                        </li>
                                    </ul>
                                    <!-- end /.titlebtm -->

                                    <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                        mattis, leo quam aliquet congue placerat mi id nisi interdum mollis. Praesent pharetra,
                                        justo ut scelerisque the mattis, leo quam aliquet congue justo ut scelerisque.</p>
                                </div>
                                <!-- end /.product-desc -->

                                <div class="product_data">
                                    <div class="tags tags--round">
                                        <ul>
                                            <li>
                                                <a href="#">app</a>
                                            </li>
                                            <li>
                                                <a href="#">movies</a>
                                            </li>
                                            <li>
                                                <a href="#">files</a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- end /.tags -->
                                    <div class="product-purchase featured--product-purchase d-sm-flex justify-content-between align-content-center align-items-center">
                                        <div class="price_love">
                                            <span>$10 </span>                                            
                                        </div>                                        
                                        <div class="prod_btn nw-prdct-btn">
                                            <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                            <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                        </div>                                        
                                    </div>
                                    <!-- end /.product-purchase -->
                                </div>
                            </div>
                            <!-- end /.featured__product-description -->
                        </div>

                        <div class="featured__single-slider">
                            <div class="featured__preview-img">
                                <img src="images/featprod.jpg" alt="Featured products">
                                <div class="prod_btn">
                                    <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                    <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                </div>
                            </div>
                            <!-- end /.featured__preview-img -->

                            <div class="featured__product-description">
                                <div class="product-desc desc--featured">
                                    <a href="single-product.html" class="product_title">
                                        <h4>Lorem Ipsum</h4>
                                    </a>
                                    <ul class="titlebtm">
                                      
                                        <li class="product_cat">
                                            <a href="#">
                                                <span class="lnr lnr-book"></span> Files</a>
                                        </li>
                                    </ul>
                                    <!-- end /.titlebtm -->

                                    <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                        mattis, leo quam aliquet congue placerat mi id nisi interdum mollis. Praesent pharetra,
                                        justo ut scelerisque the mattis, leo quam aliquet congue justo ut scelerisque.</p>
                                </div>
                                <!-- end /.product-desc -->

                                <div class="product_data">
                                    <div class="tags tags--round">
                                        <ul>
                                            <li>
                                                <a href="#">app</a>
                                            </li>
                                            <li>
                                                <a href="#">movies</a>
                                            </li>
                                            <li>
                                                <a href="#">files</a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- end /.tags -->
                                    <div class="product-purchase featured--product-purchase d-sm-flex justify-content-between align-content-center align-items-center">
                                        <div class="price_love">
                                            <span>$10 </span>
                                            
                                        </div>
                                        
                                            <div class="prod_btn nw-prdct-btn">
                                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                            </div>
                                       
                                        
                                        
                                    </div>
                                    <!-- end /.product-purchase -->
                                </div>
                            </div>
                            <!-- end /.featured__product-description -->
                        </div>

                         <div class="featured__single-slider">
                            <div class="featured__preview-img">
                                <img src="images/featprod.jpg" alt="Featured products">
                                <div class="prod_btn">
                                    <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                    <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                </div>
                            </div>
                            <!-- end /.featured__preview-img -->

                            <div class="featured__product-description">
                                <div class="product-desc desc--featured">
                                    <a href="single-product.html" class="product_title">
                                        <h4>Lorem Ipsum</h4>
                                    </a>
                                    <ul class="titlebtm">
                                      
                                        <li class="product_cat">
                                            <a href="#">
                                                <span class="lnr lnr-book"></span> Files</a>
                                        </li>
                                    </ul>
                                    <!-- end /.titlebtm -->

                                    <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the
                                        mattis, leo quam aliquet congue placerat mi id nisi interdum mollis. Praesent pharetra,
                                        justo ut scelerisque the mattis, leo quam aliquet congue justo ut scelerisque.</p>
                                </div>
                                <!-- end /.product-desc -->

                                <div class="product_data">
                                    <div class="tags tags--round">
                                        <ul>
                                            <li>
                                                <a href="#">app</a>
                                            </li>
                                            <li>
                                                <a href="#">movies</a>
                                            </li>
                                            <li>
                                                <a href="#">files</a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <!-- end /.tags -->
                                    <div class="product-purchase featured--product-purchase d-sm-flex justify-content-between align-content-center align-items-center">
                                        <div class="price_love">
                                            <span>$10 </span>                                            
                                        </div>                                        
                                        <div class="prod_btn nw-prdct-btn">
                                            <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                            <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                                        </div>
                                    </div>
                                    <!-- end /.product-purchase -->
                                </div>
                            </div>
                            <!-- end /.featured__product-description -->
                        </div>                        
                        <!--end /.featured__single-slider-->
                    </div>
                </div>
            </div>
            <!-- end /.featured__preview-img -->
        </div>
        <!-- end /.featured-product-slider -->
    </section>
    <!--================================
    END FEATURED PRODUCT AREA
    =================================-->

    <!--================================
    START COUNTER UP AREA
    =================================-->
    <section class="counter-up-area bgimage">
        <div class="bg_image_holder">
            <img src="images/countbg.jpg" alt="">
        </div>
        <!-- start .container -->
        <div class="container content_above">
            <!-- start .col-md-12 -->
            <div class="col-md-12">
                <div class="counter-up">
                    <div class="counter mcolor2">
                        <span class="lnr lnr-briefcase"></span>
                        <span class="count">38,436</span>
                        <p>Items for sale</p>
                    </div>
                    <div class="counter mcolor3">
                        <span class="lnr lnr-cloud-download"></span>
                        <span class="count">38,436</span>
                        <p>Total Sales</p>
                    </div>
                    <div class="counter mcolor1">
                        <span class="lnr lnr-smile"></span>
                        <span class="count">38,436</span>
                        <p>Happy customers</p>
                    </div>
                    <div class="counter mcolor4">
                        <span class="lnr lnr-users"></span>
                        <span class="count">38,436</span>
                        <p>Members</p>
                    </div>
                </div>
            </div>
            <!-- end /.col-md-12 -->
        </div>
        <!-- end /.container -->
    </section>
    <!--================================
    END COUNTER UP AREA
    =================================-->

    <section class="why_choose section--padding">
        <!-- start container -->
        <div class="container">
            <!-- start row -->
            <div class="row">
                <!-- start col-md-12 -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h1>{!! $homeCmsData->name !!}</h1>
                        {!! $homeCmsData->description !!}
                    </div>
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end /.row -->

            <!-- start row -->
        @if($whyusData->count() > 0)
            <div class="row">
                <!-- start .col-md-4 -->
            @foreach($whyusData as $key => $data)
                <div class="col-lg-4 col-md-6">
                    <!-- start .reason -->
                    <div class="feature2">
                        <span class="feature2__count">{{sprintf("%02d",$key+1)}}</span>
                        <div class="feature2__content">
                            <span class="{{$data->icon_class}}"></span>
                            <h3 class="feature2-title">{{$data->title}}</h3>
                            <p>{!! $data->description !!}</p>
                        </div>
                        <!-- end /.feature2__content -->
                    </div>
                    <!-- end /.feature2 -->
                </div>
            @endforeach
                <!-- end /.col-md-4 -->

               
               
            </div>
        @endif
            <!-- end /.row -->
        </div>
        <!-- end /.container -->
    </section>
    <!--================================
    END COUNTER UP AREA
    =================================-->

    <!--================================
    START CALL TO ACTION AREA
    =================================-->
    <section class="call-to-action bgimage">
        <div class="bg_image_holder">
            <img src="images/calltobg.jpg" alt="">
        </div>
        <div class="container content_above">
            <div class="row">
                <div class="col-md-12">
                    <div class="call-to-wrap">
                        <h1 class="text--white">Ready to Join Our Marketplace!</h1>
                        <h4 class="text--white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. </h4>
                        <a href="login.html" class="btn btn--lg btn--round btn--white callto-action-btn">Join Us Today</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================================
    END CALL TO ACTION AREA
    =================================-->
    
  @endsection