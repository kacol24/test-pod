<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HighchartReport
{
    const GROUP_BY_DAY = 'day';

    const GROUP_BY_WEEK = 'week';

    const GROUP_BY_MONTH = 'month';

    public function generate($model, $startDate, $endDate, $groupBy = self::GROUP_BY_DAY)
    {
        $labels = [];
        $period = CarbonPeriod::create($startDate, '1 '.$groupBy, $endDate)->toArray();

        $visitors = $model::whereBetween('date', [$startDate, $endDate])
                          ->orderBy('date')
                          ->get()
                          ->when($groupBy == self::GROUP_BY_MONTH, function ($query) use ($labels, $period) {
                              foreach ($period as $date) {
                                  $labels[$date->format('F Y')] = 0;
                              }

                              return $query->groupBy(function ($record) {
                                  return Carbon::parse($record->date)->format('F Y');
                              });
                          })
                          ->when($groupBy == self::GROUP_BY_WEEK, function ($query) use ($labels, $period) {
                              foreach ($period as $date) {
                                  $labels[$date->format('M').' week '.$date->weekOfMonth] = 0;
                              }

                              return $query->groupBy(function ($record) {
                                  $date = Carbon::parse($record->date);

                                  return $date->format('M').' week '.$date->weekOfMonth;
                              });
                          })
                          ->when(! $groupBy || $groupBy == self::GROUP_BY_DAY,
                              function ($query) use ($labels, $period) {
                                  foreach ($period as $date) {
                                      $labels[$date->format('Y-m-d')] = 0;
                                  }

                                  return $query->groupBy('date');
                              })
                          ->map(function ($grouped) {
                              return $grouped->count();
                          })->toArray();
        $visitorData = array_merge($labels, $visitors);

        return [
            'data'          => array_values($visitorData),
            'labels'        => array_keys($visitorData),
            'sum'           => $sum = array_sum(array_values($visitorData)),
            'sum_formatted' => number_format($sum, 0, ',', '.'),
        ];
    }
}
