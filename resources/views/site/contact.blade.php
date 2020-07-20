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
        START AFFILIATE AREA
    =================================-->
    <section class="contact-area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <!-- start col-md-12 -->
                        <div class="col-md-12">
                            <div class="section-title">
                                <h1>{!! $cmsData->name !!}</h1>
                                <p>{!! $cmsData->description !!}</p>
                            </div>
                        </div>
                        <!-- end /.col-md-12 -->
                    </div>
                    <!-- end /.row -->
                   
                    <div class="row">
                        @if($contactWidgetData->count() > 0)
                        @foreach($contactWidgetData as $contactWidget)
                        <div class="col-lg-4 col-md-6">
                            <div class="contact_tile">
                                <span class="{{$contactWidget->icon_class}}"></span>
                                <h4 class="tiles__title">{{$contactWidget->title}}</h4>
                                <div class="tiles__content">
                                    {!! $contactWidget->description !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        <!-- end /.col-lg-4 col-md-6 -->

                        

					<div class="col-md-12">
						<div class="contact_form cardify">
							<div class="contact_form__title">
								<h3>{{$cmsData->meta_description}}</h3>
							</div>

							<div class="row">
								<div class="col-md-8 offset-md-2">
								@include('admin.elements.notification')
									{{ Form::open(array(
														'method'=> 'POST',
														'class' => '',
														'route' => ['site.contact'],
														'name'  => 'contactusForm',
														'id'    => 'contactusForm',
														'files' => true,
														'autocomplete' => false,
														'novalidate' => true)) }}
									<div class="contact_form--wrapper">
									
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
													{{ Form::text('first_name', null, array(
                                                                'id' => 'first_name',
                                                                'placeholder' => 'First Name',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
													{{ Form::text('last_name', null, array(
                                                                'id' => 'last_name',
                                                                'placeholder' => 'Last Name',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
													</div>
												</div>
											</div>

											<div class="row">
													<div class="col-md-6">
														<div class="form-group">
														{{ Form::text('email', null, array(
																	'id' => 'email',
																	'placeholder' => 'Email',
																	'class' => 'form-control',
																	'required' => 'required'
																	)) }}
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
														{{ Form::text('phone_number', null, array(
																	'id' => 'phone_number',
																	'placeholder' => 'Phonr number',
																	'class' => 'form-control',
																	'required' => 'required'
																	)) }}
														</div>
													</div>
											</div>

											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														{{ Form::textarea('subject', null, array(
																					'id'=>'subject',
																					'placeholder' => 'Message',
																					'class' => 'form-control',
																					'rows' => 4,
																					'required' => 'required' )) }}
													</div>
												</div>
                        					</div>

											<div class="sub_btn">
												<button type="submit" class="btn btn--round btn--default">Send Request</button>
											</div>
										
									</div>
									{{ Form::close() }}
								</div>
								<!-- end /.col-md-8 -->
							</div>
							<!-- end /.row -->
						</div>
						<!-- end /.contact_form -->
					</div>
                        <!-- end /.col-md-12 -->
                    </div>
                    
                    <!-- end /.row -->
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
            START
    =================================-->
    <div id="map"></div>
    <!-- end /.map -->
    <!--================================
            END FAQ AREA
    =================================-->
    
  @endsection