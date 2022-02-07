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
                    <h1 class="page-title m-0">Contact Submissions</h1>
                </div>
                <div class="col col-md-auto">
                    <div class="row align-items-center justify-content-end">
                        <div class="col-md order-md-5 d-flex align-items-center mt-3 mt-md-0">
                            <div class="form-group mb-0 w-100">
                                <div class="input-group ">
                                    <input class="form-control search-query" name="search" placeholder="Search"
                                           type="text" value="">
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
                <table id="datatable"
                       data-mobile-responsive="true"
                       data-pagination="true"
                       data-sort-order="desc"
                       data-side-pagination="server"
                       data-query-params="queryParams"
                       data-page-list="[10,20, 50, 100, 200]"
                       data-url="{{ route('contact_submissions.datatable') }}">
                    <thead>
                    <tr>
                        <th data-field="name" data-sortable="true">Name</th>
                        <th data-field="email" data-sortable="true">Email</th>
                        <th data-field="sent_to">Sent To</th>
                        <th data-field="message">Message</th>
                        <th data-field="created_at">Sent At</th>
                        <th data-field="action"></th>
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
