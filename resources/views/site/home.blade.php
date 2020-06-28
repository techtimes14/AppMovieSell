@extends('site.layouts.app', [])
  @section('content')
  
  <main class="site_main" id="site_main">
    <div class="about_section">
        <div class="container">
            <div class="tab_slider_section">
                <div class="tab_menu">
                    <ul class="row">
                        <li class="col-sm-4">
                            <div class="tab_menu_cntnt">
                                <figure class="tabmenu_icon">
                                    <img src="{{asset('images/site/trending_icon.png')}}" alt="">
                                </figure>
                                <div class="tabmenu_txt">
                                    <h3>{{$trendingData->name}}</h3>
                                    {!!$trendingData->description!!}
                                </div>
                            </div>
                        </li>
                        <li class="col-sm-4">
                            <div class="tab_menu_cntnt">
                                <figure class="tabmenu_icon">
                                    <img src="{{asset('images/site/browse_icon.png')}}" alt="">
                                </figure>
                                <div class="tabmenu_txt">
                                    <h3>{{$browseByData->name}}</h3>
                                    {!!$browseByData->description!!}
                                </div>
                            </div>
                        </li>
                        <li class="col-sm-4">
                            <div class="tab_menu_cntnt">
                                <figure class="tabmenu_icon">
                                    <img src="{{asset('images/site/favourite_icon.png')}}" alt="">
                                </figure>
                                <div class="tabmenu_txt">
                                    <h3>{{$favouritesData->name}}</h3>
                                    {!!$favouritesData->description!!}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            @if (count($trendingVideos) > 0)
                <div class="tab_details">
                    <div class="tab_info">
                        <h2>Treanding</h2>
                        <div class="owl-carousel owl-theme tab_slider">
                        @foreach ($trendingVideos as $trendingVideo)
                            <div class="item">
                                <a data-vid="{{$trendingVideo->id}}" data-toggle="modal" class="openVideoPopUp">
                                    <figure>
                                        <img class="card-img-top img-fluid" src="{{$trendingVideo->image_standard_url}}">
                                    </figure>
                                    <div class="play_icon">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            @endif
            </div>
            <div class="about_sec">
                <div class="row">
                    <div class="col-md-2">
                        <h2>{!! $aboutData->title !!}</h2>
                        {{-- <a href="#" class="link d_link">View more</a> --}}
                    </div>
                    <div class="col-md-10">
                        <div class="abt_cntnt">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! $aboutData->description !!}
                                </div>
                                <div class="col-md-6">
                                    {!! $aboutData->description2 !!}
                                </div>
                            </div>
                            {{-- <a href="#" class="link m_link">View more</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($siteSetting['home_short_description'] != '')
    <div class="middle_text">
        <div class="container">
            <div class="middle_cntnt">{!!$siteSetting['home_short_description']!!}</div>
        </div>
    </div>
    @endif
@if (count($recentlyAddedVideos) > 0)
    <div class="recent_video_sec">
        <div class="container">
            <h2>Recently Added Videos</h2>
            <div class="owl-carousel owl-theme recent_video_slider">
            @foreach ($recentlyAddedVideos as $recentVideo)
                <div class="item">
                    <a data-vid="{{$recentVideo->id}}" data-toggle="modal" class="openVideoPopUp">
                        <figure>
                            <img class="card-img-top img-fluid" src="{{$recentVideo->image_standard_url}}">
                        </figure>
                        <div class="play_icon">
                        </div>
                    </a>                    
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endif
</main>
    
  @endsection