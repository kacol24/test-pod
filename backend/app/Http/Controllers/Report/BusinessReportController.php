<?php

namespace App\Http\Controllers\Report;

use App\Models\Business\Business;
use App\Models\User;

class BusinessReportController extends ReportController
{
    public function __invoke()
    {
        $users = User::whereHas('businesses', function ($business) {
            $business->whereBetween('created_at', [$this->startDate, $this->endDate]);
        })->get();
        [$male, $female] = $users->partition(function ($user) {
            return $user->gender == 'Male';
        });
        $data['gender'] = [
            'sum'        => $users->count(),
            'sum_male'   => $male->count(),
            'sum_female' => $female->count(),
        ];

        $data['company_types'] = $this->generateCompanyTypeReport();
        $data['company_sizes'] = $this->generateCompanySizeReport();
        $data['ownership'] = $this->generateOwnershipReport();
        $data['established_since'] = $this->generateEstablishedSinceReport();
        $data['location'] = $this->generateBusinessLocationReport();
        $data['category'] = $this->genereateBusinessCategoryReport();

        $data['top5'] = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                                ->withCount('visitors')
                                ->withCount('buttonClicks')
                                ->withCount('contactClicks')
                                ->withCount('couponClicks')
                                ->get()
                                ->sortByDesc('visitors_count')
                                ->take(5);

        return view('dashboard.business.business', $data);
    }

    protected function generateCompanyTypeReport()
    {
        $businesses = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                              ->orderBy('created_at')
                              ->get();
        $labels = [
            'PT'                   => 0,
            'CV'                   => 0,
            'Belum Berbadan Usaha' => 0,
        ];
        $businesses->each(function ($business) use (&$labels) {
            if (isset($labels[$business->company_type])) {
                $labels[$business->company_type]++;
            }
        });

        return [
            'sum'  => $businesses->count(),
            'data' => array_map(function ($companyType, $counter) {
                return [
                    'name' => $companyType,
                    'y'    => $counter,
                ];
            }, array_keys($labels), $labels),
        ];
    }

    protected function generateCompanySizeReport()
    {
        $businesses = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                              ->orderBy('created_at')
                              ->get();
        $labels = [
            '<10 orang'    => 0,
            '10-49 orang'  => 0,
            '50-249 orang' => 0,
            '>250 orang'   => 0,
        ];
        $businesses->each(function ($business) use (&$labels) {
            if (isset($labels[$business->company_size])) {
                $labels[$business->company_size]++;
            }
        });

        return [
            'sum'  => $businesses->count(),
            'data' => array_map(function ($companySize, $counter) {
                return [
                    'name' => $companySize,
                    'y'    => $counter,
                ];
            }, array_keys($labels), $labels),
        ];
    }

    protected function generateOwnershipReport()
    {
        $businesses = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                              ->orderBy('created_at')
                              ->get();
        $labels = [
            'Pribadi'   => 0,
            'Publik'    => 0,
            'Franchise' => 0,
            'Lainnya'   => 0,
        ];
        $businesses->each(function ($business) use (&$labels) {
            if (isset($labels[$business->ownership])) {
                $labels[$business->ownership]++;
            }
        });

        return [
            'sum'  => $businesses->count(),
            'data' => array_map(function ($ownership, $counter) {
                return [
                    'name' => $ownership,
                    'y'    => $counter,
                ];
            }, array_keys($labels), $labels),
        ];
    }

    protected function generateEstablishedSinceReport()
    {
        $businesses = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                              ->orderBy('created_at')
                              ->get();
        $labels = [
            '< 1 Tahun'  => 0,
            '1-5 Tahun'  => 0,
            '5-10 Tahun' => 0,
            '> 10 Tahun' => 0,
        ];
        $businesses->each(function ($business) use (&$labels) {
            switch ($business->establish_since) {
                case $business->establish_since == today()->format('Y'):
                    $labels['< 1 Tahun']++;
                    break;
                case $business->establish_since <= today()->subYears(1)
                                                          ->format('Y') && $business->establish_since > today()
                        ->subYears(5)->format('Y'):
                    $labels['1-5 Tahun']++;
                    break;
                case $business->establish_since <= today()->subYears(5)
                                                          ->format('Y') && $business->establish_since > today()
                        ->subYears(10)->format('Y'):
                    $labels['5-10 Tahun']++;
                    break;
                case $business->establish_since < today()->subYears(10)->format('Y'):
                    $labels['> 10 Tahun']++;
                    break;
            }
        });

        return [
            'sum'  => $businesses->count(),
            'data' => array_map(function ($ownership, $counter) {
                return [
                    'name' => $ownership,
                    'y'    => $counter,
                ];
            }, array_keys($labels), $labels),
        ];
    }

    protected function generateBusinessLocationReport()
    {
        $businesses = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                              ->orderBy('created_at')
                              ->get();

        $grouped = $businesses->groupBy(function ($business) {
            return $business->location;
        })->map(function ($value) {
            return $value->count();
        })->sortByDesc(function ($value) {
            return $value;
        })->take(4);

        return [
            'labels' => $grouped->keys(),
            'data'   => $grouped->values(),
        ];
    }

    protected function genereateBusinessCategoryReport()
    {
        $businesses = Business::whereBetween('created_at', [$this->startDate, $this->endDate])
                              ->orderBy('created_at')
                              ->get();

        $grouped = $businesses->groupBy(function ($business) {
            return $business->firstcategory()->title;
        })->map(function ($value) {
            return $value->count();
        })->sortByDesc(function ($value) {
            return $value;
        })->take(4);

        return [
            'labels' => $grouped->keys(),
            'data'   => $grouped->values(),
        ];
    }
}
