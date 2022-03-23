@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="mx-auto" style="max-width: 885px">
            <div class="text-center">
                <img src="{{ asset('images/inky-online-meeting-1-1.png') }}" alt="" class="img-fluid">
                <h1 class="page-title mb-4 text-center">
                    Referral
                </h1>
                <div class="mx-auto font-size:14" style="max-width: 730px">
                    Invite fellow artists to join, and get {{ App\Models\StoreReferral::COMMISSION_RATE * 100 }}% of
                    every sales they make for {{ App\Models\StoreReferral::EXPIRED_THRESHOLD / 30 }} months since they
                    joined.
                    Commission will be added automatically to your wallet. <strong>Limited
                        to {{ App\Models\Store::MAX_DOWNLINE }} invites only.</strong>
                </div>
            </div>
            <div class="card mt-5"
                 style="background: rgba(22, 101, 216, 0.05);border: 1px dashed rgba(22, 101, 216, 0.5);border-radius: 4px;">
                @if($upline->referralSlotsLeft())
                    <div class="fw-500 font-size:16 mb-2">
                        Share this link to your friend:
                    </div>
                    <div class="bg-white px-3 py-2 d-flex justify-content-between align-items-center"
                         style="border: 1px solid #E2E5ED;box-shadow: inset 0px 1px 2px rgba(102, 113, 123, 0.21);border-radius: 4px;">
                        <span
                            class="text-color:blue fw-500 user-select-all font-size:14 text-truncate">{{ route('register', ['rid' => $upline->ref_code]) }}</span>
                        <a href="javascript:void(0)"
                           class="text-decoration-none fw-400 text-color:black text-nowrap font-size:14 ms-1" @include('partials.data-copy', ['copyText' => route('register', ['rid' => $upline->ref_code])])>
                            <i class="far fa-fw fa-copy"></i>
                            Copy
                        </a>
                    </div>
                @else
                    <div class="fw-500 font-size:16 text-color:blue">
                        Sharing quota reached!
                    </div>
                @endif
            </div>
            <div class="row align-items-center justify-content-between mt-4">
                <div class="col-md-auto">
                    <div class="fw-500 d-flex align-items-center">
                        <div style="width: 48px;height: 48px; background-color:#000;"
                             class="rounded-circle d-flex align-items-center justify-content-center text-white me-3">
                            <i class="ri ri-service-fill ri-fw ri-lg"></i>
                        </div>
                        {{ $upline->downlines->count() }} out of {{ App\Models\Store::MAX_DOWNLINE }} of your
                        friends joined.
                    </div>
                </div>
                <div class="col-md-auto mt-4 mt-md-0">
                    <div class="d-flex align-items-center">
                        <div class="fw-500" style="color: #707A83;">
                            Total commission:
                        </div>
                        <div class="fw-600 ms-1">
                            IDR {{ $upline->formatted_total_commission }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table border">
                    <thead>
                    <tr class="text-uppercase font-size:12 fw-500" style="color: #9EA0A5;">
                        <th class="fw-500">#</th>
                        <th class="fw-500">Store Name</th>
                        <th class="fw-500">Email</th>
                        <th class="fw-500">Commission (IDR)</th>
                        <th class="fw-500">Status</th>
                        <th class="fw-500">Days Left</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($downlines as $downline)
                        <tr class="font-size:12">
                            <td class="py-4">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-4">
                                {{ $downline->downline->storename }}
                            </td>
                            <td class="py-4">
                                {{ $downline->downline->superAdmin->first()->email }}
                            </td>
                            <td class="py-4">
                                {{ $downline->formatted_total_commission }}
                            </td>
                            <td class="py-4 text-uppercase">
                                @if($downline->isExpired())
                                    <div class="text-muted">
                                        Inactive
                                    </div>
                                @else
                                    <div class="text-color:green">
                                        Active
                                    </div>
                                @endif
                            </td>
                            <td class="py-4">
                                {{ $downline->expired_days_left }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center font-size:14 py-5">
                                Your friends list is empty. Let’s invite them to join with your link!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
