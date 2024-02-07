<?php

namespace Modules\Customer\App\Http\Requests;

use App\Http\Requests\FormRequest;

class CustomerFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['customer_group_id'] = ['required', 'integer'];
        $rules['name']              = ['required', 'string'];
        $rules['company_name']      = ['nullable', 'string'];
        $rules['tax_number']        = ['nullable', 'string'];
        $rules['phone']             = ['nullable', 'string', 'max:15', 'unique:customers,phone'];
        $rules['email']             = ['nullable', 'string', 'email', 'max:50', 'unique:customers,email'];
        $rules['address']           = ['nullable', 'string'];
        $rules['city']              = ['nullable', 'string'];
        $rules['state']             = ['nullable', 'string'];
        $rules['postal_code']       = ['nullable', 'string'];
        $rules['country']           = ['nullable', 'string'];

        if(request()->update_id){
            $rules['phone'][3]        = 'unique:customers,phone,'.request()->update_id;
            $rules['email'][4]        = 'unique:customers,email,'.request()->update_id;
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
