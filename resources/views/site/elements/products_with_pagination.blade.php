@if ($products->count() > 0)
    <div class="row">
    @foreach($products as $row)
        @php
        $productImage = \URL:: asset('images').'/site/'.Helper::NO_IMAGE;
        if ($row->productDefaultImage->count() > 0) {
            if(file_exists(public_path('/uploads/product/list_thumbs'.'/'.$row->productDefaultImage[0]->image))) {
                $productImage = \URL::asset('uploads/product/list_thumbs').'/'.$row->productDefaultImage[0]->image;
            }
        }
        @endphp
        <div class="col-lg-2 col-md-4">
            <div class="product product--card">
                <div class="product__thumbnail">
                    <img src="{{$productImage}}" alt="Product Image">
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
                                <span class="lnr lnr-camera-video align-middle"></span>{{$row->categoryDetails->title}}
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
            <div class="pagination-area">
                <nav class="navigation pagination" role="navigation">
                    {!! $products->links('site.pagination.default') !!}
                </nav>
            </div>
        </div>
    </div>
@else
    <p>@lang('custom.message_no_records_found')</p>
@endif