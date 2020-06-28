@php $getAllRoles = Helper::getRolePermissionPages(); @endphp
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ asset('js/admin/dist/img/avatar5.png') }}" class="img-circle" alt="{{Auth::guard('admin')->user()->full_name}}">
        </div>
        <div class="pull-left info">
            <p>{{Auth::guard('admin')->user()->full_name}}</p>
        </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><strong>MAIN MENU</strong></li>
        <li @if (Route::current()->getName() == 'admin.dashboard')class="active" @endif>
            <a href="{{route('admin.dashboard')}}">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
        </li>

    <!-- User management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.user.list',$getAllRoles) || in_array('admin.user.add',$getAllRoles) || (in_array('admin.user.list',$getAllRoles) && in_array('admin.user.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.user.list' || Route::current()->getName() == 'admin.user.add' || Route::current()->getName() == 'admin.user.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-users" aria-hidden="true"></i>
                <span>User Management</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>  
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.user.list' || Route::current()->getName() == 'admin.user.add' || Route::current()->getName() == 'admin.user.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.user.list',$getAllRoles) || (in_array('admin.user.list',$getAllRoles) && in_array('admin.user.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.user.list')class="active" @endif><a href="{{ route('admin.user.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.user.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.user.add')class="active" @endif><a href="{{ route('admin.user.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- User management End -->

    <!-- Tag management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.tag.list',$getAllRoles) || in_array('admin.tag.add',$getAllRoles) || (in_array('admin.tag.list',$getAllRoles) && in_array('admin.tag.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.tag.list' || Route::current()->getName() == 'admin.tag.add' || Route::current()->getName() == 'admin.tag.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-tags" aria-hidden="true"></i>
                <span>Tag Management</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>  
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.tag.list' || Route::current()->getName() == 'admin.tag.add' || Route::current()->getName() == 'admin.tag.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.tag.list',$getAllRoles) || (in_array('admin.tag.list',$getAllRoles) && in_array('admin.tag.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.tag.list')class="active" @endif><a href="{{ route('admin.tag.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.tag.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.tag.add')class="active" @endif><a href="{{ route('admin.tag.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Tag management End -->

    <!-- Category management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.category.list',$getAllRoles) || in_array('admin.category.add',$getAllRoles) || (in_array('admin.category.list',$getAllRoles) && in_array('admin.category.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.category.list' || Route::current()->getName() == 'admin.category.add' || Route::current()->getName() == 'admin.category.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-list-alt" aria-hidden="true"></i>
                <span>Category Management</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>  
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.category.list' || Route::current()->getName() == 'admin.category.add' || Route::current()->getName() == 'admin.category.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.category.list',$getAllRoles) || (in_array('admin.category.list',$getAllRoles) && in_array('admin.category.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.category.list')class="active" @endif><a href="{{ route('admin.category.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.category.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.category.add')class="active" @endif><a href="{{ route('admin.category.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Category management End -->

    <!-- Brand management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.brand.list',$getAllRoles) || in_array('admin.brand.add',$getAllRoles) || (in_array('admin.brand.list',$getAllRoles) && in_array('admin.brand.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.brand.list' || Route::current()->getName() == 'admin.brand.add' || Route::current()->getName() == 'admin.brand.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-bitcoin" aria-hidden="true"></i>
                <span>Brand Management</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>  
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.brand.list' || Route::current()->getName() == 'admin.brand.add' || Route::current()->getName() == 'admin.brand.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.brand.list',$getAllRoles) || (in_array('admin.brand.list',$getAllRoles) && in_array('admin.brand.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.brand.list')class="active" @endif><a href="{{ route('admin.brand.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.brand.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.brand.add')class="active" @endif><a href="{{ route('admin.brand.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Brand management End -->

    <!-- Video management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.video.list',$getAllRoles) || in_array('admin.video.add',$getAllRoles) || (in_array('admin.video.list',$getAllRoles) && in_array('admin.video.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.video.list' || Route::current()->getName() == 'admin.video.add' || Route::current()->getName() == 'admin.video.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-video-camera" aria-hidden="true"></i>
                <span>Video Management</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>  
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.video.list' || Route::current()->getName() == 'admin.video.add' || Route::current()->getName() == 'admin.video.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.video.list',$getAllRoles) || (in_array('admin.video.list',$getAllRoles) && in_array('admin.video.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.video.list')class="active" @endif><a href="{{ route('admin.video.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.video.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.video.add')class="active" @endif><a href="{{ route('admin.video.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- video management End -->

    <!-- Banner management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.banner.list',$getAllRoles) || in_array('admin.banner.add',$getAllRoles) || (in_array('admin.banner.list',$getAllRoles) && in_array('admin.banner.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.banner.list' || Route::current()->getName() == 'admin.banner.add' || Route::current()->getName() == 'admin.banner.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-picture-o" aria-hidden="true"></i>
                <span>Banner Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.banner.list' || Route::current()->getName() == 'admin.banner.add' || Route::current()->getName() == 'admin.banner.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.banner.list',$getAllRoles) || (in_array('admin.banner.list',$getAllRoles) && in_array('admin.banner.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.banner.list')class="active" @endif><a href="{{ route('admin.banner.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.banner.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.banner.add')class="active" @endif><a href="{{ route('admin.banner.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Banner management End -->

    <!-- Website management start -->    
    @if ((Auth::guard('admin')->user()->role_id==1))
        <li class="treeview @if (Route::current()->getName() == 'admin.CMS.list' || Route::current()->getName() == 'admin.CMS.edit' || Route::current()->getName() == 'admin.site-settings') menu-open @endif">
            <a href="#">
                <i class="fa fa-wrench" aria-hidden="true"></i>
                <span>Website Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.CMS.list' || Route::current()->getName() == 'admin.CMS.edit' || Route::current()->getName() == 'admin.site-settings') style="display: block;" @endif>
                
                <!-- Site settings start -->
                <li @if (Route::current()->getName() == 'admin.site-settings')class="active" @endif>
                    <a href="{{ route('admin.site-settings') }}">
                        <i class="fa fa-cog" aria-hidden="true"></i> <span>Site Settings</span>
                    </a>
                </li>
                
                <!-- Cms start -->
                <li @if (Route::current()->getName() == 'admin.CMS.list' || Route::current()->getName() == 'admin.CMS.edit')class="active" @endif>
                    <a href="{{ route('admin.CMS.list') }}">
                        <i class="fa fa-database" aria-hidden="true"></i> <span>CMS</span>
                    </a>
                </li>                
            </ul>
        </li>
    @endif   
    <!-- Website management end -->
    
    </ul>
</section>
<!-- /.sidebar -->