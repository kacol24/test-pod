<div class="container container--app mb-3" id="page-header">
    <div class="row justify-content-center">
        <div class="{{ $size ?? 'col-12' }}">
            <div class="row justify-content-between">
                <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                    <h1 class="page-title m-0">
                        {{ $title }}
                    </h1>
                </div>
                @if($slot)
                    <div class="col col-md-auto">
                        {{ $slot }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
