<?php

namespace App\Http\Controllers\Report;

use App\Models\Business\Business;
use App\Models\Business\BusinessCoupon;
use App\Models\User;

class MemberReportController extends ReportController
{
    public function __invoke()
    {
        $data['members'] = $this->generateMemberChartReport();
        $data['business'] = $this->generateBusinessChartReport();

        $coupons = BusinessCoupon::whereBetween('created_at', [$this->startDate, $this->endDate])
                                 ->orderBy('created_at')
                                 ->get();
        $lastPeriod = BusinessCoupon::whereBetween('created_at', [$this->beforeStartDate, $this->beforeEndDate])
                                    ->orderBy('created_at')
                                    ->get();
        $divider = $lastPeriod->count();
        if ($divider == 0) {
            $divider = $coupons->count();
        }
        if ($divider == 0) {
            $divider = 100;
        }
        $data['kpi'][] = [
            'title'            => 'Coupons Created',
            'rate'             => $coupons->count(),
            'last_period_rate' => $lastPeriod->count(),
            'percent_diff'     => ($coupons->count() - $lastPeriod->count()) / $divider * 100,
        ];

        $businessCoupons = Business::whereHas('coupons', function ($coupon) {
            $coupon->whereBetween('created_at', [$this->startDate, $this->endDate])
                   ->where('is_publish', true);
        })->get();
        $lastBusinessCoupons = Business::whereHas('coupons', function ($coupon) {
            $coupon->whereBetween('created_at', [$this->beforeStartDate, $this->beforeEndDate])
                   ->where('is_publish', true);
        })->get();
        $divider = $lastBusinessCoupons->count();
        if ($divider == 0) {
            $divider = $businessCoupons->count();
        }
        if ($divider == 0) {
            $divider = 100;
        }
        $data['kpi'][] = [
            'title'            => 'Business With Active Coupons',
            'rate'             => $businessCoupons->count(),
            'last_period_rate' => $lastBusinessCoupons->count(),
            'percent_diff'     => ($businessCoupons->count() - $lastBusinessCoupons->count()) / $divider * 100,
        ];

        return view('dashboard.members.members', $data);
    }

    protected function generateMemberChartReport()
    {
        $members = User::whereBetween('created_at', [$this->startDate, $this->endDate])
                       ->withCount('businesses')
                       ->get();
        [$with, $without] = $members->partition(function ($member) {
            return $member->businesses->count() > 0;
        });
        $data = [
            'with_business_count'    => $with->count(),
            'without_business_count' => $without->count(),
            'sum'                    => $members->count(),
            'total_business'         => $members->sum('businesses_count'),
        ];
        $lastMembers = User::whereBetween('created_at', [$this->beforeStartDate, $this->beforeEndDate])
                           ->withCount('businesses')
                           ->get();
        $data['last_period'] = [
            'sum'            => $lastMembers->count(),
            'total_business' => $lastMembers->sum('businesses_count'),
        ];

        $diff = $data['sum'] - $data['last_period']['sum'];
        $data['compare'] = [
            'plus_minus'   => $diff > 0 ? '+' : '-',
            'percent_diff' => 0,
        ];
        if ($data['last_period']['sum'] || $data['sum']) {
            $divider = $data['last_period']['sum'] > 0 ? $data['last_period']['sum'] : $data['sum'];
            $data['compare']['percent_diff'] = abs($diff / $divider * 100);
        }

        return $data;
    }

    protected function generateBusinessChartReport()
    {
        $business = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                            ->get();
        [$basic, $complete] = $business->partition(function ($record) {
            return $record->type == 'basic';
        });
        $data = [
            'sum'          => $business->count(),
            'sum_basic'    => $basic->count(),
            'sum_complete' => $complete->count(),
        ];

        $lastBusiness = Business::whereBetween('created_at', [$this->beforeStartDate, $this->beforeEndDate])
                                ->get();
        [$lastBasic, $lastComplete] = $lastBusiness->partition(function ($record) {
            return $record->type == 'basic';
        });

        $data['last_period'] = [
            'sum'          => $lastBusiness->count(),
            'sum_basic'    => $lastBasic->count(),
            'sum_complete' => $lastComplete->count(),
        ];

        $diff = $data['sum'] - $data['last_period']['sum'];
        $data['compare'] = [
            'plus_minus'   => $diff > 0 ? '+' : '-',
            'percent_diff' => 0,
        ];
        if ($data['last_period']['sum'] || $data['sum']) {
            $divider = $data['last_period']['sum'] > 0 ? $data['last_period']['sum'] : $data['sum'];
            $data['compare']['percent_diff'] = round(abs($diff / $divider * 100));
        }

        $diff = $data['sum_basic'] - $data['last_period']['sum_basic'];
        $data['compare_basic'] = [
            'plus_minus'   => $diff > 0 ? '+' : '-',
            'percent_diff' => 0,
        ];
        if ($data['last_period']['sum_basic'] || $data['sum_basic']) {
            $divider = $data['last_period']['sum_basic'] > 0 ? $data['last_period']['sum_basic'] : $data['sum_basic'];
            $data['compare_basic']['percent_diff'] = round(abs($diff / $divider * 100));
        }

        $diff = $data['sum_complete'] - $data['last_period']['sum_complete'];
        $data['compare_complete'] = [
            'plus_minus'   => $diff > 0 ? '+' : '-',
            'percent_diff' => 0,
        ];
        if ($data['last_period']['sum_complete'] || $data['sum_complete']) {
            $divider = $data['last_period']['sum_complete'] > 0 ? $data['last_period']['sum_complete'] : $data['sum_complete'];
            $data['compare_complete']['percent_diff'] = round(abs($diff / $divider * 100));
        }

        return $data;
    }
}
