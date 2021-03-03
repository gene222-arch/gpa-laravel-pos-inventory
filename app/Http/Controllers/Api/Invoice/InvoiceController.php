<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Models\Invoice;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\ShowRequest;
use App\Http\Requests\Invoice\DeleteRequest;
use App\Http\Requests\Invoice\UpdateRequest;

class InvoiceController extends Controller
{

    use ApiResponser;

    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->middleware(['auth:api', 'permission:Manage Invoices']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->invoice->all();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request)
    {
        $result = $this->invoice->showInvoiceWithDetails($request->invoice_id);

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }



    /**
     * Undocumented function
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $result = $this->invoice->updateStatus($request->invoice_ids);

        return !$result
            ? $this->error('Invoice status is not updated', 400)
            : $this->success($result,
        'Invoice status updated.',
        201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request)
    {
        $isSalesInvoiceDeleted = $this->invoice->deleteSalesInvoices(
            $request->invoice_ids
        );

        return (!$isSalesInvoiceDeleted)
            ? $this->error('Invoice status is not deleted', 400)
            : $this->success([],
            'Invoices deleted successfully.');
    }

}
