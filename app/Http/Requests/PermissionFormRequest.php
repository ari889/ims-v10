<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class PermissionFormRequest extends FormRequest
{
    protected $rules = [];
    protected $messages = [];
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
        $this->rules['module_id'] = ['required', 'integer'];
        $this->messages['module_id.required'] = "This module field is required!";
        $this->messages['module_id.integer'] = "This module field value must be an integer!";
        

        $collection = collect(request());
        if($collection->has('permission')){
            foreach(request()->permission as $key => $value){
                $this->rules['permission.'.$key.'.name'] = ['required', 'string'];
                $this->rules['permission.'.$key.'.slug'] = ['required', 'string', 'unique:permissions,slug'];

                $this->messages['permission.'.$key.'.name.required'] = "This field name is required!";
                $this->messages['permission.'.$key.'.name.string'] = "This field value must be an integer!";
                $this->messages['permission.'.$key.'.slug.required'] = "This slug field name is required!";
                $this->messages['permission.'.$key.'.slug.string'] = "This slug field value must be an integer!";
                $this->messages['permission.'.$key.'.slug.unique'] = "This slug field value already been taken!";
            }
        }

        return $this->rules;
    }

    /**
     * return custom messages
     */
    public function messages(){
        return $this->messages;
    }
}
