<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportMultiSheetExport;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function sales(Request $request): View
    {
        $start = $request->filled('start')
            ? Carbon::parse($request->start)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $end = $request->filled('end')
            ? Carbon::parse($request->end)->endOfDay()
            : now()->endOfDay();

        $userId = $request->filled('user_id') ? $request->user_id : null;

        $transactions = Transaction::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->get();

        $totalPenjualan = $transactions->sum('total');
        $jumlahTransaksi = $transactions->count();
        $cashiers = User::orderBy('name')->get(['id', 'name', 'role']);

        return view('reports.sales', compact(
            'transactions',
            'totalPenjualan',
            'jumlahTransaksi',
            'start',
            'end',
            'cashiers',
            'userId'
        ));
    }

    public function export(Request $request)
    {
        $start = $request->filled('start')
            ? Carbon::parse($request->start)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $end = $request->filled('end')
            ? Carbon::parse($request->end)->endOfDay()
            : now()->endOfDay();

        $userId = $request->filled('user_id') ? $request->user_id : null;

        $filename = 'laporan-penjualan-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.xlsx';

        return Excel::download(
            new SalesReportMultiSheetExport($start, $end, $userId),
            $filename
        );
    }
}
