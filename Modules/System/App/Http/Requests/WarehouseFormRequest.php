<?php

namespace Modules\System\App\Http\Requests;

use App\Http\Requests\FormRequest;

class WarehouseFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['name'] = ['required', 'string', 'unique:warehouses,name'];
        $rules['phone'] = ['nullable', 'string', 'max:15'];
        $rules['email'] = ['nullable', 'string', 'max:50'];
        $rules['address'] = ['nullable', 'string'];
        if(request()->update_id){
            $rules['name'] = 'unique:warehouses,name,'.request()->update_id;
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
