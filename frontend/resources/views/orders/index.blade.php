@extends('layouts.layout')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="">
        <div class="container container--app" id="overview">
            @if(session('status'))
                <x-alert type="success" dismissible icon>
                    {{ session('status') }}
                </x-alert>
            @endif
            @if(session('error'))
                <x-alert type="danger" dismissible icon>
                    {{ session('error') }}
                </x-alert>
            @endif
            @if($errors->any())
                <x-alert type="danger" dismissible icon>
                    @foreach ($errors->all() as $error)
                        <p class="m-0">
                            {{ $error }}
                        </p>
                    @endforeach
                </x-alert>
            @endif

            <div class="row justify-content-between">
                <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                    <h1 class="page-title m-0">{{ __('Orders') }} </h1>
                </div>
                <div class="col col-md-auto">
                    <div class="row align-items-center justify-content-end">
                        <div class="col col-md-auto order-md-9 border-end pe-4">
                            <div class="d-flex align-items-center">
                                <label for="" class="text-uppercase me-3">
                                    Channel
                                </label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Tokopedia</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto col-md-auto order-md-10 text-right">
                            <div class="form-group mb-0 w-100 ps-3">
                                <div class="input-group">
                                    <input class="form-control search-query" name="search"
                                           placeholder="{{ __('Search product') }}" type="text" value="">
                                    <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-fw fa-search"></i>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md order-md-5 d-flex align-items-center mt-3 mt-md-0">
                            <select class="form-select" aria-label="Default select example">
                                <option>Tokopedia</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container container--app">
            <div class="card mt-3 p-0">
                <div class="card-header">
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
                    </div>
                </div>
                <table id="datatable"
                       data-mobile-responsive="true"
                       data-pagination="true"
                       data-sort-order="asc"
                       data-classes="table table-hover table-striped"
                       data-reorderable-rows="true"
                       data-use-row-attr-func="true"
                       data-side-pagination="server"
                       data-query-params="queryProduct"
                       data-page-list="[10,20, 50, 100, 200]"
                       data-url="{{ route('orders.datatable') }}">
                    <thead>
                    <tr>
                        <th data-field="order_no">ORDER</th>
                        <th data-field="customer">CUSTOMER</th>
                        <th data-field="address"><ADDRESS></ADDRESS></th>
                        <th data-field="created_at">ORDER TIME</th>
                        <th data-field="total">TOTAL</th>
                        <th data-field="status">STATUS</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{asset('backend/js/list.js')}}"></script>
@endpush
