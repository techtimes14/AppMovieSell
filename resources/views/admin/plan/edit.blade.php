@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.plan.list')}}"><i class="fa fa-question-circle"></i> Plan List</a></li>
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
                                    'route' => ['admin.plan.editsubmit', $details["id"]],
                                    'name'  => 'updatePlanForm',
                                    'id'    => 'updatePlanForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Title">Title<span class="red_star">*</span></label>
                                    {{ Form::text('title',$details['title'], array(
                                                                'id' => 'title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Title',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Features<span class="red_star">*</span></label>
                                    @php
                                    if (count($details->planFeatures) > 0) {
                                        $countPlanFeatures = count($details->planFeatures);
                                    } else {
                                        $countPlanFeatures = 1;
                                    }
                                    @endphp
                                    
                                    <input type="hidden" id="feature_count" value="{{$countPlanFeatures}}">

                                    <div class="addField">
                                @php
                                $k = 1; $m = 0;
                                if(count($details->planFeatures) > 0) {
                                    foreach($details->planFeatures as $key => $val) {
                                @endphp
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-md-10">                                            
                                                {!! Form::text('features['.$key.']', $val->feature, array('required', 'class'=>'form-control', 'placeholder' => 'Title', 'id' => 'features'.$m)) !!}
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-danger feature_move_to_trash" data-planid="{{$details['id']}}" data-featureid="{{$val->id}}" type="button"><i class="fa fa-trash"></i></button>
                                            @if ($k == 1)
                                                <button class="btn btn-success add-more" id="addrow" type="button"><i class="fa fa-plus"></i></button>
                                            @endif
                                            </div>
                                        </div>
                                @php
                                    $k++; $m++;
                                    }
                                } else {
                            @endphp

                                    <div class="row">
                                        <div class="col-md-10">
                                            {!! Form::text('features[0]', null, array('class'=>'form-control', 'placeholder' => '', 'id' => 'features0')) !!}
                                        </div>
                                        <div class="col-md-2">
                                            <label for="title">&nbsp;</label><br />
                                            <button class="btn btn-success add-more" id="addrow" type="button"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                            @php
                                }
                            @endphp
                                    </div>
                                </div>                                
                            </div>
                        </div>                        
                    </div> 
                    
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.plan.list').'?page='.$data['pageNo'] }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
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
    // Features section start //
    var counter = $('#feature_count').val();
    $("#addrow").on("click", function () {        
        var cols = '';
        var newRow = $('<div class="row" style="margin-top: 10px;">');
        cols += '<div class="col-md-10"><input required class="form-control" placeholder="" id="features'+counter+'" name="features['+counter+']" type="text"></div>';
        cols += '<div class="col-sm-2"><a class="deleteRow btn btn-danger ibtnDel" href="javascript: void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a></div>';

        newRow.append(cols);
        $(".addField").append(newRow);

        counter++;        
    });
    $(".row").on("click", ".ibtnDel", function (event) {
        $(this).closest(".row").remove();
        counter--;
    });
    // Features section end //
});
    
// Delete feature start //
$(document).on('click', '.feature_move_to_trash', function() {
    var featureId = $(this).data('featureid');
    var planId = $(this).data('planid');
    
    Swal.fire({
        title: 'Warning!',
        text: 'Are you sure you want to delete?',
        icon: 'warning',
        allowOutsideClick: false,
        // confirmButtonColor: '#d6f55b',
        // cancelButtonColor: '#141516',
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.value) {
            var deleteFeatureUrl = '{{ route("admin.plan.delete-plan-feature") }}';
            $('#whole-area').show();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: deleteFeatureUrl,
                method: 'POST',
                data: {
                    'feature_id': featureId,
                    'plan_id': planId,
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#whole-area').hide(); //Showing loader
                    
                    if(response.has_error == 0) {
                        Swal.fire({
                            title: "Success",
                            text: response.message,
                            icon: "success",
                            showCancelButton: false,
                            // confirmButtonColor: '#d6f55b',
                            confirmButtonText: "Ok",
                            cancelButtonText: "",
                            closeOnConfirm: true,
                            closeOnCancel: false
                        }).then((result) => {
                            window.location.reload();  
                        });                            
                    } else{
                        Swal.fire({
                            title: "Error",
                            text: response.message,
                            icon: "error",
                            showCancelButton: false,
                            // confirmButtonColor: '#d6f55b',
                            confirmButtonText: "Ok",
                            cancelButtonText: "",
                            closeOnConfirm: true,
                            closeOnCancel: false
                        });
                    }
                }
            });
        }
    });

});
// Delete feature end //
</script>

@endsection