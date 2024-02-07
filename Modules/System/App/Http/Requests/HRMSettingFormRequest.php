<?php

namespace Modules\System\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HRMSettingFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'check_in'=>'required',
            'check_out'=>'required',
        ];
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
