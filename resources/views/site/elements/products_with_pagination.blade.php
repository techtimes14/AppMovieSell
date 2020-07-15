    <div class="row">
    @foreach($products as $row)
        <div class="col-lg-2 col-md-4">
            <div class="product product--card">
                <div class="product__thumbnail">
                    <img src="{{asset('images/site/p1.jpg')}}" alt="Product Image">
                    <div class="prod_btn">
                        <a href="single-product.html" class="transparent btn--sm btn--round">View More</a>                    
                    </div>
                </div>
                <div class="product-desc">
                    <a href="#" class="product_title">
                        <h4>{{$row->title}}</h4>
                    </a>
                    <ul class="titlebtm">                    
                        <li class="product_cat">
                            <a href="#" class="">
                                <span class="lnr lnr-camera-video align-middle"></span>movies
                            </a>
                        </li>
                    </ul>                
                </div>
                <div class="product-purchase d-flex align-content-center align-self-center justify-content-between">
                    <div class="price_love">
                        <span>${{Helper::formatToTwoDecimalPlaces($row->price)}} </span>                    
                    </div>
                    <div class="sell nw-prdct-btn">                    
                        <a href="single-product.html" class="transparent btn--sm btn--round">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>

    <div class="row">
        <div class="col-md-12">            
            {!! $products->links() !!}                    
        </div>
    </div>