<?php

namespace App\Imports;

use App\Models\Stock;
use App\Models\Supplier;
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
            '*.id' => ['required', 'integer'],
            '*.supplier' => ['required', 'string', 'exists:suppliers,name'],
            '*.in_stock' => ['required', 'integer', 'min:0'],
            '*.stock_in' => ['required', 'integer', 'min:0'],
            '*.stock_out' => ['required', 'integer', 'min:0'],
            '*.minimum_reorder_level' => ['required', 'integer', 'min:1'],
            '*.default_purchase_costs' => ['required', 'numeric', 'min:0'],
        ];
    }

    
    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            '*.id' => 'product id',
            '*.supplier' => 'supplier',
            '*.in_stock' => 'in stock',
            '*.stock_in' => 'stock in',
            '*.stock_out' => 'stock out',
            '*.minimum_reorder_level' => 'minimum reorder level',
            '*.default_purchase_costs' => 'default purchase costs',
        ];
    }


    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '*.supplier.exists' => 'The :attribute does not exist.',
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
            'supplier_id' => Supplier::where('name', '=', $row['supplier'])->first()->id,
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
