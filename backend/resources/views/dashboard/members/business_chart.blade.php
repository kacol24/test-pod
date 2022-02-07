<div class="card-header d-flex align-items-center">
    <span class="Avatar border-0 mr-3" style="background: #1D78FD;">
        <i class="fas fa-briefcase fa-fw"></i>
    </span>
    <h5 class="card-title">
        Profile Business
        <small class="d-block text-uppercase">
            @if($chart['compare']['percent_diff'] != 0)
                <span class="{{ $chart['compare']['plus_minus'] == '+' ? 'text-color:green' : 'text-color:red' }}">
                    {{ $chart['compare']['plus_minus'] }}
                    {{ number_format($chart['compare']['percent_diff'], 0, ',', '.') }}%
                </span>
                Dibandingkan periode lalu
                <span class="text-muted">({{ $chart['last_period']['sum'] }})</span>
            @else
                Sama dengan periode sebelumnya.
            @endif
        </small>
    </h5>
</div>
<div id="business_count_chart"></div>
<div class="card-footer">
    <div class="row text-center">
        <div class="col border-right">
            <div class="text-muted text-uppercase font-weight-medium font-size:12">
                Basic Profile Business
            </div>
            <div style="font-size: 40px;">
                {{ $chart['sum_basic'] }}
            </div>
            <div>
                <span
                    class="font-size:14 {{ $chart['compare_basic']['plus_minus'] == '+' ? 'text-color:green' : 'text-color:red' }}">
                    {{ $chart['compare_basic']['plus_minus'] }}
                    {{ number_format($chart['compare_basic']['percent_diff'], 0, ',', '.') }}%
                </span>
            </div>
        </div>
        <div class="col">
            <div class="text-muted text-uppercase font-weight-medium font-size:12">
                Completed Profile Business
            </div>
            <div style="font-size: 40px;">
                {{ $chart['sum_complete'] }}
            </div>
            <div>
                <span
                    class="font-size:14 {{ $chart['compare_complete']['plus_minus'] == '+' ? 'text-color:green' : 'text-color:red' }}">
                    {{ $chart['compare_complete']['plus_minus'] }}
                    {{ number_format($chart['compare_complete']['percent_diff'], 0, ',', '.') }}%
                </span>
            </div>
        </div>
    </div>
</div>
<div x-show="loading" class="loading-dashboard"
     style="background-color: rgba(255, 255, 255, 1);"></div>
<script>
    Highcharts.chart('business_count_chart', {
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
            text: '<div id="pieChartInfoText" class="chart__title-container"><div class="chart__title">{{ $chart['sum'] }}</div><div class="chart__subtitle">Total Business</div></div>'
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

        colors: ['#1D78FD', '#34AA44'],
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
                showInLegend: true,
            }
        },

        series: [
            {
                name: 'Members',
                innerSize: '50%',
                data: [
                    {
                        name: 'Basic Profile',
                        y: {{ $chart['sum_basic'] }}
                    },
                    {
                        name: 'Completed Profile',
                        y: {{ $chart['sum_complete'] }}
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
