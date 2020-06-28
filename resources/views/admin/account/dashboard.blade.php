@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Dashboard of <strong> {{ Helper::getAppName() }} </strong></h1>
  <ol class="breadcrumb">
      <li><a><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="">
                @include('admin.elements.notification')                
            </div>
        </div>
    </div>
    <!-- Info boxes -->
    {{-- show admin dashboard 1st row start --}}
    <div class="row">        
    </div>
    {{-- show admin dashboard 1st row end --}}

</section>
<!-- /.content -->

@endsection


