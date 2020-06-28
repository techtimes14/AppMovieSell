@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ $page_title }}</h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <!-- Search section -->
                    <div class="box-tools">
                        <div class="input-group input-group-sm search_width">
                        {{ Form::open(array(
                                        'method' => 'GET',
                                        'class' => 'display_table',
                                        'route' =>  ['admin.tag.list'],
                                        'id' => 'searchTagForm',
                                        'novalidate' => true)) }}
                          {{ Form::text('searchText', (isset($searchText)) ? $searchText:null, array(
                                        'id' => 'searchText',
                                        'placeholder' => 'Search by title',
                                        'class' => 'form-control pull-right')) }}
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                <a href="{{ route('admin.tag.list') }}" class="btn btn-default"><i class="fa fa-refresh"></i></a>
                            </div>
                        {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <!-- Search Section End -->

                @include('admin.elements.notification')

                <div class="box-body table-responsive">
                  <table class="table table-bordered">
                      <tr>
                          <th>Title</th>
                          <th>Status</th>
                          <th class="action_width text_align_center">Action</th>
                      </tr>
                    @if(count($list) > 0)
                      @foreach ($list as $row)
                      <tr>                            
                        <td>{{ $row->title }}</td>
                          <td>
                            <span class="label @if($row->status == 1) label-success @else label-danger @endif">
                            @if($row['status'] == '1')
                                <a class="color_white" href="javascript:void(0)" onclick="return sweetalertMessageRender(this, 'Are you sure you want to inactive?',  'warning', true)" data-href="{{ route('admin.tag.change-status', [$row->id]) }}" title="Status">
                                    Active
                                </a>
                            @else
                                <a class="color_white" href="javascript:void(0)" onclick="return sweetalertMessageRender(this, 'Are you sure you want to active?',  'warning',  true)" data-href="{{ route('admin.tag.change-status', [$row->id]) }}" title="Status">
                                    Inactive
                                </a>
                            @endif
                            </span>
                          </td>
                          <td class="text_align_center">
                            <a href="{{ route('admin.tag.edit', [$row->id]) }}" title="Edit" class="btn btn-info btn-sm">
                              <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>
                            &nbsp;
                            <a onclick="return sweetalertMessageRender(this, 'Are you sure you want to delete?', 'error',  true)" href="javascript:void(0)" title="Delete" data-href="{{ route('admin.tag.delete', [$row->id]) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a> 
                          </td>                            
                      </tr>
                      @endforeach
                    @else
                      <tr>
                        <td colspan="4">No records found</td>
                      </tr>
                    @endif
                  </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                @if(count($list)>0)
                  <div class="row">
                    <div class="col-sm-3">
                      <div class="pull-left page_of_margin">
                        {{ AdminHelper::paginationMessage($list) }}
                      </div>
                    </div>
                    <div class="col-sm-9">
                      <div class="no-margin pull-right">                      
                        {{ $list->appends(request()->input())->links() }}
                      </div>
                    </div>
                  </div>
                @endif
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
<!-- /.content -->

@endsection