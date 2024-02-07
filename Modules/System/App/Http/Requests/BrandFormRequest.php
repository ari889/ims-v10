<?php

namespace Modules\System\App\Http\Requests;

use App\Http\Requests\FormRequest;

class BrandFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['title'] = ['required', 'string', 'unique:brands,title'];
        $rules['image'] = ['required', 'image', 'mimes:png,jpg,jpeg,gif'];
        if(request()->update_id){
            $rules['title'][2] = 'unique:brands,title,'.request()->update_id;
            $rules['image'][0] = 'unique:nullable';
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
