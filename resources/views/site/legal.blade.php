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
        START LEGAL AREA
    =================================-->
    <section class="pricing_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h1>{!! $legaleData->name !!}</h1>
                        {!! $legaleData->description2 !!}
                    </div>
                    <div class="legal_section">
                        <div class="legal_contents">
                            {!! $legaleData->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================================
        END LEGAL AREA
    =================================-->
    
  @endsection