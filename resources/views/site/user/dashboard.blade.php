@extends('site.layouts.app', [])
  @section('content')
  
    <!--================================
        START BREADCRUMB AREA
    =================================-->
    <!--================================
        END BREADCRUMB AREA
    =================================-->

     <!--================================
        START BREADCRUMB AREA
    =================================-->
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="breadcrumb">
                        <ul>
                            <li>
                                <a href="index.html">Home</a>
                            </li>
                            <li class="active">
                                <a href="#">{{Auth::user()->first_name}}</a>
                            </li>
                        </ul>
                    </div>
                    <h1 class="page-title">Affilated Dashboard</h1>
                </div>
                <div class="col-md-6">
                    <div class="invitation_sec">
                        <h2 class="page-title">Invitation Link</h2>
                        <input readonly type="text" value="{{route('site.users.invitation',Auth::user()->referral_code)}}" id="promoCopy">
                        <button><span></span><i class="fa fa-clipboard" aria-hidden="true"></i></button>
                    </div>
                    
                    
                </div>
                <!-- end /.col-md-12 -->
            </div>
            <!-- end /.row -->
        </div>
        <!-- end /.container -->
    </section>
    <!--================================
        END BREADCRUMB AREA
    =================================-->

    <!--================================
            START SIGNUP AREA
    =================================-->
    <section class="signup_area section--padding2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="affilated_dashboard">
                        <div class="estimate_section">
                            <div class="estimate_earning">
                                <h3>Estimated Earnings</h3>
                                <table>
                                    <tr>
                                        <td>$0.00 <span>Today so far</span></td>
                                        <td>$0.00 <span>Yesterday</span></td>
                                        <td>$0.00 <span>This month so far</span></td>
                                        <td>$0.00 <span>Last Month</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="estimate_earning">
                                <h3>Payments</h3>
                                <table>
                                    <tr>
                                        <td>$265.23 <span class="date">July 15, 2020</span> <span>Next Payment</span></td>
                                        <td>$230.30 <span class="date">June 15, 2020</span> <span>Last Payment</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div>
                            <div id="curve_chart"></div>
                        </div>
                    </div>
                </div>
                <!-- end .col-md-6 -->
            </div>
            <!-- end .row -->
        </div>
        <!-- end .container -->
    </section>
    <!--================================
            END SIGNUP AREA
    =================================-->
    

    @endsection