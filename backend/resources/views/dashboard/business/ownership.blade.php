<div class="card-header d-flex align-items-center">
    <span class="Avatar border-0 mr-3" style="background: #1D78FD;">
        <i class="fas fa-key fa-fw"></i>
    </span>
    <h5 class="card-title">
        Ownership
    </h5>
</div>
<div id="ownership_chart"></div>
<div x-show="loading" class="loading-dashboard"
     style="background-color: rgba(255, 255, 255, 1);"></div>
<script>
    Highcharts.chart('ownership_chart', {
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

        colors: ['#1D78FD', '#FFBD00', '#6758F3', '#00C187'],
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
                name: 'Company Type',
                innerSize: '50%',
                data: @json($chart['data'])
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
