<div class="card-header d-flex align-items-center">
    <span class="Avatar border-0 mr-3" style="background: #1D78FD;">
        <i class="far fa-user fa-fw"></i>
    </span>
    <h5 class="card-title">
        Members
        <small class="d-block text-uppercase">
            @if($chart['compare']['percent_diff'] != 0)
                <span class="{{ $chart['compare']['plus_minus'] == '+' ? 'text-color:green' : 'text-color:red' }}">
                    {{ $chart['compare']['plus_minus'] }}
                    {{ round(abs($chart['compare']['percent_diff'])) }}%
                </span>
                Dibandingkan periode lalu
                <span class="text-muted">({{ $chart['last_period']['sum'] }})</span>
            @else
                Sama dengan periode sebelumnya.
            @endif
        </small>
    </h5>
</div>
<div id="members_count_chart"></div>
<div class="card-footer">
    <div class="row text-center">
        <div class="col-4 border-right">
            <div class="text-muted text-uppercase font-weight-medium font-size:12">
                Members
            </div>
            <div style="font-size: 40px;">
                {{ $chart['sum'] }}
            </div>
            <div>
                <span
                    class="font-size:14 {{ $chart['compare']['plus_minus'] == '+' ? 'text-color:green' : 'text-color:red' }}">
                    {{ $chart['compare']['plus_minus'] }}
                    {{ round(abs($chart['compare']['percent_diff'])) }}%
                </span>
            </div>
        </div>
        <div class="col-4 border-right">
            <div class="text-muted text-uppercase font-weight-medium font-size:12">
                Business
            </div>
            <div style="font-size: 40px;">
                {{ $chart['total_business'] }}
            </div>
            @php($businessDiff = $chart['total_business'] - $chart['last_period']['total_business'])
            @php($businessDivider = $chart['last_period']['total_business'] > 0 ? $chart['last_period']['total_business'] : $chart['total_business'])
            <div>
                <span
                    class="font-size:14 {{ $businessDiff > 0 ? 'text-color:green' : 'text-color:red' }}">
                    {{ $businessDiff > 0 ? '+' : '-' }}
                    {{ round(abs($businessDiff / $businessDivider * 100)) }}%
                </span>
            </div>
        </div>
        <div class="col-4">
            <div class="text-muted text-uppercase font-weight-medium font-size:12">
                Unique Business
            </div>
            <div style="font-size: 40px;">
                {{ $chart['with_business_count'] }}
            </div>
            <div>
                <span
                    class="font-size:14 {{ $chart['compare']['plus_minus'] == '+' ? 'text-color:green' : 'text-color:red' }}">
                    {{ $chart['compare']['plus_minus'] }}
                    {{ round(abs($chart['compare']['percent_diff'])) }}%
                </span>
            </div>
        </div>
    </div>
</div>
<div x-show="loading" class="loading-dashboard"
     style="background-color: rgba(255, 255, 255, 1);"></div>
<script>
    Highcharts.chart('members_count_chart', {
        chart: {
            type: 'pie',
            height: 300
        },

        title: {
            width: 300,
            x: 30,
            y: 40,
            align: 'right',
            useHTML: true,
            text: '<div id="pieChartInfoText" class="chart__title-container"><div class="chart__title">{{ $chart['sum'] }}</div><div class="chart__subtitle">Total Member</div></div>'
        },

        legend: {
            align: 'right',
            layout: 'vertical',
            verticalAlign: 'bottom',
            itemMarginBottom: 10,
            x: 60,
            width: 300,
            useHTML: true,
            labelFormatter: function() {
                return '<div style="position:relative;width:200px;"><span style="float:left">' + this.name +
                    '</span><span style="position:absolute;right:0;">' + this.y + '</span></div>';
            }
        },

        colors: ['#1D78FD', '#EAECEE'],
        credits: {
            enabled: false
        },

        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },

        series: [
            {
                name: 'Members',
                innerSize: '50%',
                data: [
                    {
                        name: 'Unique Business Owner',
                        y: {{ $chart['with_business_count'] }}
                    },
                    {
                        name: 'Members Without Business',
                        y: {{ $chart['without_business_count'] }}
                    }
                ]
            }
        ],

        responsive: {
            rules: [
                {
                    condition: {
                        maxWidth: 400
                    },
                    chartOptions: {
                        title: {
                            width: 200,
                            x: -30
                        },
                        legend: {
                            align: 'right',
                            layout: 'vertical',
                            verticalAlign: 'bottom',
                            itemMarginBottom: 10,
                            x: 0,
                            width: 200,
                            useHTML: true,
                            labelFormatter: function() {
                                return '<div style="position:relative;width:170px;"><span style="float:left">' +
                                    this.name + '</span><span style="position:absolute;right:0;">' + this.y +
                                    '</span></div>';
                            }
                        },
                        series: [
                            {}, {
                                id: 'versions',
                                dataLabels: {
                                    enabled: false
                                }
                            }]
                    }
                }]
        }
    });
</script>
