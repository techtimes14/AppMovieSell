@extends('site.layouts.app', [])
  @section('content')

  @php
  $imgPath = URL:: asset('images').'/site/'.Helper::NO_IMAGE;
  if(file_exists(public_path('/uploads/cms/thumbs/'.$cmsData->local[0]->local_image))) {
    $imgPath = URL::to('/').'/uploads/cms/thumbs/'.$cmsData->local[0]->local_image;
  }
  @endphp

  <section class="innerBanner" style="background-image:url({{$imgPath}});">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 col-xl-4">
          <h1>{!! $cmsData->local[0]->local_title !!}</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="common-page">
    <div class="container">
      {!! $cmsData->local[0]->local_description !!}
    </div>
  </section>
    
  @endsection
