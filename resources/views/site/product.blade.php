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

                        <div class="filter__option filter--select">
                            <div class="select-wrap">
                                <select name="price" id="price">
                                    <option value="low-to-high">Price : Low to High</option>
                                    <option value="high-to-low">Price : High to low</option>
                                </select>
                                <span class="lnr lnr-chevron-down"></span>
                            </div>
                        </div>
                        <!-- end /.filter__option -->

                        <div class="filter__option filter--select mr-0">
                            <div class="select-wrap">
                                <select name="per_page" id="per_page">
                                    <option value="12">12 Items per page</option>
                                    <option value="18">18 Items per page</option>
                                    <option value="24">24 Items per page</option>
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

            <div id="products_div">
                @include('site.elements.products_with_pagination')
            </div>

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

    <script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault(); 
            var page    = $(this).attr('href').split('page=')[1];
            var perPage = $('#per_page').val();
            var price   = $('#price').val();

            getMoreProducts(page, perPage, price);
        });
    });

    function getMoreProducts(page, perPage, price) {
        var websiteLink = $('#website_link').val();
        var updatedUrl          = websiteLink + '/market-place';
        var getMoreProductsUrl  = websiteLink + '/market-place-products';

        if (page != 1) {
            updatedUrl          = updatedUrl + '?page='+page+'&price='+price+'&perPage='+perPage;
            getMoreProductsUrl  = getMoreProductsUrl + '?page='+page+'&price='+price+'&perPage='+perPage;
        } else {
            updatedUrl = updatedUrl + '?price='+price+'&perPage='+perPage;
            getMoreProductsUrl  = getMoreProductsUrl + '?price='+price+'&perPage='+perPage;
        }
        window.history.pushState({path: updatedUrl},'',updatedUrl);
        // console.log(updatedUrl);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: getMoreProductsUrl,
            success:function(data) {
                $('#products_div').html(data);
            }
        });
    }
</script>
    
  @endsection