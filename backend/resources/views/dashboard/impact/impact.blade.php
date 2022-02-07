<div class="container container--app pb-2">
    <div class="BasicCard BasicCard--narrow">
        @include('dashboard.impact.visitor_chart', ['chart' => $visitors])
    </div>
    <div class="BasicCard BasicCard--narrow mt-4">
        @include('dashboard.impact.contact_chart', [
            'chart' => $contacts,
            'visitors' => $visitors
        ])
    </div>
    <div class="BasicCard BasicCard--narrow mt-4">
        @include('dashboard.impact.button_chart', [
            'chart' => $buttons,
            'visitors' => $visitors
        ])
    </div>
    <div class="BasicCard BasicCard--narrow mt-4">
        @include('dashboard.impact.coupon_chart', [
            'chart' => $coupons,
            'visitors' => $visitors
        ])
    </div>
</div>
<hr>
<div class="container container--app pb-2">
    <h1 class="page-title mb-4">Click Through Rate</h1>
    @if(isset($kpi))
        <div class="row">
            @foreach($kpi as $performance)
                <div class="col-md-4">
                    <div class="widget mx-0 text-center" style="position:relative;">
                        <h3 class="widget__title">
                            {{ $performance['title'] }}
                        </h3>
                        <div class="widget__body">
                            {{ round($performance['rate']) }}%
                        </div>
                        <div class="widget__meta">
                            @if($performance['percent_diff'] != 0)
                                <span
                                    class="{{ $performance['percent_diff'] > 0 ? 'text-color:green' : 'text-color:red' }}">
                                    {{ $performance['percent_diff'] > 0 ? '+' : '-' }}
                                    {{ abs($performance['percent_diff']) }}%
                                </span>
                                Dibandingkan periode lalu
                                <span class="text-muted">({{ $performance['last_period_rate'] }}%)</span>
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
