<div class="row justify-content-between">
    <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
        @stack('header_left')
    </div>
    <div class="col col-md-auto">
        <div class="row align-items-center justify-content-end">
            <div class="col col-md-auto order-md-9">
                <div class="dropdown">
                    <button id="text-category" aria-expanded="false"
                            aria-haspopup="true" class="m-0 btn btn-default d-block d-md-inline-block dropdown-toggle"
                            data-toggle="dropdown" type="button">
                        By Category
                    </button>
                    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                        @foreach($categories as $category)
                            <a class="dropdown-item" href="javascript:void(0)"
                               onclick="filterCategory({{$category->id}},'{{$category->title(session('language'))}}')">{{$category->title(session('language'))}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-auto col-md-auto order-md-10 text-right">
                <a class="btn btn-primary" href="{{route('product.add')}}">
                    <i class="fas fa-fw fa-plus-circle"></i>
                    {{Lang::get('product.product')}}
                </a>
            </div>
            <div class="col-md order-md-5 d-flex align-items-center mt-3 mt-md-0">
                <div class="form-group mb-0 w-100">
                    <div class="input-group ">
                        <input class="form-control search-query" name="search"
                               placeholder="{{Lang::get('product.searchby')}}" type="text" value="">
                        <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-fw fa-search"></i>
                                </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
