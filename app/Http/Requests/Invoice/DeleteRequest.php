<?php

namespace App\Http\Requests\Invoice;

use App\Http\Requests\BaseRequest;

class DeleteRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice_ids.*' => ['required', 'integer', 'distinct', 'exists:invoices,id']
        ];
    }
}
