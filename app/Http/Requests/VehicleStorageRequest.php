<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleStorageRequest extends BaseFormRequest
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
            'plate' =>[
                'required',
                'min:5',
                'max:12',                
                Rule::unique('vehicles', 'plate')->ignore($this->id)
            ],
            'color' =>[
                'required',
                'max:50'                
            ],
            'brand' =>[
                'required',
                'max:20'                
            ],            
            'vehicle_type_id' =>[
                'required'             
            ],
            'third_party_owner_id' =>[
                'required'             
            ],
            'third_party_driver_id' =>[
                'required'             
            ],
        ];
    }


    /**
     *  Custom messages for validation errors
     *
     * @return array
     */
    public function messages()
    {
        return [
            
        ];
    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'plate' => 'trim|uppercase|escape',
            'color' => 'trim|escape',
            'brand' => 'trim|uppercase|escape',
        ];
    }
}
