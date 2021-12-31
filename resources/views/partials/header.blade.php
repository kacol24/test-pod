<div class="modal fade" id="mobile_nav">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <span class="font-size:26 fw-600">Menu</span>
                <a href="#" aria-label="Close" class="close text-decoration-none text-color:black font-size:36"
                   data-bs-dismiss="modal" type="button">
                    <span aria-hidden="true" style="font-weight: 100">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <nav class="navbar px-0 px-lg-3">
                    <div class="collapse show navbar-collapse justify-content-end" id="main_nav">
                        @include('partials.menu-items')
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<header class="fixed-top shadow-sm">
    <nav class="navbar navbar-expand-lg px-0 px-lg-3" id="site_nav">
        <a class="navbar-brand border-0" href="/">
            LOGO
        </a>
        <button class="navbar-toggler border-start" type="button" data-bs-toggle="modal" data-bs-target="#mobile_nav"
                aria-controls="main_nav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="ri-menu-line ri-fw"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="main_nav">
            @include('partials.menu-items')
        </div>
    </nav>
</header>
