<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithUpserts, WithValidation, WithBatchInserts, WithChunkReading
{
    use Importable;

    public function rules(): array
    {
        return [
            '*.sku' => ['required', 'alpha_num', 'min:1', 'max:13'],
            '*.barcode' => ['required', 'alpha_num', 'min:1', 'max:13'],
            '*.product_description' => ['required', 'string'],
            '*.category' => ['required', 'exists:categories,name'],
            '*.sold_by' => ['required', 'in:each,weight/volume'],
            '*.price' => ['numeric', 'nullable'],
            '*.cost' => ['required', 'numeric'],
        ];
    }


    public function uniqueBy()
    {
        return [
            'sku',
            'barcode'
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
       return new Product([
            'id' => $row['id'],
            'sku' => $row['sku'],
            'barcode' => $row['barcode'],
            'name' => $row['product_description'],
            'category' => Category::where('name', '=', $row['category'])->first()->id,
            'sold_by' => $row['sold_by'],
            'price' => $row['price'],
            'cost' => $row['cost'],
        ]);
    }
}
