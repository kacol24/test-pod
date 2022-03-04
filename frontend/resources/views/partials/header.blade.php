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

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable"
         style="max-width: 880px">
        <div class="modal-content">
            <div class="modal-header p-4">
                <h5 class="modal-title font-size:16 fw-500" id="createModalLabel">
                    Select Product Type
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('design.create') }}" class="text-decoration-none">
                            <div
                                class="card shadow-sm text-center p-md-5 d-flex align-items-center justify-content-center text-color:black">
                                <div style="width: 45px;height: 45px;background: rgba(22, 101, 216, 0.1);"
                                     class="rounded-circle d-flex align-items-center justify-content-center mb-4">
                                    <i class="ri ri-t-shirt-2-line ri-fw text-color:blue"></i>
                                </div>
                                <strong class="d-block font-size:14 fw-bold mb-3">
                                    Print-on-demand product
                                </strong>
                                <div class="font-size:14 fw-500" style="color: #707A83;">
                                    T-shirts, mugs, pillow, or any items from our line up you want to sell
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div
                            class="card shadow-sm text-center p-md-5 d-flex align-items-center justify-content-center text-color:black position-relative"
                            style="background: #F7F7F7;">
                            <div class="product-item__overlay d-block w-auto h-auto" style="top: 10px;left: 10px;">
                                <span class="badge bg-dark">Coming Soon</span>
                            </div>
                            <div style="width: 45px;height: 45px;background: #fff;"
                                 class="rounded-circle d-flex align-items-center justify-content-center mb-4">
                                <i class="ri ri-pantone-line ri-fw"></i>
                            </div>
                            <strong class="d-block font-size:14 fw-bold mb-3">
                                Create your own
                            </strong>
                            <div class="font-size:14 fw-500" style="color: #707A83;">
                                Stickers, cards, or any type of promotional freebie you want to add to an order
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
