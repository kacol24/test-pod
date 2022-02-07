@if(check_permission('dashboard'))
    <li class="nav-item @if($active=='dashboard') active @endif">
        <a class="nav-link" href="{{route('dashboard')}}">
            Overview
        </a>
    </li>
@endif
@if(check_permission('order.list'))
    @if(check_permission('banktransfer.list') && config('payment.bank_transfer.active') && check_permission('payment.list'))
<li class="border-0 nav-item dropdown {{ in_array($active, ['order','payment']) ? 'active' : '' }}">
    <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
       data-toggle="dropdown"
       href="#" id="navbarDropdown" role="button">
        {{Lang::get('menuadmin.order')}}
    </a>
    <div aria-labelledby="navbarDropdown" class="dropdown-menu">
        <a class="dropdown-item" href="{{route('order.list')}}">
            {{Lang::get('menuadmin.order')}}
            <span class="badge badge-primary rounded-circle">{{Core\Models\Order\Order::where('status_id',2)->count()}}</span>
        </a>
        <a class="dropdown-item" href="{{route('payment.list')}}" class="nav-link">
            {{Lang::get('payment.payments')}}
        </a>
    </div>
    @else
<li class="nav-item {{ in_array($active, ['order']) ? 'active' : '' }}">
    <a class="nav-link" href="{{route('order.list')}}">
        {{Lang::get('menuadmin.order')}}
        <span class="badge badge-primary rounded-circle">{{Core\Models\Order\Order::where('status_id',2)->count()}}</span>
    </a>
</li>
    @endif
@endif

@if(check_permission('business.list'))
<li class="nav-item {{ in_array($active, ['business']) ? 'active' : '' }}">
    <a class="nav-link" href="{{route('business.list')}}">
        Business
    </a>
</li>
@endif
@if(check_permission('treasure_arise.list'))
    <li class="nav-item {{ in_array($active, ['treasure_arise']) ? 'active' : '' }}">
        <a class="nav-link" href="{{route('treasure_arise.list')}}">
            Treasures Arise
        </a>
    </li>
@endif
@if(check_permission('category.list'))
<li class="nav-item {{ in_array($active, ['category']) ? 'active' : '' }}">
    <a class="nav-link" href="{{route('category.list')}}">
        Categories
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

@if(check_permission('contact_submissions.list'))
    <li class="nav-item {{ in_array($active, ['contact_submissions']) ? 'active' : '' }}">
        <a class="nav-link" href="{{route('contact_submissions.list')}}">
            Contact Forms
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
