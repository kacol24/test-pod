@extends($theme.'::backend.layout')

@section('content')
<main class="pb-3 pb-md-5" role="main" style="">
    <div class="container container--app" id="overview">
        <div class="row justify-content-between">
        </div>
    </div>
    <div class="container container--app mb-5">
        <a class="btn btn-link" href="{{route('customer.list')}}">
            <i class="fas fa-fw fa-arrow-left"></i>
            Back
        </a>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-0 position-sticky" style="top: 100px;">
                    <div class="card-header text-center py-4">
                        <div class="avatar mx-auto {{$user->gender}}">
                            {{get_initials($user->name)}}
                        </div>
                        <h1 class="font-size:18 text-color:gray mt-3 mb-0">
                            {{$user->name}}
                        </h1>
                        <small class="font-size:12 text-color:icon d-block">
                            Joined since {{date('d F Y', strtotime($user->created_at))}}
                        </small>
                        <div class="badge badge-success text-uppercase mt-3 font-weight-medium">
                            {{$user->group->name}}
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <small class="text-muted font-size:12 text-uppercase">
                            {{Lang::get('customer.totalpurchase')}}
                        </small>
                        <div class="font-size:30 text-color:gray">
                            IDR {{number_format($total_purchase,0,",",".")}}
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div class="font-size:14 text-muted">Loyalty Points</div>
                            <div class="text-color:green">{{number_format($user->point,0,",",".")}}</div>
                        </div>
                        <div class="d-flex justify-content-between pt-3">
                            <div class="font-size:14 text-muted">Email</div>
                            <div class="text-color:gray">{{$user->email}}</div>
                        </div>
                        <div class="d-flex justify-content-between pt-3">
                            <div class="font-size:14 text-muted">Gender</div>
                            <div class="text-color:gray">{{ucfirst($user->gender)}}</div>
                        </div>
                        <div class="d-flex justify-content-between pt-3">
                            <div class="font-size:14 text-muted">Date of Birth</div>
                            <div class="text-color:gray">{{date('d F Y', strtotime($user->dob))}}</div>
                        </div>
                        <div class="d-flex justify-content-between pt-3">
                            <div class="font-size:14 text-muted">Total Transactions</div>
                            <div class="text-color:gray">{{$transaction}}</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-link pl-0" href="{{route('customer.topuppoint', $user->id)}}">
                            Manually Edit Points
                            <i class="fas fa-fw fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md mt-4 mt-md-0">
                <div class="card card--widget p-0">
                    <div class="card-header">
                        <h5 class="card-title">
                            Purchase History
                        </h5>
                    </div>
                    <table class="datatable"
                           data-classes="table table-hover"
                           data-mobile-responsive="true"
                           data-pagination="true"
                           data-sort-order="desc"
                           data-side-pagination="server"
                           data-query-params="queryParams"
                           data-page-list="[10,20, 50, 100, 200]"
                           data-url="{{route('customer.order.datatable', $user->id)}}">
                        <thead class="d-none">
                        <tr>
                            <th data-field="created_at" class="text-nowrap">ORDER TIME</th>
                            <th data-field="order_no" class="w-100 align-top">ORDER</th>
                            <th data-field="total" class="text-right text-nowrap">TOTAL</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="card card--widget p-0 mt-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            Points History
                        </h5>
                    </div>
                    <table class="datatable"
                           data-classes="table table-hover"
                           data-mobile-responsive="true"
                           data-pagination="true"
                           data-sort-order="desc"
                           data-side-pagination="server"
                           data-query-params="queryParams"
                           data-page-list="[10,20, 50, 100, 200]"
                           data-url="{{route('customer.point.datatable', $user->id)}}">
                        <thead class="d-none">
                        <tr>
                            <th data-field="created_at" class="text-nowrap">Tanggal</th>
                            <th data-field="description" class="w-100 align-top">Description</th>
                            <th data-field="point" class="text-right text-nowrap">Points</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
    <script src="{{asset('backend/js/list.js')}}"></script>
@endpush
