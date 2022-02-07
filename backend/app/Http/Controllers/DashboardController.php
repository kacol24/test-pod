<?php

namespace App\Http\Controllers;

use App\Models\Business\Business;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboard(Request $request)
    {
        $end_date = date('d-m-Y');
        $start_date = date('d-m-Y', strtotime($end_date."-1 month"));
        if ($request->has('start_date')) {
            $start_date = date('d-m-Y', strtotime($request->input('start_date')));
        }
        if ($request->has('end_date')) {
            $end_date = date('d-m-Y', strtotime($request->input('end_date')));
        }

        $data = [
            'page_title' => 'Dashboard',
            'active'     => 'dashboard',
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ];

        return view('dashboard/dashboard', $data);
    }

    public function getHeader(Request $request)
    {
        $start_date = date("Y-m-d", strtotime($request->input('start_date')));
        $end_date = date("Y-m-d", strtotime($request->input('end_date')));
        $date_diff = interval_day($start_date, $end_date);
        $prev_end_date = $start_date;
        $prev_start_date = date('Y-m-d', strtotime($prev_end_date.'-'.$date_diff.' days'));

        $data = [
            'total_business' => [
                'sum'         => Business::where('created_at', '<=', $end_date)->count(),
                'last_period' => [
                    'sum' => Business::where('created_at', '<=', $prev_end_date)->count(),
                ],
            ],

            'total_basic_business' => [
                'sum'         => Business::where('created_at', '<=', $end_date)
                                         ->where('type', Business::TYPE_BASIC)
                                         ->count(),
                'last_period' => [
                    'sum' => Business::where('created_at', '<=', $prev_end_date)
                                     ->where('type', Business::TYPE_BASIC)
                                     ->count(),
                ],
            ],

            'total_complete' => [
                'sum'         => Business::where('created_at', '<=', $end_date)
                                         ->where('type', Business::TYPE_COMPLETE)
                                         ->count(),
                'last_period' => [
                    'sum' => Business::where('created_at', '<=', $prev_end_date)
                                     ->where('type', Business::TYPE_COMPLETE)
                                     ->count(),
                ],
            ],

            'total_member' => [
                'sum'         => User::where('created_at', '<=', $end_date)
                                     ->count(),
                'last_period' => [
                    'sum' => User::where('created_at', '<=', $prev_end_date)
                                 ->count(),
                ],
            ],

            'total_treasure_arise' => [
                'sum'         => Business::where('created_at', '<=', $end_date)
                                         ->where('is_treasure_arise', true)
                                         ->count(),
                'last_period' => [
                    'sum' => Business::where('created_at', '<=', $prev_end_date)
                                     ->where('is_treasure_arise', true)
                                     ->count(),
                ],
            ],
        ];

        return view('dashboard/header', $data);
    }
}
