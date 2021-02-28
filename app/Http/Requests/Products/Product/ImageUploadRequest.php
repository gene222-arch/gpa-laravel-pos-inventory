<?php

namespace App\Http\Requests\Products\Product;

use App\Http\Requests\BaseRequest;

class ImageUploadRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_image' => ['nullable', 'image', 'mimes:jpeg,png,svg', 'max:1999']
        ];
    }
}
