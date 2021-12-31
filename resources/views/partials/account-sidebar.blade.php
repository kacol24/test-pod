<div class="card p-0 sticky-top mb-4 account-sidebar" style="top: 85px;">
    <div class="card-header p-4 d-flex align-items-center">
        <div class="me-3">
            <div class="avatar bg-color:yellow">
                JT
            </div>
        </div>
        <div>
            <h3 class="card-title">
                Junitalia Tanosudcipto
            </h3>
            <small class="card-subtitle d-block">
                Joined since 08 Jul 2020
            </small>
            <a href="" class="text-decoration-none font-size:12">
                Log Out
                <i class="ri-arrow-right-line align-middle"></i>
            </a>
        </div>
    </div>
    <div class="card-body p-3 d-none d-md-block">
        <label class="text-uppercase mb-3 px-3 font-size:12">Your Details</label>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs(['myaccount']) ? 'active' : '' }}"
                   href="{{ route('myaccount') }}">
                    My Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs(['myorders', 'orderdetail']) ? 'active' : '' }}"
                   href="{{ route('myorders') }}">
                    My Purchases
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs(['myshipments', 'shipmentdetail']) ? 'active' : '' }}"
                   href="{{ route('myshipments') }}">
                    My Shipments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs(['myaddress']) ? 'active' : '' }}"
                   href="{{ route('myaddress') }}">
                    My Addresses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    My Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled">Disabled</a>
            </li>
        </ul>
    </div>
</div>

<div class="dropdown mb-4 d-block d-md-none">
    <button class="btn btn-default w-100 dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
            aria-expanded="false">
        My Profile
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li>
            <a class="dropdown-item"
               href="{{ route('myaccount') }}">
                My Profile
            </a>
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('myorders') }}">
                My Purchases
            </a>
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('myshipments') }}">
                My Shipments
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('myaddress') }}">
                My Addresses
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#">
                My Users
            </a>
        </li>
        <li>
            <a class="dropdown-item disabled">Disabled</a>
        </li>
    </ul>
</div>
