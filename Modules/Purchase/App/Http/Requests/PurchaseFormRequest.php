<?php

namespace Modules\Purchase\App\Http\Requests;

use App\Http\Requests\FormRequest;

class PurchaseFormRequest extends FormRequest
{
    protected $rules;
    protected $messages;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['warehouse_id']    = ['required'];
        $this->rules['supplier_id']     = ['required'];
        $this->rules['purchase_status'] = ['required'];
        if(request()->has('products')){
            foreach (request()->products as $key => $value) {
                $this->rules   ['products.'.$key.'.qty']          = ['required', 'numeric', 'gt:0'];
                $this->messages['products.'.$key.'.qty.required'] = 'This field is required';
                $this->messages['products.'.$key.'.qty.numeric']  = 'This field is numeric';
                $this->messages['products.'.$key.'.qty.gt']       = 'This field is grater than 0';
            }
        }
        return $this->rules;
    }

    /**
     * send request message
     */
    public function messages(){
        return $this->messages;
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
