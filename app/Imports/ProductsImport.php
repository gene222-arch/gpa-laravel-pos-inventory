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
            'id' => ['required', 'integer'],
            '*.sku' => ['required', 'alpha_num', 'min:1', 'max:13', 'unique:products,sku,' . $this->id],
            '*.barcode' => ['required', 'alpha_num', 'min:1', 'max:13', 'unique:products,barcode,' . $this->id],
            '*.product_description' => ['required', 'string', 'unique:products,name'],
            '*.category' => ['required', 'exists:categories,name'],
            '*.sold_by' => ['required', 'in:each,weight/volume'],
            '*.price' => ['numeric', 'nullable'],
            '*.cost' => ['required', 'numeric'],
        ];
    }


    /**
     * @return array
     */
    public function customValidationAttributes()
    {
        return [
            'id' => 'product id',
            '*.product_description' => 'product description',
            '*.sold_by' => 'sold by',
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
            'image' => 'http://127.0.0.1:8000/storage/images/Products/product_default_img_1614450024.svg',
            'category' => Category::where('name', '=', $row['category'])->first()->id,
            'sold_by' => $row['sold_by'],
            'price' => $row['price'],
            'cost' => $row['cost'],
        ]);
    }
    
}
