@extends($theme.'::backend.layout')

@php($entitySingular = 'customer')
@php($entityPlural = 'customers')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="">
        <div class="container container--app" id="overview">
            @if (session('status'))
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <img src="{{asset('backend/images/success-icon.png')}}" alt=""> {{session('status')}}
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
                                <a href="{{ route('group.list') }}" class="btn btn-link pr-0">
                                    <i class="fas fa-fw fa-edit"></i>
                                    {{ Lang::get('customer.customergroups') }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md order-md-5 d-flex align-items-center">
                            <div class="form-group mb-0 w-100">
                                <div class="input-group ">
                                    <input class="form-control search-query" name="search"
                                           placeholder="{{Lang::get('cms.searchby', ['search_by' => 'name'])}}"
                                           type="search">
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
                <div class="card-header border-bottom-0 justify-content-end">
                    <form id="form-export" action="{{route('customer.export')}}">
                        <input type="hidden" name="group" value="0">
                        <div class="d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="d-flex align-items-center">
                                <label class="filter-label m-0">
                                    Filter:
                                </label>
                                <div class="dropdown ml-3">
                                    <button aria-expanded="false" aria-haspopup="true"
                                            class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown" id="dropdownMenuButton" type="button">
                                        <span id="text-customer-group">All Customer Group</span>
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                        <a class="dropdown-item"
                                           onclick="filterCustomerGroup(0,'All Customer Group')">{{Lang::get('customer.all')}}</a>
                                        @foreach($groups as $group)
                                            <a class="dropdown-item"
                                               onclick="filterCustomerGroup({{$group->id}}, '{{$group->name}}')">{{$group->name}}</a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="border-right mx-4 d-none d-md-block" style="height: 39px"></div>
                            </div>
                            <a class="text-color:icon mr-3 ml-4 mx-md-0" href="javascript:void(0);" id="export-customer">
                                <i class="fas fa-fw fa-cloud-download-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>
                <table id="datatable"
                       data-mobile-responsive="true"
                       data-pagination="true"
                       data-sort-order="desc"
                       data-side-pagination="server"
                       data-query-params="queryUsers"
                       data-page-list="[10,20, 50, 100, 200]"
                       data-url="{{ route($entitySingular . '.datatable') }}">
                    <thead class="text-uppercase">
                    <tr>
                        <th data-field="avatar"></th>
                        <th data-field="name">{{Lang::get('customer.customername')}}</th>
                        <th data-field="email">Email</th>
                        <th data-field="gender">{{Lang::get('general.gender')}}</th>
                        <th data-field="dob">DOB</th>
                        <th data-field="group">{{Lang::get('customer.group')}}</th>
                        <th data-field="status">Status</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{asset('backend/js/list.js')}}"></script>
    <script type="text/javascript">
        $("#export-customer").click(function(){
            $("#form-export").submit();
        });
    </script>
@endpush
