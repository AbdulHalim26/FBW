<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportMultiSheetExport implements Export, WithMultipleSheets
{
    public function __construct(
        protected mixed $start,
        protected mixed $end,
        protected mixed $userId = null
    ) {}

    public function sheets(): array
    {
        return [
            new SalesExport($this->start, $this->end, $this->userId),
            new TopProductsExport($this->start, $this->end, $this->userId),
        ];
    }
}
