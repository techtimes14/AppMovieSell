@php
$siteSettings = Helper::getSiteSettings();
@endphp
<div class="whole-area" id="whole-area" style="display: none;">
    <div class="loader" id="loader-1"></div>
</div>

<footer class="site_footer">
  <div class="container">
      <div class="footer_sec">
          <div class="f_cntct">
              <img src="{{asset('images/site/heart_rate.png')}}" alt="">
          </div>
          <div class="f_cntct">
              <i class="fab fa-instagram"></i>
              <a href="#">{{$siteSettings->instagram_link}}</a>
          </div>
          <div class="f_cntct">
              <i class="far fa-envelope"></i>
              <a href="mailto:{{$siteSettings->from_email}}">{{$siteSettings->from_email}}</a>
          </div>
          <div class="f_cntct">
              <i class="icon-video"></i>
              <a href="#">streamfit.ca</a>
          </div>
      </div>
  </div>
    <input type="hidden" name="website_link" id="website_link" value="{{ url('/') }}" />
</footer>

{{-- Pop-up video start --}}
<div class="modal fade browseby_ifrmae" id="browseby_ifrmae" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close close_video_popup" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="container" id="original_contents" style="display: none;">
                <div class="modal-body">
                    <div class="ytube_vdo" id="yt-player">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="fancybox_footer">
                        <div class="fancybox_footer_lft">
                            <h2>Fitness from home</h2>
                            <div class="iframe_tag" id="youtube_video_tags">
                                <ul>
                                    <li>Fitness</li>
                                </ul>
                            </div>
                        </div>
                        @php
                        if(Auth::user()) {
                            $loginStatus = 'yes';
                        } else {
                            $loginStatus = 'no';
                        }
                        @endphp
                        <div class="fancybox_footer_rit">
                            <a javascript="void(0);" class="popupfaviconholder fav @if (!Auth::user())userNotLoggedin @endif" data-loginstatus="{{$loginStatus}}" data-videoid="0">
                                <span class="popupfavicon s-icon popupfav"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container" id="before_original_contents">
                <p>Please wait...</p>
            </div>
        </div>
    </div>
</div>
{{-- Pop-up video end --}}

@if (Auth::user())
{{-- Create Board start --}}
<div class="modal fade create_board_section" id="create_board" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close close_create_board" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="container">
                <div class="modal-body">
                    <div class="create_board_popup">
                        <div class="create_board_popup_inner">
                            <h2>Create board</h2>
                            <form method="POST" id="createBoardForm" name="createBoardForm" autocomplete="off">
                                {{ csrf_field() }}
                                <input type="text" name="board_name" id="board_name" required value="" placeholder="Name">
                                <label id="board_name-error" class="error" for="board_name">&nbsp;</label><br />
                                <input type="submit" value="create">
                            </form>
                            <div class="text_center" id="create_board_message"></div>
                            <figure class="create_board_logo">
                                <img src="{{asset('images/site/create_board_logo.png')}}" alt="">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Create Board end --}}

{{-- Add To Favourite start --}}
<div class="modal fade favourite_popup" id="add_to_favouite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close close_add_to_favourite" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <form method="POST" id="makeFavouriteForm" name="makeFavouriteForm" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="favoirute_sec">
                        <h2>Add to favourites</h2>
                        <div class="add_to_fav">
                        @if (Auth::user()->boardList->count() > 0)
                            <label class="radio_btn">Save in folder
                                <input type="radio" class="main_save" name="save" id="save_in_folder" value="Save in folder">
                                <span class="radio"></span>
                            @foreach (Auth::user()->boardList as $board)
                                <label class="radio_btn">{{$board->title}}
                                    <input type="radio" class="child_save" name="save_folder" value="{{$board->id}}">
                                    <span class="radio"></span>
                                </label>
                            @endforeach
                            </label>
                        @endif
                            <input type="hidden" name="fav_video_id" id="fav_video_id" value="">
                            <input type="hidden" name="fav_video" id="fav_video" value="">

                            <label class="radio_btn">Save
                                <input type="radio" class="main_save" id="save" name="save" value="Save">
                                <span class="radio"></span>
                            </label>
                        </div>
                        <p><input type="submit" value="Save to Favourite"></p>
                        <div class="text_center" id="make_favourite_message"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Add To Favourite end --}}

@endif