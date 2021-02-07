<?php

namespace App\Imports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StocksImport implements ToModel, WithHeadingRow, WithUpserts, WithValidation, WithBatchInserts, WithChunkReading
{

    use Importable;

    public function rules(): array
    {
        return [
            '*.supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            '*.in_stock' => ['required', 'integer', 'min:0'],
            '*.stock_in' => ['required', 'integer', 'min:0'],
            '*.stock_out' => ['required', 'integer', 'min:0'],
            '*.minimum_reorder_level' => ['required', 'integer', 'min:1'],
            '*.default_purchase_costs' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function uniqueBy()
    {
        return [
            'product_id'
        ];
    }


    public function batchSize(): int
    {
        return 1000;
    }


    public function chunkSize(): int
    {
        return 1000;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Stock([
            'product_id' => $row['id'],
            'supplier_id' => $row['supplier_id'],
            'in_stock' => $row['in_stock'],
            'bad_order_stock' => $row['bad_order_stock'],
            'stock_in' => $row['stock_in'],
            'stock_out' => $row['stock_out'],
            'minimum_reorder_level' => $row['minimum_reorder_level'],
            'incoming' => $row['incoming'],
            'default_purchase_costs' => $row['default_purchase_costs']
        ]);
    }
}
