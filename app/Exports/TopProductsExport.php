<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TopProductsExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        protected mixed $start,
        protected mixed $end,
        protected mixed $userId = null
    ) {}

    public function collection(): Collection
    {
        return DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->whereBetween('transactions.created_at', [$this->start, $this->end])
            ->when($this->userId, fn($q) => $q->where('transactions.user_id', $this->userId))
            ->select('products.code', 'products.name')
            ->selectRaw('SUM(transaction_details.qty) as total_qty, SUM(transaction_details.subtotal) as total_omzet')
            ->groupBy('products.id', 'products.code', 'products.name')
            ->orderByDesc('total_qty')
            ->get();
    }

    public function headings(): array
    {
        return ['Kode Produk', 'Nama Produk', 'Total Qty Terjual', 'Total Penjualan (Rp)'];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->name,
            (int) $row->total_qty,
            (int) $row->total_omzet,
        ];
    }

    public function title(): string
    {
        return 'Produk Terlaris';
    }
}
