<?php

namespace App\Http\Requests\HubPaymentRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
'from_branch_id' => 'required|integer|exists:branches,id',
            // 'to_branch_id' => 'required|integer|exists:branches,id|different:from_branch_id',
            'transport_type' => 'required|in:by_road,by_air',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|in:kg,gram,liter,ml',

        ];
    }
}
