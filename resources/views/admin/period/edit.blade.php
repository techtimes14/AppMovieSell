@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.period.list')}}"><i class="fa fa-clock-o"></i> Pakage Period List</a></li>
        <li class="active">{{ $data['page_title'] }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                
                @include('admin.elements.notification')

                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.period.editsubmit', $details["id"]],
                                    'name'  => 'updatePeriodForm',
                                    'id'    => 'updatePeriodForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Title">Title<span class="red_star">*</span></label>
                                    {{ Form::text('title',$details['title'], array(
                                                                'id' => 'title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Title',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Title">Period<span class="red_star">*</span></label>
                                    {{ Form::text('period',  $details['period'], array(
                                                                'id' => 'period',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Period in month',
                                                                'required' => 'required' )) }}
                                    <small>Number of months for eg. for 3months put 3, 1year put 12</small>
                                    <div id="period_error_message"></div>
                                </div>                                
                            </div>                            
                        </div>                        
                    </div> 
                    
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.period.list').'?page='.$data['pageNo'] }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection