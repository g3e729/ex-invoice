<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validation = [
            'POST' => [
                'name'                  => 'required',
                'address'               => 'required',
                'invoice_date'          => 'required',
                'invoice_number'        => 'required',
                'due_date'              => 'required',
                'products.*.product_id' => 'required',
                'products.*.quantity'   => 'required|min:1',
                'products.*.tax'        => 'required',
                'payments.*.type'       => 'required',
                'payments.*.amount'     => 'required',
            ],
            'PATCH' => [],
        ];

        return $validation[$this->method()] ?? [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'products.*.product_id.required'        => 'Please select product',
            'products.*.quantity.required'  => 'The quantity field is required',
            'products.*.quantity.min'       => 'The quantity must be at least :min.',
            'products.*.tax.required'       => 'The tax field is required',
            'payments.*.type.required'      => 'The type field is required',
            'payments.*.amount.required'    => 'The amount field is required',
        ];
    }
}
