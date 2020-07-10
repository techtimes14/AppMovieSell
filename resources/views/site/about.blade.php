@extends('site.layouts.app', [])
  @section('content')

    <!--================================
    START ABOUT HERO AREA
    =================================-->
    <section class="about_hero bgimage">
        <div class="bg_image_holder">
            <img src="images/about_hero.jpg" alt="">
        </div>

        <div class="container content_above">
            <div class="row">
                <div class="col-md-12">
                    <div class="about_hero_contents">
                        <h1>Welcome to MartPlace!</h1>
                        <p>Lorem ipsum dolor 
                            <span>adipisicing elit</span>
                        </p>

                        <div class="about_hero_btns">
                            
                            <a href="login.html" class="btn btn--white btn--lg btn--round">Join Us Today</a>
                        </div>
                    </div>
                    <!-- end /.about_hero_contents -->
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end /.row-->
        </div>
        <!-- end /.container -->
    </section>
    <!--================================
    END ABOUT HERO AREA
    =================================-->

    <!--================================
    END ABOUT HERO AREA
    =================================-->
    <section class="about_mission">
        <div class="content_block1">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-sm-12">
                        <div class="content_area">
                            <h1 class="content_area--title">About
                                <span class="highlight">MartPlace</span>
                            </h1>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis,
                                leo quam aliquet congue this there placerat mi id nisi they interdum mollis. Praesent pharetra
                                justo ut sceleris que the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis
                                quam. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                invidunt sanctus est Lorem ipsum dolor sit amet consetetur sadipscing.</p>
                        </div>
                    </div>
                    <!-- end /.col-md-5 -->
                </div>
                <!-- end /.row -->
            </div>
            <!-- end /.container -->

            <div class="content_image bgimage">
                <div class="bg_image_holder">
                    <img src="images/ab1.jpg" alt="">
                </div>
            </div>
        </div>
        <!-- end /.about -->

        <div class="content_block2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6  offset-md-6 offset-lg-7">
                        <div class="content_area2">
                            <h1 class="content_area2--title">MartPlace
                                <span class="highlight">Mission</span>
                            </h1>
                            <p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis,
                                leo quam aliquet congue this there placerat mi id nisi they interdum mollis. Praesent pharetra
                                justo ut sceleris que the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis
                                quam. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                                invidunt sanctus est Lorem ipsum dolor sit amet consetetur sadipscing.</p>
                        </div>
                    </div>
                    <!-- end /.col-md-5 -->
                </div>
                <!-- end /.row -->
            </div>
            <!-- end /.container -->

            <div class="content_image2 bgimage">
                <div class="bg_image_holder">
                    <img src="images/ab2.jpg" alt="">
                </div>
            </div>
        </div>
        <!-- end /.mission-->
    </section>
    <!--================================
    END ABOUT HERO AREA
    =================================-->

    <!--================================
    START TIMELINE AREA
    =================================-->
   <section class="badges author-rank">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h1>Author Rank</h1>
                        <p>Laborum dolo rumes fugats untras. Etharums ser quidem rerum facilis dolores nemis omnis fugats. Lid
                            est laborum dolo rumes fugats untras.</p>
                    </div>
                    <div class="author-badges">
                        <div class="badge-single">
                            <img src="images/svg/author_rank_bronze.svg" alt="" class="svg">
                            <h3>Bronze Author</h3>
                            <p>Sold More Than $50,000 on Martplace</p>
                        </div>
                        <div class="badge-single">
                            <img src="images/svg/author_rank_golden.svg" alt="" class="svg">
                            <h3>Golden Author</h3>
                            <p>Sold More Than $100,000 on Martplace</p>
                        </div>
                        <div class="badge-single">
                            <img src="images/svg/author_rank_diamond.svg" alt="" class="svg">
                            <h3>Diamond Author</h3>
                            <p>Sold More Than $1M on Martplace</p>
                        </div>
                    </div>
                    <!-- ends: .author-rank-badges -->
                </div>
            </div>
        </div>
    </section>
    <!--================================
    END TIMELINE AREA
    =================================-->   

    <!--================================
    START CALL TO ACTION AREA
    =================================-->
    @include('site.elements.call_to_action')
    <!--================================
    END CALL TO ACTION AREA
    =================================-->
    
  @endsection