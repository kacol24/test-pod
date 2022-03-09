@extends('layout')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="">
        @component('partials.page-header', [
                      'title' => Lang::get('option.options'),
                  ])
            <div class="row">
                <div class="col-md-auto order-md-10 mb-3 mb-md-0">
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-primary" href="{{route('option.add')}}">
                            <i class="fas fa-fw fa-plus-circle"></i>
                            {{Lang::get('option.addoption')}}
                        </a>
                    </div>
                </div>
                <div class="col-md order-md-5 d-flex align-items-center">
                    <div class="form-group mb-0 w-100">
                        <div class="input-group ">
                            <input class="form-control search-query" name="search"
                                   placeholder="{{Lang::get('option.searchby')}}" type="text" value="">
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
        @endcomponent
        <div class="container container--app" id="overview">
            @if (session('status'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{asset('images/success-icon.png')}}" alt=""> {{session('status')}}
                </div>
            @endif
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
                       data-url="{{ route('option.datatable') }}">
                    <thead>
                    <tr>
                        <th data-field="title" data-sortable="true">{{Lang::get('option.optionname')}}</th>
                        <th data-field="created_at" data-sortable="true">{{ __('Created At') }}</th>
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
