<div id="container" class="col-md-12 no-pad">

</div>
<script type="text/javascript">
  $(function(){
    Highcharts.chart('container', {
    title: {
      text: "{{Lang::get('dashboard.productdata')}}"
    },
    credits: {
      enabled: false
    },
    yAxis: [{
      title: {
        text: ''
      },
      labels: {
        style: {
          color: 'green',
        }
      },
    }, {
      title: {
        text: ''
      },
      labels: {
        style: {
          color: '#9EA0A5',
        }
      },
    }],

    xAxis: {
      type: 'datetime',
      tickInterval: 3600 * 1000 * 24,
      dateTimeLabelFormats: {
        day: '%e %b'
      }
    },

    legend: {
      align: 'left',
      verticalAlign: 'top',
      layout: 'horizontal',
    },

    tooltip: {
      shared: true 
    },
    plotOptions: {
      line: {
        dataLabels: {
          enabled: true
        },
        enableMouseTracking: true
      }
    },

    series: [{
      yAxis: 0,
      color: '#cccccc',
      name: "{{Lang::get('dashboard.viewedproduct')}}",
      data: [
        <?php 
          $idx = 0;
        ?>
        @foreach($product_views as $date => $view) 
          @if($idx>0)
          , 
          @endif
          [Date.UTC({{date('Y',strtotime($date))}}, {{date('m',strtotime($date))-1}}, {{date('d',strtotime($date))}}), {{$view}}]
          <?php $idx++; ?>
        @endforeach
      ],
      tooltip: {
        valueSuffix: ' Producs'
      }
    },{
      yAxis: 1,
      color: '#1665D8',
      name: "{{Lang::get('dashboard.soldproduct')}}",
      data: [
        @foreach($product_solds as $idx => $sold) 
          @if($idx>0)
          , 
          @endif
          [Date.UTC({{date('Y',strtotime($sold->created_at))}}, {{date('m',strtotime($sold->created_at))-1}}, {{date('d',strtotime($sold->created_at))}}), {{$sold->sold_product}}]
        @endforeach
      ],
      tooltip: {
        valueSuffix: ' Products'
      }
    }],
    responsive: {
      rules: [
        {
          condition: {
            maxWidth: 500
          },
          chartOptions: {
            legend: {
              align: 'left',
              verticalAlign: 'top',
              layout: 'horizontal',
            },
          }
        }]
    }
  });
  })
</script>
<style type="text/css">
  #highcharts-0 {
    left: 0 !important;
  }
</style>