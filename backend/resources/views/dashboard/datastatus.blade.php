<div class="card-header d-flex align-items-center">
    <img src="{{asset('backend/images/icons/icon_bestsellers.png')}}" alt="" class="card-title-icon img-fluid">
    <h5 class="card-title">
        Transaction Rate
        <small class="d-block text-uppercase">
            <?php
                $selisih = $transaction_count - $prev_transaction_count;
                if($selisih && $prev_transaction_count) {
                  $diff = $selisih/$prev_transaction_count*100;
                }else {
                  $diff = 0;
                }
            ?>
            @if($selisih>0)
            <span class="text-color:green">+{{number_format($diff,2,",",".")}}%</span> Since last period
            @else
            <span class="text-color:red">-{{number_format($diff,2,",",".")}}%</span> Since last period
            @endif
        </small>
    </h5>
</div>
<div id="transaction_rate_chart"></div>
<script>
  <?php
    $pending =0;
    $paid = 0;
    $undershipment = 0;
    $complete = 0;
    $cancel = 0;
    $return = 0;
    if($status->where('id',1)->first()->transactions)
      $pending = $status->where('id',1)->first()->transactions;
    if($status->where('id',2)->first()->transactions)
      $paid = $status->where('id',2)->first()->transactions;
    if($status->where('id',3)->first()->transactions)
      $undershipment = $status->where('id',3)->first()->transactions;
    if($status->where('id',4)->first()->transactions)
      $complete = $status->where('id',4)->first()->transactions;
    if($status->where('id',5)->first()->transactions)
      $cancel = $status->where('id',5)->first()->transactions;
    if($status->where('id',6)->first()->transactions)
      $return = $status->where('id',6)->first()->transactions;
    $paidoff = $paid+$undershipment+$complete;
    $trans = $pending+$paid+$undershipment+$complete+$cancel+$return;
    if($paidoff && $trans)
      $pct_paid = $paidoff/$trans*100;
    else
      $pct_paid = 0;
  ?>
  Highcharts.chart('transaction_rate_chart', {
    colors: ['#BCBCBC','#1D78FD', '#FACF55', '#5BB381', '#CD375C', '#000000', '#24CBE5', '#FFF263', '#FF9655','#6AF9C4','#ED561B'],
    chart: {
      type: 'pie',
      marginTop:-50,
      height: 300
    },
    title: {
      width: 300,
      x:30,
      y:40,
      align: 'right',
      useHTML: true,
      text: '<div id="pieChartInfoText" style="position:absolute; text-align:left;width: 250px;padding-bottom: 20px;border-bottom: 1px solid gray;padding-left:30px;"><div style="font-size: 32px;text-align:left;">{{ number_format($pct_paid,1,",",".") }} %</div><div style="font-size: 16px;text-align:left;">Paid Transactions</div></div>'
    },
    credits: {
      enabled: false
    },
    legend: {
      align: 'right',
      layout: 'vertical',
      verticalAlign: 'bottom',
      itemMarginBottom: 10,
      x:60,
      width: 300,
      useHTML: true,
      labelFormatter: function() {
        return '<div style="position:relative;width:200px;"><span style="float:left">' + this.name + '</span><span style="position:absolute;right:0;">' + this.y + '</span></div>';
      }
    },

    plotOptions: {
      pie: {
          shadow: false,
      }
    },
    tooltip: {
      // formatter: function() {
      //   return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
      // }
      pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },

    series: [{
      name: "{{Lang::get('dashboard.transaction')}}",
      data: [
        ["{{Lang::get('dashboard.waitingpayment')}}", {{$pending}}],
        ["{{Lang::get('dashboard.paid')}}", {{$paid}}],
        ["{{Lang::get('dashboard.undershipment')}}", {{$undershipment}}],
        ["{{Lang::get('dashboard.completed')}}", {{$complete}}],
        ["{{Lang::get('dashboard.cancelled')}}", {{$cancel}}],
        ["{{Lang::get('dashboard.returned')}}", {{$return}}]
      ],
      innerSize: '60%',
      showInLegend:true,
      dataLabels: {
          enabled: false
      }
    }],
    responsive: {
      rules: [
        {
          condition: {
            maxWidth: 400
          },
          chartOptions: {
            title: {
              width: 200,
              x:-30,
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
                return '<div style="position:relative;width:170px;"><span style="float:left">' + this.name + '</span><span style="position:absolute;right:0;">' + this.y + '</span></div>';
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
