<div class="card-header d-flex align-items-center">
    <span class="Avatar border-0 mr-3" style="background: #FFBD00;">
        <i class="fas fa-th-large fa-fw"></i>
    </span>
    <h5 class="card-title">
        Kategori/Industri
    </h5>
</div>
<div id="category_chart"></div>
<div x-show="loading" class="loading-dashboard"
     style="background-color: rgba(255, 255, 255, 1);"></div>
<script>
    Highcharts.chart('category_chart', {
        chart: {
            type: 'column'
        },

        title: false,

        colors: ['#FFBD00'],

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
                name: 'Kategori',
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
