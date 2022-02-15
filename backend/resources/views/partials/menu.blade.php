<div class="modal fade" id="mobile_nav">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header pl-0 border-bottom flex-column">
                <button aria-label="Close" class="close float-left ml-0" data-dismiss="modal" type="button">
                    <span aria-hidden="true" style="font-weight: 100">&times;</span>
                </button>
                <a class="nav-link pb-0 pt-4" href="javascript:void(0);">
                    <img alt="logogram goodcommerce" loading="lazy" src="{{asset('images/Logo.png')}}"
                         width="15">
                </a>
            </div>
            <div class="modal-body">
                <nav class="navbar">
                    <ul class="navbar-nav">
                        @include('partials.menu-items')
                    </ul>
                </nav>
            </div>
            <div class="modal-footer d-block p-0 shadow-lg">
                <nav class="navbar m-0 border-0">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown border-0">
                            <a aria-expanded="false" aria-haspopup="true"
                               class="nav-link dropdown-toggle d-flex align-items-center"
                               data-toggle="dropdown"
                               href="#" id="navbarDropdown" role="button">
                                <div class="d-inline-block">
                                    {{session('admin')->name}}
                                    <small class="d-block text-muted">
                                        {{session('admin')->role->name}}
                                    </small>
                                </div>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<header class="fixed-top">
    <nav class="navbar navbar-expand-lg px-0 px-lg-3" id="site_nav">
        <a class="navbar-brand d-none d-lg-flex" href="/">
            <img alt="logogram goodcommerce" loading="lazy" src="{{asset('images/Logo.png')}}" width="20">
        </a>
        <button aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"
                data-target="#mobile_nav" data-toggle="modal" type="button">
            <i class="fas fa-fw fa-bars"></i>
        </button>

        <div class="font-weight-medium font-size:18 text-color:gray d-block d-lg-none">
            {{$page_title}}
        </div>

        <div class="collapse navbar-collapse" id="main_nav">
            <ul class="navbar-nav mr-auto">
                @include('partials.menu-items')
            </ul>
        </div>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <div aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
            <li class="nav-item dropdown border-left pl-4 d-none d-lg-flex">
                <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle d-flex align-items-center"
                   data-toggle="dropdown"
                   href="#" id="navbarDropdown" role="button">
                    <div class="d-inline-block">
                        {{session('admin')->name}}
                        <small class="d-block text-muted">
                            {{session('admin')->role->name}}
                        </small>
                    </div>
                </a>
                <div aria-labelledby="navbarDropdown" class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{route('logout')}}">
                        {{Lang::get('general.logout')}}
                    </a>
                </div>
            </li>
        </ul>
    </nav>
</header>
