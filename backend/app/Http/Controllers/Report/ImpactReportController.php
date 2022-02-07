<?php

namespace App\Http\Controllers\Report;

use App\Models\ButtonClick;
use App\Models\ContactClick;
use App\Models\CouponClick;
use App\Models\Visitor;
use App\Services\HighchartReport;

class ImpactReportController extends ReportController
{
    public function __invoke()
    {
        $data['visitors'] = $this->generateReport(Visitor::class);

        $data['contacts'] = $this->generateReport(ContactClick::class);

        $data['buttons'] = $this->generateReport(ButtonClick::class);

        $data['coupons'] = $this->generateReport(CouponClick::class);

        $contactRate = round($data['contacts']['sum'] / $data['visitors']['sum'] * 100);
        $contactDivider = $data['visitors']['last_period']['sum'];
        if ($contactDivider == 0) {
            $contactDivider = $data['contacts']['sum'];
        }
        if ($contactDivider == 0) {
            $contactDivider = 100;
        }
        $contactLastPeriodRate = round($data['contacts']['last_period']['sum'] / $contactDivider * 100);
        $data['kpi'][] = [
            'title'            => 'Contact Clicks',
            'rate'             => $contactRate,
            'percent_diff'     => $contactRate - $contactLastPeriodRate,
            'last_period_rate' => $contactLastPeriodRate,
        ];

        $buttonRate = round($data['buttons']['sum'] / $data['visitors']['sum'] * 100);
        $buttonDivider = $data['visitors']['last_period']['sum'];
        if ($buttonDivider == 0) {
            $buttonDivider = $data['buttons']['sum'];
        }
        if ($buttonDivider == 0) {
            $buttonDivider = 100;
        }
        $buttonLastPeriodRate = round($data['buttons']['last_period']['sum'] / $buttonDivider * 100);
        $data['kpi'][] = [
            'title'            => 'Button Clicks',
            'rate'             => $buttonRate,
            'percent_diff'     => $buttonRate - $buttonLastPeriodRate,
            'last_period_rate' => $buttonLastPeriodRate,
        ];

        $couponRate = round($data['coupons']['sum'] / $data['visitors']['sum'] * 100);
        $couponDivider = $data['visitors']['last_period']['sum'];
        if ($couponDivider == 0) {
            $couponDivider = $data['coupons']['sum'];
        }
        if ($couponDivider == 0) {
            $couponDivider = 100;
        }
        $couponLastPeriodRate = round($data['coupons']['last_period']['sum'] / $couponDivider * 100);
        $data['kpi'][] = [
            'title'            => 'Coupon Clicks',
            'rate'             => $couponRate,
            'percent_diff'     => $couponRate - $couponLastPeriodRate,
            'last_period_rate' => $couponLastPeriodRate,
        ];

        return view('dashboard.impact.impact', $data);
    }

    protected function generateReport($model)
    {
        $highchartReport = new HighchartReport;
        $currentPeriod = [
            'start_date' => $this->startDate,
            'end_date'   => $this->endDate,
        ];
        $lastPeriod = [
            'start_date' => $this->beforeStartDate,
            'end_date'   => $this->beforeEndDate,
        ];
        $groupBy = $this->groupBy;

        $report = $highchartReport->generate(
            $model,
            $currentPeriod['start_date'],
            $currentPeriod['end_date'],
            $groupBy
        );
        $report['last_period'] = $highchartReport->generate(
            $model,
            $lastPeriod['start_date'],
            $lastPeriod['end_date'],
            $groupBy
        );
        $diff = $report['sum'] - $report['last_period']['sum'];
        $report['compare'] = [
            'plus_minus'   => $diff > 0 ? '+' : '-',
            'percent_diff' => 0,
        ];
        if ($report['last_period']['sum'] || $report['sum']) {
            $divider = $report['last_period']['sum'] > 0 ? $report['last_period']['sum'] : $report['sum'];
            $report['compare']['percent_diff'] = abs($diff / $divider * 100);
        }

        return $report;
    }
}
