<div id="transaction_chart"></div>
<script>
  Highcharts.chart('transaction_chart', {
    chart: {
      type: 'column'
    },
    title: {
      text: ''
    },
    credits: {
      enabled: false
    },
    xAxis: {
      type: 'datetime',
      tickInterval: 3600 * 1000 * 24,
      dateTimeLabelFormats: {
        day: '%e %b'
      }
    },
    yAxis: {
      min: 0,
      title: {
        text: ''
      }
    },
    legend: {
      align: 'left',
      verticalAlign: 'top',
      layout: 'horizontal',
    },
    tooltip: {
      headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
      pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
          '<td style="padding:0;padding-left:5px;"><b>{point.y}</b></td></tr>',
      footerFormat: '</table>',
      shared: true,
      useHTML: true
    },
    plotOptions: {
      column: {
        pointPadding: 0.2,
        borderWidth: 0
      }
    },
    series: [
      {
        color: 'gray',
        name: "{{Lang::get('dashboard.numberoftransaction')}}",
        data: [
          @foreach($transactions as $idx => $transaction) 
            @if($idx>0)
            , 
            @endif
            [Date.UTC({{date('Y',strtotime($transaction->date))}}, {{date('m',strtotime($transaction->date))}}-1, {{date('d',strtotime($transaction->date))}}), {{$transaction->transactions}}]
          @endforeach
        ],
        tooltip: {
          valueSuffix: ' '+" {{Lang::get('dashboard.transaction')}}"
        }

      }, {
        color: '#1665D8',
        name: "{{Lang::get('dashboard.numberofpaid')}}",
        data: [
          @foreach($paid_transactions as $idx => $transaction) 
            @if($idx>0)
            , 
            @endif
            [Date.UTC({{date('Y',strtotime($transaction->date))}}, {{date('m',strtotime($transaction->date))}}-1, {{date('d',strtotime($transaction->date))}}), {{$transaction->transactions}}]
          @endforeach
        ],
        tooltip: {
          valueSuffix: ' '+" {{Lang::get('dashboard.transaction')}}"
        }
      }]
  });
</script>