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
            <h1 class="page-title m-0">Treasures Arise</h1>
        </div>
        <div class="col col-md-auto">
          <div class="row align-items-center justify-content-end">
                <div class="col-md order-md-5 d-flex align-items-center mt-3 mt-md-0">
                    <div class="form-group mb-0 w-100">
                        <div class="input-group ">
                            <input class="form-control search-query" name="search" placeholder="Search business" type="text" value="">
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
                            'pending': 'Pending',
                            'approved': 'Approved',
                            'rejected': 'Rejected',
                        }
                       }">
                      <div class="d-none d-md-block">
                          <ul id="filter-publish" class="nav nav-pills">
                              <li class="nav-item">
                                  <a class="all btn btn-default rounded-pill btn-sm px-3 text-color:blue font-weight-bold"
                                     href="javascript:void(0);" @click="filterPublish('all'); selectedStatus = 'all'">
                                      All
                                  </a>
                              </li>
                              <li class="nav-item mx-3">
                                  <a class="pending btn btn-default rounded-pill btn-sm px-3" href="javascript:void(0);"
                                     @click="filterPublish('pending'); selectedStatus = 'pending'">
                                      Pending
                                  </a>
                              </li>
                              <li class="nav-item mr-3">
                                  <a class="approved btn btn-default rounded-pill btn-sm px-3"
                                     href="javascript:void(0);"
                                     @click="filterPublish('approved'); selectedStatus = 'approved'">
                                      Approved
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a class="rejected btn btn-default rounded-pill btn-sm px-3"
                                     href="javascript:void(0);"
                                     @click="filterPublish('rejected'); selectedStatus = 'rejected'">
                                      Rejected
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
                 data-sort-order="desc"
                 data-side-pagination="server"
                 data-query-params="queryProduct"
                 data-page-list="[10,20, 50, 100, 200]"
                 data-url="{{ route('treasure_arise.datatable') }}">
              <thead>
              <tr>
                  <th data-field="name" data-sortable="true">Business Name</th>
                  <th data-field="category">Category</th>
                  <th data-field="treasure_arise_status" data-sortable="true">Status</th>
                  <th data-field="action_treasure_arise">&nbsp;</th>
              </tr>
              </thead>
          </table>
      </div>
  </div>
</main>

@endsection

@push('scripts')
<script type="text/javascript">
  var publish = '';
  var status = '';
  var category = '';
</script>
<script src="{{asset('js/list.js')}}"></script>
@endpush
