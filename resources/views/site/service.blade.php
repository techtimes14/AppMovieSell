@extends('site.layouts.app', [])
  @section('content')

    <!--================================
        START BREADCRUMB AREA
    =================================-->
    @include('site.elements.breadcrumb')
    <!--================================
        END BREADCRUMB AREA
    =================================-->
  
    <!--================================
        START SERVICE AREA
    =================================-->
    <section class="section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="services_section">
                @if ($seviceList->count() > 0)
                    @foreach ($seviceList as $service)
                        @php
                        $serviceImage = \URL:: asset('images').'/site/'.Helper::NO_IMAGE;
                        if(file_exists(public_path('/uploads/service/thumbs'.'/'.$service->Image))) {
                            $serviceImage = \URL::asset('uploads/service/thumbs').'/'.$service->image;
                        }
                        @endphp
                        <div class="service_sec">
                            <figure>
                                <img src="{{$serviceImage}}" alt="">
                            </figure>
                            <div class="service_content">
                                <h2>{{$service->title}}</h2>
                                {!! $service->description !!}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>@lang('custom.message_no_records_found')</p>
                @endif                        
                    </div>
                </div>
            </div>
            <!-- end .row -->
        </div>
        <!-- end .container -->
    </section>
    <!--================================
        END SERVICE AREA
    =================================-->
    
  @endsection