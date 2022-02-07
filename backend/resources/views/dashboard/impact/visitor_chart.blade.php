<div class="BasicCard__body py-0">
    <div class="row">
        <div class="col-md-8">
            <div class="py-3">
                <h1 class="Content__title Content__title--sm d-flex align-items-center mb-3">
                    <span class="Avatar border-0 mr-3" style="background-color:#00C187;">
                        <i class="far fa-user fa-fw"></i>
                    </span>
                    Pengunjung
                </h1>
                <div id="impact_visitor_chart"></div>
            </div>
        </div>
        <div class="col-md-4 border-left">
            <div
                class="p-5 d-flex flex-column justify-content-between align-items-center h-100 text-center">
                <h5 class="border-bottom pb-5 w-100">
                    Total Pengunjung
                </h5>
                <div class="my-5 w-100">
                    <h1>{{ $chart['sum_formatted'] }}</h1>
                </div>
                <div class="border-top w-100 pt-3">
                    <small>
                        @if($chart['sum'] === optional($chart['last_period'])['sum'])
                            Sama dengan periode sebelumnya.
                        @else
                            <strong
                                class="{{ optional($chart['compare'])['plus_minus'] == '+' ? 'text-success' : 'text-danger' }}">
                                {{ optional($chart['compare'])['plus_minus'] }}{{ round(optional($chart['compare'])['percent_diff']) }}%
                            </strong>
                            Dibandingkan periode sebelumnya
                        @endif
                        <span class="text-muted">
                            &nbsp;({{ optional($chart['last_period'])['sum_formatted'] }})
                        </span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<div x-show="loading" class="loading-dashboard"
     style="background-color: rgba(255, 255, 255, 1);"></div>

<script>
    Highcharts.chart('impact_visitor_chart', {
        title: false,
        colors: ['#00C187'],
        credits: {
            enabled: false
        },
        yAxis: {
            title: false
        },

        xAxis: {
            type: 'category',
            categories: @json($chart['labels'])
        },

        series: [
            {
                name: 'Clicks',
                data: @json($chart['data'])
            }
        ],

        responsive: {
            rules: [
                {
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
        }
    });
</script>
