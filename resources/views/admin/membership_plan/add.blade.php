@extends('admin.layouts.app', ['title' => $panel_title])
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.membershipPlan.list')}}"><i class="fa fa-tasks"></i> Membership Plan List</a></li>
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
                                    'route' => ['admin.membershipPlan.addsubmit'],
                                    'name'  => 'addMembershipPlanForm',
                                    'id'    => 'addMembershipPlanForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Plan<span class="red_star">*</span></label>
                                    <select name="plan_id" id="plan_id" class="form-control" value="{{old('plan_id')}}">
                                        <option value="">-Select-</option>
                                @if (count($planList))
                                    @foreach ($planList as $plan)
                                        <option value="{{$plan->id}}" @if($plan->id == old('plan_id') ) selected="selected" @endif>{{$plan->title}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Period<span class="red_star">*</span></label>
                                    <select name="period_id" id="period_id" class="form-control">
                                        <option value="">-Select-</option>
                                @if (count($periodList))
                                    @foreach ($periodList as $period)
                                        <option value="{{$period->id}}" @if($period->id == old('period_id') ) selected="selected" @endif>{{$period->title}}</option>
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
                                            $
                                        </div>
                                        {{ Form::text('amount', null, array(
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
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.membershipPlan.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
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