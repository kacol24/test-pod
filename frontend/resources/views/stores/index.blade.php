@extends('layouts.layout')

@section('content')
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

        <div class="text-start">
            <h1 class="page-title font-size:22">
                {{ __('Store') }}
            </h1>
            <div class="font-size:14">
                Connect your account with other platforms so that we can manage your orders for you.
            </div>
        </div>
    </div>
    <div class="container container--app">
        <div class="card mt-3 p-0">
            <table id="datatable"
                   data-mobile-responsive="true"
                   data-pagination="true"
                   data-sort-order="asc"
                   data-classes="table table-hover table-striped"
                   data-reorderable-rows="true"
                   data-use-row-attr-func="true"
                   data-page-list="[10,20, 50, 100, 200]">
                <thead>
                <tr>
                    <th data-field="order_no">PLATFORM</th>
                    <th data-field="customer">SPECIAL OFFER</th>
                    <th data-field="address">ACCOUNT</th>
                    <th data-field="created_at">STATUS</th>
                    <th data-field="total">ACTION</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <img src="{{ asset('images/tokopedia.png') }}" alt="logo tokopedia" class="img-fluid">
                    </td>
                    <td>
                        Free IDR 50,000 Tokopedia Ad quota when you connect
                    </td>
                    <td>

                    </td>
                    <td>
                        @if(!$connected->firstWhere('platform', 'tokopedia'))
                            @if($pendingTokopedia)
                                <span class="text-warning">Pending</span>
                            @endif
                        @else
                            <span class="text-color:green">Connected</span>
                        @endif
                    </td>
                    <td>
                        @if(!$connected->firstWhere('platform', 'tokopedia'))
                            @if($pendingTokopedia)
                                <a href="{{ route('stores.cancel_tokopedia', $pendingTokopedia) }}"
                                   class="btn btn-sm px-1 btn-outline-dark"
                                   data-confirm="Cancel request to connect tokopedia?  Your connection info will be removed, you will have to reconnect again."
                                   data-method="DELETE">
                                    Cancel Request
                                </a>
                            @else
                                <a href="#tokopediaModal" class="btn btn-sm px-1 btn-dark" data-bs-toggle="modal">
                                    Connect
                                </a>
                            @endif
                        @else
                            <a href="{{ route('stores.destroy', $connected->firstWhere('platform', 'tokopedia')->id) }}"
                               class="btn btn-sm px-1 btn-outline-dark"
                               data-confirm="Disconnect? Your connection info will be removed, you will have to reconnect again."
                               data-method="DELETE">
                                Disconnect
                            </a>
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    @include('stores._tokopedia-flow')
@endsection

@push('scripts')
    <script src="{{asset('backend/js/list.js')}}"></script>
@endpush
