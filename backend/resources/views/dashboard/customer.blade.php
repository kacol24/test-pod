<div class="card-header d-flex align-items-center">
    <img src="{{asset('backend/images/icons/icon_customers.png')}}" alt="" class="card-title-icon img-fluid">
    <h5 class="card-title">
        {{Lang::get('dashboard.customer')}}
        <small class="d-block text-uppercase">
            <?php
                if(!$prev_buyer)
                  $prev_buyer = $buyer;
                $selisih = $buyer - $prev_buyer;
                if($selisih && $prev_buyer) {
                  $diff = $selisih/$prev_buyer*100;
                }
                else {
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
<div id="customers_chart"></div>
<?php
  $total_old = 0;
  $under17 = 0;
  $under24 = 0;
  $under34 = 0;
  $under44 = 0;
  $up45 = 0;
  $others = 0;
  foreach($olds as $old) {
    if($old->old) {
      if($old->old <=17) {
        $under17 += $old->transactions;
      }else if($old->old >=18 && $old->old <=24) {
        $under24 += $old->transactions;
      }else if($old->old >=25 && $old->old <=34) {
        $under34 += $old->transactions;
      }else if($old->old >=35 && $old->old <=44) {
        $under44 += $old->transactions;
      }else if($old->old >=45) {
        $up45 += $old->transactions;
      }
    }else
    {
      $others += $old->transactions;
    }

    $total_old += $old->transactions;
  }
  if($total_old && $under17) {
    $under17 = round($under17/$total_old*100,0);
  }

  if($total_old && $under24) {
    $under24 = round($under24/$total_old*100,0);
  }

  if($total_old && $under34) {
    $under34 = round($under34/$total_old*100,0);
  }

  if($total_old && $under44) {
    $under44 = round($under44/$total_old*100,0);
  }

  if($total_old && $up45) {
    $up45 = round($up45/$total_old*100,0);
  }

  if($total_old && $others) {
    $others = round($others/$total_old*100,0);
  }
?>
<div class="m-3">
    <div class="d-flex justify-content-between">
        <label for="" class="filter-label">
            &lt;=17 {{Lang::get('dashboard.year')}}
        </label>
        <span>
            {{$under17}}%
        </span>
    </div>
    <div class="progress" style="height: 4px;">
        <div class="progress-bar" style="width:{{$under17}}%;" role="progressbar" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
</div>
<hr>
<div class="m-3">
    <div class="d-flex justify-content-between">
        <label for="" class="filter-label">
            18-24 {{Lang::get('dashboard.year')}}
        </label>
        <span>
            {{$under24}}%
        </span>
    </div>
    <div class="progress" style="height: 4px;">
        <div class="progress-bar" style="width:{{$under24}}%;" role="progressbar" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
</div>
<hr>
<div class="m-3">
    <div class="d-flex justify-content-between">
        <label for="" class="filter-label">
            25-34 {{Lang::get('dashboard.year')}}
        </label>
        <span>
            {{$under34}}%
        </span>
    </div>
    <div class="progress" style="height: 4px;">
        <div class="progress-bar" style="width:{{$under34}}%;" role="progressbar" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
</div>
<hr>
<div class="m-3">
    <div class="d-flex justify-content-between">
        <label for="" class="filter-label">
            35-44 {{Lang::get('dashboard.year')}}
        </label>
        <span>
            {{$under44}}%
        </span>
    </div>
    <div class="progress" style="height: 4px;">
        <div class="progress-bar" style="width:{{$under44}}%;" role="progressbar" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
</div>
<hr>
<div class="m-3">
    <div class="d-flex justify-content-between">
        <label for="" class="filter-label">
            45+ {{Lang::get('dashboard.year')}}
        </label>
        <span>
            {{$up45}}%
        </span>
    </div>
    <div class="progress" style="height: 4px;">
        <div class="progress-bar" style="width:{{$up45}}%;" role="progressbar" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
</div>
<hr>
<div class="m-3">
    <div class="d-flex justify-content-between">
        <label for="" class="filter-label">
            {{Lang::get('dashboard.notspecified')}}
        </label>
        <span>
            {{$others}}%
        </span>
    </div>
    <div class="progress" style="height: 4px;">
        <div class="progress-bar" style="width:{{$others}}%;" role="progressbar" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100"></div>
    </div>
</div>
<script>
<?php
  $male = 0;
  $female = 0;
  $others = 0;
  if(isset($gender->where('gender','male')->first()->buyer))
    $male = $gender->where('gender','male')->first()->buyer;
  if(isset($gender->where('gender','female')->first()->buyer))
    $female = $gender->where('gender','female')->first()->buyer;
  if(isset($gender->where('gender','others')->first()->buyer))
    $others = $gender->where('gender','others')->first()->buyer;
?>
  Highcharts.chart('customers_chart', {
    colors: ['#1665D8','#CD375C','grey','#1665D8', '#DDDF00', '#64E572', '#ff0000', '#000000', '#24CBE5', '#FFF263', '#FF9655','#6AF9C4','#ED561B'],
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
      text: '<div id="pieChartInfoText" style="position:absolute; text-align:left;width: 250px;padding-bottom: 20px;border-bottom: 1px solid gray;padding-left:30px;"><div style="font-size: 32px;text-align:left;">{{ number_format($buyer,0,",",".") }}</div><div style="font-size: 16px;text-align:left;">{{Lang::get('dashboard.customer')}}</div></div>'
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
      name: "{{Lang::get('dashboard.customer')}}",
      data: [
        ["{{Lang::get('dashboard.male')}}", {{$male}}],
        ["{{Lang::get('dashboard.female')}}", {{$female}}],
        ["{{Lang::get('dashboard.notspecified')}}", {{$others}}]
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
