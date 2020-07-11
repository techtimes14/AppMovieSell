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

    <!-- About Us management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.aboutus.list',$getAllRoles) || in_array('admin.aboutus.add',$getAllRoles) || (in_array('admin.aboutus.list',$getAllRoles) && in_array('admin.aboutus.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.aboutus.list' || Route::current()->getName() == 'admin.aboutus.add' || Route::current()->getName() == 'admin.aboutus.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-book" aria-hidden="true"></i>
                <span>About Us Management</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>  
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.aboutus.list' || Route::current()->getName() == 'admin.aboutus.add' || Route::current()->getName() == 'admin.aboutus.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.aboutus.list',$getAllRoles) || (in_array('admin.aboutus.list',$getAllRoles) && in_array('admin.aboutus.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.aboutus.list')class="active" @endif><a href="{{ route('admin.aboutus.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.aboutus.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.aboutus.add')class="active" @endif><a href="{{ route('admin.aboutus.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- About Us management End -->

     <!-- Category management Start -->
     @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.category.list',$getAllRoles) || in_array('admin.category.add',$getAllRoles) || (in_array('admin.category.list',$getAllRoles) && in_array('admin.category.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.category.list' || Route::current()->getName() == 'admin.category.add' || Route::current()->getName() == 'admin.category.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-book" aria-hidden="true"></i>
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

    <!-- Product management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.product.list',$getAllRoles) || in_array('admin.product.add',$getAllRoles) || (in_array('admin.product.list',$getAllRoles) && in_array('admin.product.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.product.list' || Route::current()->getName() == 'admin.product.add' || Route::current()->getName() == 'admin.product.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-dropbox" aria-hidden="true"></i>
                <span>Product Management</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>  
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.product.list' || Route::current()->getName() == 'admin.product.add' || Route::current()->getName() == 'admin.product.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.product.list',$getAllRoles) || (in_array('admin.product.list',$getAllRoles) && in_array('admin.product.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.product.list')class="active" @endif><a href="{{ route('admin.product.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.product.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.product.add')class="active" @endif><a href="{{ route('admin.product.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Product management End -->
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

    <!-- Service management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.service.list',$getAllRoles) || in_array('admin.service.add',$getAllRoles) || (in_array('admin.service.list',$getAllRoles) && in_array('admin.service.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.service.list' || Route::current()->getName() == 'admin.service.add' || Route::current()->getName() == 'admin.service.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-cubes" aria-hidden="true"></i>
                <span> Service Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.service.list' || Route::current()->getName() == 'admin.service.add' || Route::current()->getName() == 'admin.service.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.service.list',$getAllRoles) || (in_array('admin.service.list',$getAllRoles) && in_array('admin.service.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.service.list')class="active" @endif><a href="{{ route('admin.service.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.service.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.service.add')class="active" @endif><a href="{{ route('admin.service.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Service management End -->

    <!-- Contactwidget management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.contactwidget.list',$getAllRoles) || in_array('admin.contactwidget.add',$getAllRoles) || (in_array('admin.contactwidget.list',$getAllRoles) && in_array('admin.contactwidget.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.contactwidget.list' || Route::current()->getName() == 'admin.contactwidget.add' || Route::current()->getName() == 'admin.contactwidget.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-address-card" aria-hidden="true"></i> 
                <span> Contact Widget Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.contactwidget.list' || Route::current()->getName() == 'admin.contactwidget.add' || Route::current()->getName() == 'admin.contactwidget.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.contactwidget.list',$getAllRoles) || (in_array('admin.contactwidget.list',$getAllRoles) && in_array('admin.contactwidget.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.contactwidget.list')class="active" @endif><a href="{{ route('admin.contactwidget.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.contactwidget.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.contactwidget.add')class="active" @endif><a href="{{ route('admin.contactwidget.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Contactwidget management End -->

    <!-- Period management Start -->
    {{-- @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.period.list',$getAllRoles) || in_array('admin.period.add',$getAllRoles) || (in_array('admin.period.list',$getAllRoles) && in_array('admin.period.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.period.list' || Route::current()->getName() == 'admin.period.add' || Route::current()->getName() == 'admin.period.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <span>Period Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.period.list' || Route::current()->getName() == 'admin.period.add' || Route::current()->getName() == 'admin.period.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.period.list',$getAllRoles) || (in_array('admin.period.list',$getAllRoles) && in_array('admin.period.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.period.list')class="active" @endif><a href="{{ route('admin.period.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.period.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.period.add')class="active" @endif><a href="{{ route('admin.period.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif --}}
    <!-- Period management End -->

    <!-- Plan management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.plan.list',$getAllRoles) || in_array('admin.plan.add',$getAllRoles) || (in_array('admin.plan.list',$getAllRoles) && in_array('admin.plan.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.plan.list' || Route::current()->getName() == 'admin.plan.add' || Route::current()->getName() == 'admin.plan.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-question-circle" aria-hidden="true"></i>
                <span> Plan Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.plan.list' || Route::current()->getName() == 'admin.plan.add' || Route::current()->getName() == 'admin.plan.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.plan.list',$getAllRoles) || (in_array('admin.plan.list',$getAllRoles) && in_array('admin.plan.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.plan.list')class="active" @endif><a href="{{ route('admin.plan.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.plan.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.plan.add')class="active" @endif><a href="{{ route('admin.plan.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Plan management End -->

    <!-- Membership Plan management Start -->
    @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.plan.list',$getAllRoles) || in_array('admin.plan.add',$getAllRoles) || (in_array('admin.membershipPlan.list',$getAllRoles) && in_array('admin.membershipPlan.edit',$getAllRoles))) )
        <li class="treeview @if (Route::current()->getName() == 'admin.membershipPlan.list' || Route::current()->getName() == 'admin.membershipPlan.add' || Route::current()->getName() == 'admin.membershipPlan.edit')menu-open @endif">
            <a href="#">
                <i class="fa fa-tasks" aria-hidden="true"></i>
                <span>Membership Plan Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu" @if (Route::current()->getName() == 'admin.membershipPlan.list' || Route::current()->getName() == 'admin.membershipPlan.add' || Route::current()->getName() == 'admin.membershipPlan.edit')style="display: block;" @endif>
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.membershipPlan.list',$getAllRoles) || (in_array('admin.membershipPlan.list',$getAllRoles) && in_array('admin.membershipPlan.edit',$getAllRoles))) )
                    <li @if (Route::current()->getName() == 'admin.membershipPlan.list')class="active" @endif><a href="{{ route('admin.membershipPlan.list') }}"><i class="fa fa-list"></i> List</a></li>
                @endif
                @if ( (Auth::guard('admin')->user()->role_id==1) || (in_array('admin.membershipPlan.add',$getAllRoles)) )
                    <li @if (Route::current()->getName() == 'admin.membershipPlan.add')class="active" @endif><a href="{{ route('admin.membershipPlan.add') }}"><i class="fa fa-plus-circle"></i> Add</a></li>
                @endif
            </ul>
        </li>
    @endif
    <!-- Membership  Plan management End -->
    
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