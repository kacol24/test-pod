@extends('layout')

@section('content')
    <main class="mb-3 mb-md-5" role="main" style="">
        @component('partials.page-header', [
                  'title' => 'Capacities',
              ])
            <div class="row">
                <div class="col-md-auto order-md-10 mb-3 mb-md-0"></div>
                <div class="col-md order-md-5 d-flex align-items-center">
                    <div class="form-group mb-0 w-100">
                        <div class="input-group">
                            <input class="form-control search-query" name="search"
                                   placeholder="Search capacities" type="text" value="">
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
                    <img src="{{asset('backend/images/success-icon.png')}}" alt=""> {{session('status')}}
                </div>
            @endif
        </div>
        <div class="container container--app"
             x-data="{
                id: null,
                platform_id: null,

                submit: function() {
                    if (!this.platform_id) {
                        return alert('Cannot submit empty Platform ID');
                    }

                    $.ajax('{{ route('connect_tokopedia.update') }}/' + this.id, {
                        method: 'PUT',
                        data: {
                            platform_id: this.platform_id
                        }
                    }).done(function(response) {
                        window.location.reload();
                    }).fail(function() {
                        alert('Whoops! Something went wrong.');
                    });
                }
             }">
            <div class="card mt-3 p-0">
                <table id="datatable"
                       data-mobile-responsive="true"
                       data-pagination="true"
                       data-sort-order="asc"
                       data-side-pagination="server"
                       data-query-params="queryParams"
                       data-page-list="[10,20, 50, 100, 200]"
                       data-url="{{ route('connect_tokopedia.datatable') }}">
                    <thead>
                    <tr>
                        <th data-field="store">Store</th>
                        <th data-field="store_name">Tokopedia Store</th>
                        <th data-field="platform_id">Platform ID</th>
                        <th data-field="created_at">Created At</th>
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
