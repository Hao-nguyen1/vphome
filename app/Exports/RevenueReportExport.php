<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RevenueReportExport implements FromView
{
    protected $reports;
    protected $totalSummaryRevenue;

    public function __construct($reports, $totalSummaryRevenue)
    {
        $this->reports = $reports;
        $this->totalSummaryRevenue = $totalSummaryRevenue;
    }

    public function view(): View
    {
        return view('backend.report.export', [
            'reports' => $this->reports,
            'totalSummaryRevenue' => $this->totalSummaryRevenue,
        ]);
    }
}