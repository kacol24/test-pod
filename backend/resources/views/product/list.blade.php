@extends('layout')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="">

        <div class="container container--app" id="overview">
{{--            @if (session('status'))--}}
{{--                <div class="alert alert-success">--}}
{{--                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}
{{--                    <img src="{{theme_asset('backend/images/success-icon.png')}}" alt=""> {{session('status')}}--}}
{{--                </div>--}}
{{--            @endif--}}
{{--            @if (session('error'))--}}
{{--                <div class="alert alert-danger">--}}
{{--                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}
{{--                    <img src="{{asset('images/error-icon.png')}}" alt=""> {{session('error')}}--}}
{{--                </div>--}}
{{--            @endif--}}
{{--            @foreach ($errors->all() as $error)--}}
{{--                <div class="alert alert-danger">--}}
{{--                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>--}}
{{--                    <img src="{{theme_asset('backend/images/error-icon.png')}}" alt=""> {{$error}}--}}
{{--                </div>--}}
{{--            @endforeach--}}

            <div class="row justify-content-between">
                <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                    <h1 class="page-title m-0">{{Lang::get('product.products')}} </h1>
                </div>
                <div class="col col-md-auto">
                    <div class="row align-items-center justify-content-end">
                        <div class="col col-md-auto order-md-9">
                            {{--                            <div class="dropdown">--}}
                            {{--                                <button id="text-category" aria-expanded="false"--}}
                            {{--                                        aria-haspopup="true"--}}
                            {{--                                        class="m-0 btn btn-default d-block d-md-inline-block dropdown-toggle"--}}
                            {{--                                        data-toggle="dropdown" type="button">--}}
                            {{--                                    By Category--}}
                            {{--                                </button>--}}
                            {{--                                <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">--}}
                            {{--                                    @foreach($categories as $category)--}}
                            {{--                                        <a class="dropdown-item" href="javascript:void(0)"--}}
                            {{--                                           onclick="filterCategory({{$category->id}},'{{$category->title(session('language'))}}')">{{$category->title(session('language'))}}</a>--}}
                            {{--                                    @endforeach--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
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
        </div>
        <div class="container container--app">
            <div class="card mt-3 p-0">
                <div class="card-header border-bottom-0">
                    <div class="row align-items-center">
                        <div class="col-auto col-md d-block d-md-flex align-items-center"
                             x-data="{
                                selectedStatus: 'all',
                                statusOptions: {
                                    'all': 'All',
                                    '1': 'Active',
                                    '0': 'Inactive',
                                }
                            }">
                            <div class="d-none d-md-block">
                                <ul id="filter-publish" class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="all btn btn-default rounded-pill btn-sm px-3 text-color:blue font-weight-bold"
                                           href="javascript:void(0);"
                                           @click="filterPublish('all'); selectedStatus = 'all'">
                                            All
                                        </a>
                                    </li>
                                    <li class="nav-item mx-3">
                                        <a class="active btn btn-default rounded-pill btn-sm px-3"
                                           href="javascript:void(0);"
                                           @click="filterPublish(1); selectedStatus = '1'">
                                            Active
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="inactive btn btn-default rounded-pill btn-sm px-3"
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
                            {{--                            @if(session('admin')->role_id==1)--}}
                            {{--                                <a class="text-color:icon btn-link mr-4" href="javascript:void(0);" data-toggle="modal"--}}
                            {{--                                   data-target="#modalexport">--}}
                            {{--                                    <i class="fas fa-fw fa-cloud-download-alt"></i>--}}
                            {{--                                    Export--}}
                            {{--                                </a>--}}
                            {{--                            @else--}}
                            {{--                                <a class="text-color:icon btn-link mr-4" href="{{ route('product.export') }}">--}}
                            {{--                                    <i class="fas fa-fw fa-cloud-download-alt"></i>--}}
                            {{--                                    Export--}}
                            {{--                                </a>--}}
                            {{--                            @endif--}}

                            {{--                            <a class="text-color:icon" href="javascript:void(0);" data-toggle="modal"--}}
                            {{--                               data-target="#modalimport">--}}
                            {{--                                <i class="fas fa-fw fa-cloud-upload-alt"></i>--}}
                            {{--                                Import--}}
                            {{--                            </a>--}}
                        </div>
                    </div>
                </div>
                <table id="datatable"
                       data-mobile-responsive="true"
                       data-pagination="true"
                       data-sort-order="desc"
                       data-use-row-attr-func="true"
                       data-side-pagination="server"
                       data-query-params="queryProduct"
                       data-page-list="[10,20, 50, 100, 200]"
                       data-url="{{ route('product.datatable') }}">
                    <thead>
                    <tr>
                        <th data-field="image">{{Lang::get('general.image')}}</th>
                        <th data-field="title" data-sortable="true">{{Lang::get('product.itemname')}}</th>
                        <th data-field="category">Category</th>
                        <th data-field="status" data-sortable="true">Status</th>
                        <th data-field="action" class="text-nowrap">&nbsp;</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.19.1/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.css">
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.19.1/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js"></script>
    <script src="{{asset('js/list.js')}}"></script>
    <script type="text/javascript">
        var publish = '';
        var status = '';
        var category = '';
        {{--var sorting_url = "{{route('product.sorting')}}";--}}
    </script>
@endpush
