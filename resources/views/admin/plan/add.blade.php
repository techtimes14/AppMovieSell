@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.plan.list')}}"><i class="fa fa-dropbox"></i> Plan List</a></li>
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
                                    'route' => ['admin.plan.addsubmit'],
                                    'name'  => 'addPlanForm',
                                    'id'    => 'addPlanForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title<span class="red_star">*</span></label>
                                    {{ Form::text('title', null, array(
                                                                'id' => 'title',
                                                                'placeholder' => 'Title',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has_attribute_show">
                                    <label for="title">Features<span class="red_star">*</span></label>
                                    <div class="addField">
                                        <div class="row">
                                            <div class="col-md-10">
                                                {!! Form::text('features[0]', null, array('required', 'class'=>'form-control', 'placeholder' => '', 'id' => 'features0')) !!}
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-success add-more" id="addrow" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.plan.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<script type="text/javascript">
$(function () {
    // Feature section start //
    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        var cols = '';
        var newRow = $('<div class="row" style="margin-top: 10px;">');
        cols += '<div class="col-md-10"><input required class="form-control" placeholder="" id="features'+counter+'" name="features['+counter+']" type="text"></div>';
        cols += '<div class="col-sm-2"><a class="deleteRow btn btn-danger ibtnDel" href="javascript: void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a></div>';

        newRow.append(cols);
        $(".addField").append(newRow);
    });
    $(".row").on("click", ".ibtnDel", function (event) {
        $(this).closest(".row").remove();
        counter--;
    });
    // Feature section end //
});
</script>

@endsection