<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use DateTime;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportView()
    {
        return view('backend.report.report_view');
    }

    public function reportByDate(Request $request)
    {
        try {
            $fromDate = new DateTime($request->from_date);
            $toDate = new DateTime($request->to_date);
        } catch (\Exception $e) {
        }
        $formatFromDate = $fromDate->format('d F Y');
        $formatToDate = $toDate->format('d F Y');
        $orders = Order::whereBetween('order_date', [$formatFromDate, $formatToDate])->get();
        return view('backend.report.report_show', compact('orders'));
    }

    public function reportByMonth(Request $request)
    {
        $orders = Order::where(['order_month' => $request->month, 'order_year' => $request->year])->get();
        return view('backend.report.report_show', compact('orders'));
    }

    public function reportByYear(Request $request)
    {
        $orders = Order::where(['order_year' => $request->year])->get();
        return view('backend.report.report_show', compact('orders'));
    }
}
