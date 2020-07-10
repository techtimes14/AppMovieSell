@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.packageDuration.list')}}"><i class="fa fa-archive"></i> Pakage Duration List</a></li>
        <li class="active">{{ $page_title }}</li>
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
                                    'route' => ['admin.packageDuration.editsubmit', $id],
                                    'name'  => 'updatePackageDurationForm',
                                    'id'    => 'updatePackageDurationForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Package<span class="red_star">*</span></label>
                                    <select name="package_id" id="package_id" class="form-control" value="{{old('package_id')}}">
                                        <option value="">-Select-</option>
                                @if (count($packageList))
                                    @foreach ($packageList as $package)
                                        <option value="{{$package->id}}" @if($package->id == $packageDurationDetail->package_id) selected="selected" @endif>{{$package->title}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Package Period<span class="red_star">*</span></label>
                                    <select name="package_period_id" id="package_period_id" class="form-control">
                                        <option value="">-Select-</option>
                                @if (count($packagePeriodList))
                                    @foreach ($packagePeriodList as $packagePeriod)
                                        <option value="{{$packagePeriod->id}}" @if($packagePeriod->id == $packageDurationDetail->package_period_id) selected="selected" @endif>{{$packagePeriod->title}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">                                    
                                    <label for="Amount">Amount<span class="red_star">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            SAR
                                        </div>
                                        {{ Form::text('amount', AdminHelper::formatToTwoDecimalPlaces($packageDurationDetail->amount), array(
                                                                        'id' => 'amount',
                                                                        'min' => 0,
                                                                        'placeholder' => 'Amount',
                                                                        'class' => 'form-control',
                                                                        'required' => 'required'
                                                                        )) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                        {{-- Add field for product description --}}
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.packageDuration.list').'?page='.$pageNo }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
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