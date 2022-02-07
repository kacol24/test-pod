@extends('layout')

@php($entitySingular = 'banner')
@php($entityPlural = 'banners')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="">
        <div class="container container--app" id="overview">
            @if (session('status'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{asset('images/success-icon.png')}}" alt=""> {{session('status')}}
                </div>
            @endif
            <div class="row justify-content-between">
                <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                    <h1 class="page-title m-0">{{ ucfirst($entityPlural) }} </h1>
                </div>
                <div class="col col-md-auto">
                    <div class="row no-gutters">
                        <div class="col-md-auto order-md-10 mb-3 mb-md-0">
                            <div class="d-flex justify-content-end">
                                <a class="btn btn-primary" href="{{route($entitySingular . '.create')}}">
                                    <i class="fas fa-fw fa-plus-circle"></i>
                                    {{Lang::get('cms.add', ['entity_singular' => $entitySingular])}}
                                </a>
                            </div>
                        </div>
                        <div class="col-md order-md-5 d-flex align-items-center">
                            <div class="form-group mb-0 w-100">
                                <div class="input-group ">
                                    <input class="form-control search-query" name="search"
                                           placeholder="{{Lang::get('cms.searchby', ['search_by' => 'title'])}}"
                                           type="text" value="">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-fw fa-search"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="border-right ml-4 d-none d-md-block" style="height: 39px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container container--app">
            <div class="card mt-3 p-0">
                <table id="datatable"
                       data-mobile-responsive="true"
                       data-pagination="true"
                       data-sticky-header="true"
                       data-sticky-header-offset-y="70"
                       data-sort-order="desc"
                       data-side-pagination="server"
                       data-query-params="queryParams"
                       data-page-list="[10,20, 50, 100, 200]"
                       data-url="{{ route($entitySingular . '.datatable') }}">
                    <thead>
                    <tr>
                        <th data-field="title" data-sortable="true">{{Lang::get('banner.title')}}</th>
                        <th data-field="type" data-sortable="true">Type</th>
                        <th data-field="start_date" data-sortable="true">{{Lang::get('general.startdate')}}</th>
                        <th data-field="end_date" data-sortable="true">{{Lang::get('general.enddate')}}</th>
                        <th data-field="status" data-sortable="true">Status</th>
                        <th data-field="action">&nbsp;</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{asset('js/list.js')}}"></script>
@endpush
