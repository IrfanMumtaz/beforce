	<li class="{{ Request::is('admin/brands*') ? 'active' : '' }}">
    	<a href="#">
    	<i class="livicon" data-name="medal" data-size="18" data-c="#6CC66C" data-hc="#6CC66C" data-loop="true"></i>
    	<span class="fa arrow"></span>Brands</a>
	
	<ul class="sub-menu">
	<li class="{{ Request::is('admin/brands*') ? 'active' : '' }}">
	<a href="{!! route('admin.brands.index') !!}"><i class="fa fa-angle-double-right"></i>Manage Brand</a>
	</li>

	<li class="{{ Request::is('admin/brands*') ? 'active' : '' }}">
	<a href="{!! route('admin.brands.create') !!}"><i class="fa fa-angle-double-right"></i>Add Brand</a>
	</li>
	
	</ul>		
	</li>

	<li class="{{ Request::is('admin/stores*') ? 'active' : '' }}">
    	<a href="#">
	<i class="livicon" data-name="users" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
	<span class="fa arrow"></span>Stores</a>
	
	<ul class="sub-menu">	

	<li class="{{ Request::is('admin/stores*') ? 'active' : '' }}">
    	<a href="{!! route('admin.stores.index') !!}"><i class="fa fa-angle-double-right"></i>Manage Stores</a>
	</li>

	<li class="{{ Request::is('admin/stores*') ? 'active' : '' }}">
    	<a href="{!! route('admin.stores.app') !!}"><i class="fa fa-angle-double-right"></i>Manage App Stores</a>
	</li>

	<li class="{{ Request::is('admin/stores*') ? 'active' : '' }}">
    	<a href="{!! route('admin.stores.create') !!}"><i class="fa fa-angle-double-right"></i>Add Stores</a>
	</li>

	</ul>

	</li>

	<li class="{{ Request::is('admin/employees*') ? 'active' : '' }}">
    	<a href="#">
    	<i class="livicon" data-name="user" data-size="18" data-c="#6CC66C" data-hc="#6CC66C" data-loop="true"></i>
	<span class="fa arrow"></span>Employees</a>

	<ul class="sub-menu">	

	<li class="{{ Request::is('admin/employees*') ? 'active' : '' }}">
    	<a href="{!! route('admin.employees.index') !!}">
    	<i class="fa fa-angle-double-right"></i>Manage Employees</a>
	</li>

	<li class="{{ Request::is('admin/employees*') ? 'active' : '' }}">
    	<a href="{!! route('admin.employees.create') !!}">
	<i class="fa fa-angle-double-right"></i>Add Employees</a>
	</li>

	</ul>

	</li>

<li class="{{ Request::is('admin/tasks*') ? 'active' : '' }}">
    <a href="{!! route('admin.tasks.index') !!}">
<i class="livicon" data-c="#EF6F6C" data-hc="#EF6F6C" data-name="list-ul" data-size="18"
               data-loop="true"></i>
               Tasks
    </a>
</li>


<li>
    <a href="{!! route('admin.interceptions.index') !!}">
 <i class="livicon" data-name="lab" data-c="#EF6F6C" data-hc="#EF6F6C" data-size="18"
               data-loop="true"></i>
               Interceptions
                
</a>
</li>

	<li class="{{ Request::is('admin/sKUS*') ? 'active' : '' }}">
    	<a href="#">
	<i class="livicon" data-name="help" data-size="18" data-c="#1DA1F2" data-hc="#1DA1F2" data-loop="true"></i>SKUS<span class="fa arrow"></span>    </a>

	<ul class="sub-menu">
	<li class="{{ Request::is('admin/categories*') ? 'active' : '' }}">
	<a href="{!! route('admin.categories.index') !!}">
	<i class="fa fa-angle-double-right"></i>Manage Categories</a>
	</li>

	<li class="{{ Request::is('admin/categories*') ? 'active' : '' }}">
	<a href="{!! route('admin.categories.create') !!}">
	 <i class="fa fa-angle-double-right"></i>Add Categories</a>
	</li>

	<li class="{{ Request::is('admin/sKUS*') ? 'active' : '' }}">
    	<a href="{!! route('admin.sKUS.index') !!}">
     	<i class="fa fa-angle-double-right"></i>Manage SKUS</a>
	</li>

	<li class="{{ Request::is('admin/sKUS*') ? 'active' : '' }}">
    	<a href="{!! route('admin.sKUS.create') !!}">
     	<i class="fa fa-angle-double-right"></i>Add SKUS</a>
	</li>

	</ul>
	</li>

	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="#">
	<i class="livicon" data-name="flag" data-c="#418bca" data-hc="#418bca" data-size="18" data-loop="true"></i>Assets
	<span class="fa arrow"></span></a>

	<ul class="sub-menu">

	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.assets.index') !!}">
     	<i class="fa fa-angle-double-right"></i>Manage Assets</a>
	</li>


	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.assets.create') !!}">
     	<i class="fa fa-angle-double-right"></i>Add Assets</a>
	</li>

	</ul>
	</li>

	
	<li class="{{ Request::is('admin/reports*') ? 'active' : '' }}">
        <a href="#"><i class="livicon" data-name="barchart" data-size="18" data-c="#6CC66C" data-hc="#6CC66C" data-loop="true"></i>Reports<span class="fa arrow"></span></a>

	<ul class="sub-menu">
	<li class="{{ Request::is('admin/reports/attendance*') ? 'active' : '' }}">
  	<a href="{!! route('admin.reports.attendance_report') !!}">
     	<i class="fa fa-angle-double-right"></i>Attendances</a>
	</li>

	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.reports.daily_sale_report') !!}">
     	<i class="fa fa-angle-double-right"></i>Daily Sales Report</a>
	</li>
	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.brandShareIndex') !!}">
     	<i class="fa fa-angle-double-right"></i>Brand Share Report</a>
	</li>
	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.genderWise') !!}">
     	<i class="fa fa-angle-double-right"></i>Customer Survey Report</a>
	</li>
	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.break') !!}">
     	<i class="fa fa-angle-double-right"></i>Break Report</a>
	</li>
	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.interception') !!}">
     	<i class="fa fa-angle-double-right"></i>Interception Report</a>
	</li>
	<li class="{{ Request::is('admin/assets*') ? 'active' : '' }}">
  	<a href="{!! route('admin.outOfStock') !!}">
     	<i class="fa fa-angle-double-right"></i>Out Of Stock Report</a>
	</li>
	</ul>
</li>


