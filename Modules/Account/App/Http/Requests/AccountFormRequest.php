<?php

namespace Modules\Account\App\Http\Requests;

use App\Http\Requests\FormRequest;

class AccountFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['account_no'] = ['required', 'string', 'unique:accounts,account_no'];
        $rules['name'] = ['required', 'string', 'unique:accounts,name'];
        $rules['initial_balance'] = ['nullable', 'numeric', 'gte:0'];
        $rules['note'] = ['nullable', 'string'];
        if(request()->update_id){
            $rules['account_no'][2] = 'unique:accounts,account_No,'.request()->update_id;
            $rules['name'][2] = 'unique:accounts,name,'.request()->update_id;
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
