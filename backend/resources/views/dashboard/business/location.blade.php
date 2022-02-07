<div class="card-header d-flex align-items-center">
    <span class="Avatar border-0 mr-3" style="background: #CD375C;">
        <i class="fas fa-map-marker-alt fa-fw"></i>
    </span>
    <h5 class="card-title">
        Lokasi Kantor
    </h5>
</div>
<div id="location_chart"></div>
<div x-show="loading" class="loading-dashboard"
     style="background-color: rgba(255, 255, 255, 1);"></div>
<script>
    Highcharts.chart('location_chart', {
        chart: {
            type: 'column'
        },

        title: false,

        colors: ['#CD375C'],

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
                name: 'Lokasi',
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
