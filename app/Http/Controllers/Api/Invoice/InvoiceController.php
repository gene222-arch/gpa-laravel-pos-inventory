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
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', $this->invoice);

        return $this->success($this->invoice->all(),
        'Success');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->invoice);

        $invoiceDetails = $this->invoice->showInvoiceWithDetails($request->invoice_id);

        return $this->success($invoiceDetails,
        'Success');
    }



    /**
     * Undocumented function
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $this->authorize('update', $this->invoice);

        $invoiceDetails = $this->invoice->paid($request->invoice_ids);

        return $this->success($invoiceDetails,
        'Success',
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
        $this->authorize('delete', $this->invoice);

        $isSalesInvoiceDeleted = $this->invoice->deleteSalesInvoices(
            $request->invoice_ids
        );

        return (!$isSalesInvoiceDeleted)
            ? $this->serverError()
            : $this->success([],
            'Success');
    }

}
