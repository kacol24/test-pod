@extends('layout')

@section('content')
    <main class="pb-3 pb-md-5" role="main" x-data="{selectedOutlet: {id: 0, title: '{{__('order.all')}}'}, status:0}">
        <form id="export" action="">
            <input type="hidden" name="outlet_id" x-model="selectedOutlet.id"/>
            <input type="hidden" name="status_id" x-model="status"/>
            <div class="container container--app" id="overview">
                <div class="row justify-content-between">
                    <div class="col-5 col-md-auto d-none d-md-flex align-items-center">
                        <h1 class="page-title m-0">{{Lang::get('order.orders')}} </h1>
                    </div>
                    <div class="col col-md-auto">
                        <section class="dashboard-filter">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="d-none d-md-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="form-group mb-0">
                                            <div class="input-group ">
                                                <input class="form-control" name="search"
                                                       placeholder="{{Lang::get('order.searchby')}}" type="text"
                                                       value="">
                                                <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-fw fa-search"></i>
                                            </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="border-right d-none d-md-block mr-4 ml-4"
                                             style="height: 39px"></div>
                                        <div class="form-group mb-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="start_date"
                                                       placeholder="{{Lang::get('general.startdate')}}" required
                                                       id="start_date" style="width:150px;"/>
                                                <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-fw fa-calendar"></i>
                                            </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mx-2 text-center">
                                            -
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="input-group">
                                                <input type="text" class="form-control datepicker" name="end_date"
                                                       placeholder="{{Lang::get('general.enddate')}}" required
                                                       id="end_date" style="width:150px;"/>
                                                <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fas fa-fw fa-calendar"></i>
                                            </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center ml-3 ml-md-0">
                                    <div class="border-right d-none d-md-block mr-4 ml-4" style="height: 39px"></div>
                                    <a class="btn btn-primary" href="">
                                        <i class="fas fa-fw fa-plus-circle"></i>
                                        {{Lang::get('order.order')}}
                                    </a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="container container--app">
                <div class="card mt-3 p-0">
                    <div class="card-header border-bottom-0">
                        <div class="row">
                            <div class="col d-flex align-items-center">
                                <div class="d-none d-md-block">
                                    <ul class="nav nav-pills">
                                        @foreach($order_status as $status)
                                            <li class="nav-item mr-2 d-inline-flex">
                                                <a class="btn btn-default rounded-pill btn-sm px-3"
                                                   href="javascript:void(0);"
                                                   :class="status == {{$status->id}} ? 'text-color:blue font-weight-bold' : ''"
                                                   @click="status = {{$status->id}};filterStatus({{$status->id}})">
                                                    <span class="badge badge-status-{{$status->id}} d-inline-block p-0"
                                                          style="width: 6px;height: 6px;"></span>
                                                    {{$status->title}}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="d-block d-md-none">
                                    <div class="dropdown">
                                        <button id="text-status" aria-expanded="false" aria-haspopup="true"
                                                class="m-0 btn btn-default d-block d-md-inline-block dropdown-toggle"
                                                data-toggle="dropdown" type="button">
                                            Filter
                                        </button>
                                        <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                            @foreach($order_status as $status)
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                   :class="status == {{$status->id}} ? 'text-color:blue font-weight-bold' : ''"
                                                   @click="status = {{$status->id}};filterStatus({{$status->id}})">
                                            <span class="badge badge-status-{{$status->id}} d-inline-block p-0"
                                                  style="width: 6px;height: 6px;"></span>
                                                    {{$status->title}}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <a class="text-color:icon ml-3" href="javascript:void(0)"
                                   x-on:click="function() {$('#export').submit()}">
                                    <i class="fas fa-fw fa-cloud-download-alt"></i>
                                    Export
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table id="datatable"
                               data-mobile-responsive="true"
                               data-pagination="true"
                               data-sticky-header="true"
                               data-sticky-header-offset-y="70"
                               data-sort-order="desc"
                               data-side-pagination="server"
                               data-query-params="queryOrder"
                               data-page-list="[10,20, 50, 100, 200]">
                            <thead>
                            <tr>
                                <th data-field="order_no">ORDER</th>
                                <th data-field="customer">CUSTOMER</th>
                                <th data-field="email">EMAIL</th>
                                <th data-field="created_at">ORDER TIME</th>
                                <th data-field="total">TOTAL</th>
                                <th data-field="status">STATUS</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
@push('scripts')
    <script type="text/javascript">
        var status = '';
        var outlet_id = '';
    </script>
    <script src="{{asset('js/list.js')}}"></script>
    <script src="{{asset('js/typeahead.min.js')}}"></script>
    <script src="{{asset('js/library/moment/min/moment.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.standalone.min.css">
    <script type="text/javascript">
        $(function() {
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayBtn: true,
                todayHighlight: true
            }).on('changeDate', function(e) {
                $table.bootstrapTable('refresh');
            });
        });

        function setOutlet(id) {
            outlet_id = id;
            $table.bootstrapTable('refresh');
        }
    </script>
@endpush
