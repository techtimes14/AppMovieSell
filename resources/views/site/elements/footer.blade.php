<?php
$siteSetting = Helper::getSiteSettings();
?>
<footer class="footer-area">
    <div class="footer-big section--padding">
        <!-- start .container -->
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="info-footer">
                        <div class="info__logo">
                            <img src="{{asset('images/site/flogo.png')}}" alt="footer logo">
                        </div>
                        <p class="info--text pr-md-5">{!!$siteSetting->home_short_description!!}</p>
                        
                    </div>
                    <!-- end /.info-footer -->
                </div>
                <!-- end /.col-md-3 -->

                <div class="col-lg-4 col-md-6">
                    <div class="footer-menu">
                        <h4 class="footer-widget-title text--white">Our Company</h4>
                        <ul>
                            <li ><a href="{{route('site.home')}}">Home</a></li>
                            <li><a href="{{route('site.about-us')}}">About us</a></li>
                            <li><a href="{{route('site.services')}}">Services</a></li>
                            <li><a href="{{route('site.contact')}}">Contact</a></li>
                            <li><a href="{{route('site.legal')}}">Legal</a></li>                            
                            <li><a href="{{route('site.market-place')}}">Market Place</a></li>
                        </ul>
                    </div>
                    <!-- end /.footer-menu -->
                </div>
                <!-- end /.col-md-5 -->

                <div class="col-lg-4 col-md-12">
                    <div class="newsletter">
                        <h4 class="footer-widget-title text--white">Contact Info</h4>
                        <ul class="info-contact mb-5">
                            <li>
                                <span class="lnr lnr-phone info-icon"></span>
                                <a href="tel:67898752235" class="info">Phone: {{$siteSetting->phone_no}}</a>
                            </li>
                            <li>
                                <span class="lnr lnr-envelope info-icon"></span>
                                <a href="mailto:{{$siteSetting->from_email}}" class="info">{{$siteSetting->home_short_description}}</span>
                            </li>
                            <li>
                                <span class="lnr lnr-map-marker info-icon"></span>
                                <span class="info">{!!$siteSetting->address!!}</span>
                            </li>
                        </ul>

                        <!-- start .social -->
                        <div class="social social--color--filled">
                            <ul>
                                <li>
                                    <a href="{!!$siteSetting->facebook_link!!}" target="_blank">
                                        <span class="fa fa-facebook"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{!!$siteSetting->twitter_link!!}" target="_blank">
                                        <span class="fa fa-twitter"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{!!$siteSetting->googleplus_link!!}" target="_blank">
                                        <span class="fa fa-google-plus"></span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{!!$siteSetting->linkedin_link!!}" target="_blank">
                                        <span class="fa fa-linkedin"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{!!$siteSetting->instagram_link!!}" target="_blank">
                                        <span class="fa fa-instagram"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- end /.social -->
                    </div>
                    <!-- end /.newsletter -->
                </div>
                <!-- end /.col-md-4 -->
            </div>
            <!-- end /.row -->
        </div>
        <!-- end /.container -->
    </div>
    <!-- end /.footer-big -->

    <div class="mini-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright-text">
                        <p>&copy; {{date('Y')}}
                            <a href="#">MartPlace</a>. All rights reserved. Created by
                            <a href="#">TechTimes</a>
                        </p>
                    </div>

                    <div class="go_top">
                        <span class="lnr lnr-chevron-up"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>