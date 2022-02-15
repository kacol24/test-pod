@extends($theme.'::backend.layout')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="">

        <div class="container container--app" id="overview">
            @if (session('status'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{theme_asset('backend/images/success-icon.png')}}" alt=""> {{session('status')}}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{asset('images/error-icon.png')}}" alt=""> {{session('error')}}
                </div>
            @endif
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{theme_asset('backend/images/error-icon.png')}}" alt=""> {{$error}}
                </div>
            @endforeach

            <div class="row justify-content-between">
                <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                    <h1 class="page-title m-0">{{Lang::get('product.products')}} </h1>
                </div>
                <div class="col col-md-auto">
                    <div class="row align-items-center justify-content-end">
                        <div class="col col-md-auto order-md-9">
                            <div class="dropdown">
                                <button id="text-category" aria-expanded="false"
                                        aria-haspopup="true"
                                        class="m-0 btn btn-default d-block d-md-inline-block dropdown-toggle"
                                        data-toggle="dropdown" type="button">
                                    @if($products->category == 0)
                                        By Category
                                    @else
                                        {{ $products->category_name }}
                                    @endif
                                </button>
                                <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)"
                                       onclick="filterCategory(0,'All Categories')">
                                        All Categories
                                    </a>
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
                            <form>
                                <div class="form-group mb-0 w-100">
                                    <div class="input-group">
                                        <input class="form-control search-query" name="search"
                                               placeholder="{{Lang::get('product.searchby')}}" type="search"
                                               value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn p-0 border-0 shadow-none" style="background-color:#fff;">
                                                <i class="fas fa-fw fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container container--app">
            <div class="card mt-3 p-0">
                <div class="card-header border-bottom-0">
                    <div class="row align-items-center">
                        <div class="col-auto col-md d-block d-md-flex align-items-center"
                             x-data="{
                                selectedStatus: '{{ request('status', 'all') }}',
                                statusOptions: {
                                    'all': 'All',
                                    '1': 'Active',
                                    '0': 'Inactive',
                                }
                            }">
                            <div class="d-none d-md-block">
                                <ul id="filter-publish" class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="all btn btn-default rounded-pill btn-sm px-3 {{ request('status') == 'all' || request('status') == '' ? 'text-color:blue font-weight-bold' : '' }}"
                                           href="javascript:void(0);"
                                           @click="filterPublish('all'); selectedStatus = 'all'">
                                            All
                                        </a>
                                    </li>
                                    <li class="nav-item mx-3">
                                        <a class="active btn btn-default rounded-pill btn-sm px-3 {{ request('status') == '1' ? 'text-color:blue font-weight-bold' : '' }}"
                                           href="javascript:void(0);"
                                           @click="filterPublish(1); selectedStatus = '1'">
                                            Active
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="inactive btn btn-default rounded-pill btn-sm px-3 {{ request('status') == '0' ? 'text-color:blue font-weight-bold' : '' }}"
                                           href="javascript:void(0);"
                                           @click="filterPublish(0); selectedStatus = '0'">
                                            Inactive
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="d-block d-md-none">
                                <div class="dropdown">
                                    <button id="text-status" aria-expanded="false" aria-haspopup="true"
                                            class="m-0 btn btn-default d-block d-md-inline-block dropdown-toggle"
                                            data-toggle="dropdown" type="button"
                                            x-text="statusOptions[selectedStatus]">
                                        All
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                        @foreach(['all' => 'All', '1' => 'Active', '0' => 'Inactive'] as $status => $statusText)
                                            <a class="dropdown-item" href="javascript:void(0)"
                                               @click="filterPublish('{{ $status }}'); selectedStatus = '{{ $status }}'">
                                                {{ $statusText }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-md-auto d-flex align-items-center text-right">
                            <a class="text-color:icon mr-4 {{ request()->routeIs(['product.list']) ? 'btn-link' : '' }}"
                               href="{{ route('product.list') }}">
                                <i class="fas fa-fw fa-list"></i>
                            </a>
                            <a class="text-color:icon {{ request()->routeIs(['product.grid']) ? 'btn-link' : '' }}"
                               href="{{ route('product.grid') }}">
                                <i class="fas fa-fw fa-th-large"></i>
                            </a>
                            <div class="border-right mx-4" style="height: 39px"></div>
                            @if(session('admin')->role_id==1)
                                <a class="text-color:icon btn-link mr-4" href="javascript:void(0);" data-toggle="modal"
                                   data-target="#modalexport">
                                    <i class="fas fa-fw fa-cloud-download-alt"></i>
                                    Export
                                </a>
                            @else
                                <a class="text-color:icon btn-link mr-4" href="{{ route('product.export') }}">
                                    <i class="fas fa-fw fa-cloud-download-alt"></i>
                                    Export
                                </a>
                            @endif

                            <a class="text-color:icon" href="javascript:void(0);" data-toggle="modal"
                               data-target="#modalimport">
                                <i class="fas fa-fw fa-cloud-upload-alt"></i>
                                Import
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="row list-unstyled mb-0" id="sortable-list">
                        @foreach($products->items as $product)
                            <li class="col-md-3 col-6 mb-4">
                                <div class="card p-0">
                                    <a href="{{route('product.edit',['id' => $product->id])}}">
                                        @php($image = image_url('221x221',$product->image))
                                        <img src="{{$image}}" alt="{{ $product->title }}" class="card-img-top w-100">
                                        <div class="card-body p-3 text-color:gray">
                                            <h3 class="card-title font-size:14">
                                                {{ $product->title }}
                                            </h3>
                                            {{ store()->currency }}
                                            {{ number_format($product->price,0,",",".") }}
                                        </div>
                                    </a>
                                    <div
                                        class="card-footer pb-3 px-3 border-0 pt-0 d-flex align-items-center justify-content-end">
                                        <div class="custom-control custom-checkbox d-none">
                                            <input class="custom-control-input" id="checkbox{{$product->id}}"
                                                   name="ids[]" type="checkbox"
                                                   value="{{ $product->id }}" {{ old('checkbox' . $product->id) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="checkbox{{$product->id}}"></label>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a class="text-color:icon mt-2 no-underline mr-3 delete"
                                               href="javascript:void(0)" url="{{ route('product.bulkdelete') }}"
                                               id="{{ $product->id }}">
                                                <i class="fas fa-fw fa-lg fa-trash"></i>
                                            </a>
                                            <a class="text-color:icon mt-2 no-underline mr-3"
                                               href="{{ route('product.edit', ['id' => $product->id]) }}">
                                                <i class="fas fa-fw fa-lg fa-edit"></i>
                                            </a>
                                            <div class="custom-control custom-switch">
                                                <input class="custom-control-input toogle-active"
                                                       url="{{ route('product.status', $product->id) }}"
                                                       id="grid_{{ $product->id }}"
                                                       type="checkbox" {{ $product->is_publish ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                       for="grid_{{ $product->id }}"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)"
                                   onclick="setPage({{ $products->prev_page }})">Previous</a>
                            </li>
                            @for($i=1;$i<=$products->total_page;$i++)
                                <li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" onclick="setPage({{ $i }})">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)"
                                   onclick="setPage({{ $products->next_page }})">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="modalexport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Export</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('product.export') }}">
                    <div class="modal-body">
                        <div class="sub-head">
                            @if(session('admin')->role_id == 1)
                                <div class="form-group">
                                    <label for="exampleInputFile">Outlet</label>
                                    <select class="form-control" required name="outlet_id">
                                        @foreach($outlets as $outlet)
                                            <option value="{{$outlet->id}}">{{$outlet->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-toggle="modal" data-target="#modalimport"
                           href="#">{{Lang::get('general.cancel')}}</a>
                        <button class="btn btn-primary text-uppercase">{{Lang::get('general.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalimport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('product.import') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="sub-head">
                            @if(session('admin')->role_id == 1)
                                <div class="form-group">
                                    <label for="exampleInputFile">Outlet</label>
                                    <select class="form-control" required name="outlet_id">
                                        @foreach($outlets as $outlet)
                                            <option value="{{$outlet->id}}">{{$outlet->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" name="file" id="exampleInputFile">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-default" data-toggle="modal" data-target="#modalimport"
                           href="#">{{Lang::get('general.cancel')}}</a>
                        <button class="btn btn-primary text-uppercase">{{Lang::get('general.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <form id="grid-product">
        <input type="hidden" name="page" value="{{$products->page}}">
        <input type="hidden" name="limit" value="{{$products->limit}}">
        <input type="hidden" name="status" value="{{$products->status}}">
        <input type="hidden" name="category" value="{{$products->category}}">
    </form>
    <form method="post" id="bulk-action">
        {{ csrf_field() }}
        <input type="hidden" name="ids"/>
        <input type="hidden" name="back_url" value="{{route('product.grid')}}"/>
    </form>
    <script type="text/javascript">
        var sorting_url = "{{route('product.sorting')}}";
        var ids = [];
        var page = {{$products->page}};
        var limit = {{$products->limit}};
        var action = '';
        var status = '{{$products->status}}';
        var category = {{$products->category}};

        $('.delete').unbind('click').click(function() {
            var id = $(this).attr('id');
            url = $(this).attr('url');
            ids = [];
            ids.push(id);
            $('#modalconfirm .modal-body .sub-head')
                .html('Are you sure you want to delete this record? This action cannot be undo');
            $('#modalconfirm').modal('show');
        });

        $('.toogle-active').unbind('change').change(function() {
            if ($(this).is(':checked')) {
                status = 'enable';
            } else {
                status = 'disable';
            }
            $('.loading').show();
            $.ajax({
                url: $(this).attr('url'),
                data: {
                    status: status,
                    _token: $('input[name=\'_token\']').val()
                },
                type: 'post',
                success: function(data) {
                    $('.loading').hide();
                }
            });
        });
    </script>
    <script src="{{theme_asset('backend/js/jquery-ui.js')}}"></script>
    <script src="{{theme_asset('backend/js/disable-selection.js')}}"></script>
    <script src="{{theme_asset('backend/js/grid.js')}}"></script>
@endpush
