<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\HighchartReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $startDate;

    protected $endDate;

    protected $beforeStartDate;

    protected $beforeEndDate;

    protected $groupBy;

    public function __construct(Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $groupBy = $request->group_by ?? HighchartReport::GROUP_BY_DAY;

        $diff = $startDate->diffInDays($endDate);
        $beforeStartDate = $startDate->copy()->subDays($diff);
        $beforeEndDate = $endDate->copy()->subDays($diff);

        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->beforeStartDate = $beforeStartDate;
        $this->beforeEndDate = $beforeEndDate;
        $this->groupBy = $groupBy;
    }
}
