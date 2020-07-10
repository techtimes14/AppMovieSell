@extends('site.layouts.app', [])
  @section('content')

    <!--================================
        START SEARCH AREA
    =================================-->
    <section class="search-wrapper">
        <div class="search-area2 bgimage">
            <div class="bg_image_holder">
                <img src="{{asset('images/site/search.jpg')}}" alt="">
            </div>
            <div class="container content_above">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="search">
                            <div class="search__title">
                                <h3><span>Over 1000</span> of new Movies, Apps & Secure files. </h3>
                            </div>
                            <div class="search__field">
                                <form action="#">
                                    <div class="field-wrapper">
                                        <input class="relative-field rounded" type="text" placeholder="Search your products">
                                        <button class="btn btn--round" type="submit">Search</button>
                                    </div>
                                </form>
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
                    </div>
                </div>
                <!-- end /.row -->
            </div>
            <!-- end /.container -->
        </div>
        <!-- end /.search-area2 -->
    </section>
    <!--================================
        END SEARCH AREA
    =================================-->

    <!--================================
        START FILTER AREA
    =================================-->
    <div class="filter-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="filter-bar d-flex justify-content-between ">
                        <div class="filter__option filter--dropdown border-0 ">
                            <a href="#" id="drop1" class="dropdown-trigger dropdown-toggle pl-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories
                                <span class="lnr lnr-chevron-down"></span>
                            </a>
                            <ul class="custom_dropdown custom_drop2 dropdown-menu" aria-labelledby="drop1">
                                <li>
                                    <a href="#">Movies
                                        <span>100</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">Files
                                        <span>45</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">App
                                        <span>85</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                        <!-- end /.filter__option -->

                       
                       

                        <div class="filter__option filter--select">
                            <div class="select-wrap">
                                <select name="price">
                                    <option value="low">Price : Low to High</option>
                                    <option value="high">Price : High to low</option>
                                </select>
                                <span class="lnr lnr-chevron-down"></span>
                            </div>
                        </div>
                        <!-- end /.filter__option -->

                        <div class="filter__option filter--select mr-0">
                            <div class="select-wrap">
                                <select name="price">
                                    <option value="10">10 Items per page</option>
                                    <option value="15">15 Items per page</option>
                                    <option value="20">20 Items per page</option>
                                </select>
                                <span class="lnr lnr-chevron-down"></span>
                            </div>
                        </div>
                        <!-- end /.filter__option -->

                        
                    </div>
                    <!-- end /.filter-bar -->
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end filter-bar -->
        </div>
    </div>
    <!-- end /.filter-area -->
    <!--================================
        END FILTER AREA
    =================================-->


    <!--================================
        START PRODUCTS AREA
    =================================-->
    <section class="products new-product">
        <!-- start container -->
        <div class="container">

            <!-- start .row -->
            <div class="row">
                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p1.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#" class="">
                                        <span class="lnr lnr-camera-video align-middle"></span>movies</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$10 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p2.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-book align-middle"></span>files</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$50 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="`">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p3.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                               
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-smartphone align-middle"></span>App</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>free </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p4.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                               <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-book align-middle"></span>files</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$80 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p5.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-smartphone align-middle"></span>App</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$10 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p6.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-camera-video align-middle"></span>movies</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$40 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p1.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-camera-video align-middle"></span>movies</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$30 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p2.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-book align-middle"></span>files</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$20 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p3.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-smartphone align-middle"></span>App</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$30 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p4.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-smartphone align-middle"></span>App</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$50 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p5.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                               
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-book align-middle"></span>files</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>$60 </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->

                <!-- start .col-md-4 -->
                <div class="col-lg-2 col-md-4">
                    <!-- start .single-product -->
                    <div class="product product--card">

                        <div class="product__thumbnail">
                            <img src="images/p6.jpg" alt="Product Image">
                            <div class="prod_btn">
                                <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>
                                
                            </div>
                            <!-- end /.prod_btn -->
                        </div>
                        <!-- end /.product__thumbnail -->

                        <div class="product-desc">
                            <a href="#" class="product_title">
                                <h4>Lorem Ipsum</h4>
                            </a>
                            <ul class="titlebtm">
                                
                                <li class="product_cat">
                                    <a href="#">
                                        <span class="lnr lnr-smartphone align-middle"></span>App</a>
                                </li>
                            </ul>

                            
                        </div>
                        <!-- end /.product-desc -->

                        <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                            <div class="price_love">
                                <span>free </span>
                                
                            </div>
                            <div class="sell nw-prdct-btn">
                               
                                <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                            </div>
                        </div>
                        <!-- end /.product-purchase -->
                    </div>
                    <!-- end /.single-product -->
                </div>
                <!-- end /.col-md-4 -->
            </div>
            <!-- end /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="pagination-area">
                        <nav class="navigation pagination" role="navigation">
                            <div class="nav-links">
                                <a class="prev page-numbers" href="#">
                                    <span class="lnr lnr-arrow-left"></span>
                                </a>
                                <a class="page-numbers current" href="#">1</a>
                                <a class="page-numbers" href="#">2</a>
                                <a class="page-numbers" href="#">3</a>
                                <a class="next page-numbers" href="#">
                                    <span class="lnr lnr-arrow-right"></span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- end /.row -->
        </div>
        <!-- end /.container -->
    </section>
    <!--================================
        END PRODUCTS AREA
    =================================-->


    <!--================================
        START CALL TO ACTION AREA
    =================================-->
    @include('site.elements.call_to_action')
    <!--================================
        END CALL TO ACTION AREA
    =================================-->
    
  @endsection