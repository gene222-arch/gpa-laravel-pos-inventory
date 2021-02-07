<?php

namespace App\Http\Requests\PDFRequest;

use App\Http\Requests\BaseRequest;

class GenerateInvoiceRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_id' => ['required', 'integer', 'exists:invoices,id']
        ];
    }
}
