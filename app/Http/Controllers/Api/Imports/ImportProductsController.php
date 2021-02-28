<?php

namespace App\Http\Controllers\Api\Imports;

use Excel;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Imports\StocksImport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Imports\ImportProductRequest;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportProductsController extends Controller
{

    use ApiResponser;


    /**
     * Undocumented function
     *
     * @param ImportProductRequest $request
     * @return Illuminate\Http\JsonResponse
     */
    public function import(ImportProductRequest $request)
    {
        try{
            DB::transaction(function () use($request)
            {
                foreach ($request->files as $file)
                {
                    (new ProductsImport())->import($file);
                    (new StocksImport())->import($file);
                }
            });
        }
        catch (ValidationException $e)
        {
            return $this->error([
                'errors' => $e->failures(),
                'errorHeader' => 'Errors found in the file, refreshing selected file in order to apply upcoming changes.'
            ], 422);
        }

        return $this->success([],
        'Database updated',
        201);
    }

}
