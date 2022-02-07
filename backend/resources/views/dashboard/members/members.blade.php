<div class="container container--app pb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="card p-0 card--widget">
                @include('dashboard.members.members_chart', ['chart' => $members])
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-0 card--widget">
                @include('dashboard.members.business_chart', ['chart' => $business])
            </div>
        </div>
    </div>
</div>
<hr>
<div class="container container--app pb-2">
    <h1 class="page-title mb-4">Coupons</h1>
    @if(isset($kpi))
        <div class="row">
            @foreach($kpi as $performance)
                <div class="col-md-6">
                    <div class="widget mx-0 text-center" style="position:relative;">
                        <h3 class="widget__title">
                            {{ $performance['title'] }}
                        </h3>
                        <div class="widget__body">
                            {{ round($performance['rate']) }}{{ $performance['rate_suffix'] ?? '' }}
                        </div>
                        <div class="widget__meta">
                            @if($performance['percent_diff'] != 0)
                                <span
                                    class="{{ $performance['percent_diff'] > 0 ? 'text-color:green' : 'text-color:red' }}">
                                    {{ $performance['percent_diff'] > 0 ? '+' : '-' }}{{ round(abs($performance['percent_diff'])) }}%
                                </span>
                                Dibandingkan periode lalu
                                <span class="text-muted">({{ $performance['last_period_rate'] }})</span>
                            @else
                                Sama dengan periode sebelumnya.
                            @endif
                        </div>
                        <div x-show="loading" class="loading-dashboard"
                             style="background-color: rgba(255, 255, 255, 1);"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
