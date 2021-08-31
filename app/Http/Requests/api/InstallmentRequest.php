<?php

namespace App\Http\Requests\api;

use App\Rules\UzbekPhone;
use Illuminate\Foundation\Http\FormRequest;

class InstallmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'phone' => ['required', 'string', new UzbekPhone],
            //'name' => 'required|string',
            'city' => 'nullable|string',
            'region' => 'nullable|string',
            'street' => 'nullable|string',
            'shop_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'quantity' => 'nullable|integer',
            'sum' => 'nullable|integer',
            'installment_card' => 'boolean|required',
            'installment_card_number' => 'required_if:installment_card,true|string',
            'installment_card_date' => 'required_if:installment_card,true|string',
            'installment_condition' => 'required|integer|min:0|max:3',
            'installment_firstname' => 'required|string',
            'installment_middlename' => 'required|string',
            'installment_lastname' => 'required|string',
            'installment_gender' => 'required|boolean',
            'installment_inn' => 'required|string',
            'installment_sn' => 'required|string',
            'installment_birthdate' => 'required|string',
            'installment_address' => 'required|string',
            'installment_issuedby' => 'required|string',
            'installment_issueddate' => 'required|string',
            'installment_passport' => 'required|file',
            'installment_passport_face' => 'required|file',
            'installment_passport_address' => 'required|file'
        ];
    }
}
