@if(check_permission('dashboard'))
    <li class="nav-item @if($active=='dashboard') active @endif">
        <a class="nav-link" href="{{route('dashboard')}}">
            Overview
        </a>
    </li>
@endif
@if(check_permission('product.list'))
    <li class="border-0 nav-item dropdown {{ in_array($active, ['product']) ? 'active' : '' }}">
        <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
           data-toggle="dropdown"
           href="#" id="navbarDropdown" role="button">
            {{Lang::get('menuadmin.product')}}
        </a>
        <div aria-labelledby="navbarDropdown" class="dropdown-menu">
            @if(check_permission('product.list'))
                <a class="dropdown-item {{ request()->routeIs(['product.list', 'product.add', 'product.edit']) ? 'active' : '' }}"
                   href="{{route('product.list')}}">{{Lang::get('menuadmin.productlist')}}</a>
            @endif
            @if(check_permission('category.list'))
                <a class="dropdown-item {{ request()->routeIs(['category.list', 'category.add', 'category.edit']) ? 'active' : '' }}"
                   href="{{route('category.list')}}">{{Lang::get('menuadmin.productcategories')}}</a>
            @endif
            @if(check_permission('capacity.list'))
                <a class="dropdown-item {{ request()->routeIs(['capacity.list', 'capacity.add', 'capacity.edit']) ? 'active' : '' }}"
                   href="{{route('capacity.list')}}">Capacities</a>
            @endif

            @if(check_permission('option.list'))
                <a class="dropdown-item {{ request()->routeIs(['option.list', 'option.add', 'option.edit']) ? 'active' : '' }}"
                   href="{{route('option.list')}}">{{Lang::get('menuadmin.productoptions')}}</a>
            @endif
            @if(check_permission('optionset.list'))
                <a class="dropdown-item {{ request()->routeIs(['optionset.list', 'optionset.add', 'optionset.edit']) ? 'active' : '' }}"
                   href="{{route('optionset.list')}}">{{Lang::get('menuadmin.productoptionsets')}}</a>
            @endif
            @if(check_permission('inventory.list'))
                <a class="dropdown-item {{ request()->routeIs(['inventory.list']) ? 'active' : '' }}"
                   href="{{route('inventory.list')}}">{{Lang::get('product.inventories')}}</a>
            @endif
        </div>
    </li>
@endif
@if(check_permission('order.list'))
    <li class="nav-item {{ in_array($active, ['order']) ? 'active' : '' }}">
        <a class="nav-link" href="{{route('order.list')}}">
            Orders
            <span
                class="badge badge-primary rounded-circle">{{ 1 }}</span>
        </a>
    </li>
@endif
@if(check_permission('banner.index'))
    <li class="nav-item {{ in_array($active, ['banner']) ? 'active' : '' }}">
        <a class="nav-link" href="{{route('banner.index')}}">
            {{Lang::get('banner.banners')}}
        </a>
    </li>
@endif
@if(check_permission('admin.list') || check_permission('role.list'))
    <li class="border-0 nav-item dropdown {{ in_array($active, ['setting']) ? 'active' : '' }}">
        <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
           data-toggle="dropdown"
           href="#" id="navbarDropdown" role="button">
            {{__('general.settings')}}
        </a>
        <div aria-labelledby="navbarDropdown" class="dropdown-menu">
            @if(check_permission('admin.list'))
                <a class="dropdown-item {{ request()->routeIs(['admin.list', 'admin.add', 'admin.edit']) ? 'active' : '' }}"
                   href="{{route('admin.list')}}">
                    {{Lang::get('admin.admins')}}
                </a>
            @endif
            @if(check_permission('role.list'))
                <a class="dropdown-item {{ request()->routeIs(['role.list', 'role.add', 'role.edit']) ? 'active' : '' }}"
                   href="{{route('role.list')}}">
                    {!!Lang::get('admin.roleandpermission')!!}
                </a>
            @endif
        </div>
    </li>
@endif
