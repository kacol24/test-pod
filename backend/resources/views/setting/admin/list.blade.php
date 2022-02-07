@extends('layout')

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
            <h1 class="page-title m-0">{{Lang::get('admin.admins')}} </h1>
        </div>
        <div class="col col-md-auto">
            <div class="row no-gutters">
                <div class="col-md-auto order-md-10 mb-3 mb-md-0">
                  <div class="d-flex justify-content-end">
                    <a class="btn btn-primary" href="{{route('admin.add')}}">
                        <i class="fas fa-fw fa-plus-circle"></i>
                        {{Lang::get('admin.addadmin')}}
                    </a>
                  </div>
                </div>
                <div class="col-md order-md-5 d-flex align-items-center">
                    <div class="form-group mb-0 w-100">
                        <div class="input-group ">
                            <input class="form-control search-query" name="search" placeholder="{{Lang::get('admin.searchby')}}" type="text" value="">

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
                 data-url="{{ route('admin.datatable') }}">
              <thead>
              <tr>
                  <th data-field="name">{{Lang::get('admin.adminname')}}</th>
                  <th data-field="email">Email</th>
                  <th data-field="role">{{Lang::get('admin.role')}}</th>
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
