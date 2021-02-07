<?php

namespace Tests\Feature\Http\Controllers\Api\Imports;

use App\Imports\ProductsImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Excel;

class ImportProductsTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_import_products_file()
    {
        $this->actingAsAdmin();

        $file = UploadedFile::fake()->create('myexcel.csv');

        Excel::fake();

        $this->post('api/import/products', [
            'file' => $file
        ]);

        Excel::assertImported('myexcel.csv');

    }


}
