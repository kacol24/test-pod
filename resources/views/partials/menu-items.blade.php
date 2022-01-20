<ul class="navbar-nav">
    <li class="nav-item dropdown border-0 active">
        <a class="nav-link dropdown-toggle" href="#" role="button"
           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Contents
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item"
               href="./blogs-index.html">
                Blog
            </a>
            <a class="dropdown-item"
               href="./banners-index.html">
                Banners
            </a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
        </div>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="/">
            Products
        </a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" href="./orders-index.html">
            Orders
        </a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" href="./products-index.html">
            Warehouse
        </a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" href="./payments-index.html">
            Stores
        </a>
    </li>

    <li class="nav-item d-flex align-items-center d-md-none pt-3">
        <a class="nav-link" href="{{ route('myaccount') }}">
            <i class="ri-account-circle-line ri-xl align-middle"></i>
        </a>
        <a class="nav-link" href="./customers-index.html">
            <i class="ri-shopping-basket-line ri-xl align-middle"></i>
        </a>
        <a class="nav-link" href="{{ route('mywallet') }}">
            <i class="ri-wallet-3-line ri-xl align-middle"></i>
        </a>
        <div class="d-flex flex-column font-size:12 ms-2">
            <span class="fw-500">IDR 200,000</span>
            <a href="" class="text-decoration-none fw-400">
                Top Up
            </a>
        </div>
    </li>
    <li class="nav-item d-block d-md-none border-0">
        <div class="nav-link">
            <a class="btn btn-primary w-100" href="./customers-index.html">
                <i class="ri-add-circle-line ri-xl align-middle"></i>
                Create
            </a>
        </div>
    </li>


    <li class="nav-item border-start ps-4 me-3 d-none d-md-flex">
        <a class="nav-link" href="./customers-index.html">
            <i class="ri-shopping-basket-line ri-xl align-middle"></i>
        </a>
    </li>
    <li class="nav-item dropdown border-start ps-4 me-3 d-none d-md-flex">
        <a class="nav-link dropdown-toggle fw-500 d-flex align-items-center" href="#" role="button"
           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ri-account-circle-line ri-xl align-middle me-2"></i>
            <span class="font-size:12">
                {{ session(\App\Models\Store::SESSION_KEY)->storename }}
            </span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="{{ route('myaccount') }}">
                    My Profile
            </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('myorders') }}">
                    My Purchases
            </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('myshipments') }}">
                    My Shipments
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('myaddress') }}">
                    My Addresses
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('myteam') }}">
                    My Team
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <a href="#" class="dropdown-item" onclick="this.closest('form').submit()">
                        Logout
                    </a>
                </form>
            </li>
        </ul>
    </li>
    <li class="nav-item me-3 border-start ps-4 d-none d-md-flex">
        <a class="nav-link" href="{{ route('mywallet') }}">
            <i class="ri-wallet-3-line ri-xl align-middle"></i>
        </a>
        <div class="d-flex flex-column font-size:12 ms-2">
            <span class="fw-500">
                IDR {{ number_format($storeBalanceComposer, 0, ',', '.') }}
            </span>
            <a href="{{ route('mywallet') }}" class="text-decoration-none fw-400">
                Top Up
            </a>
        </div>
    </li>
    <li class="nav-item me-3 d-none d-md-flex">
        <a class="btn btn-primary" href="./customers-index.html">
            <i class="ri-add-circle-line ri-xl align-middle"></i>
            Create
        </a>
    </li>
</ul>
