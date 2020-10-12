<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThirdPartyStorageRequest extends BaseFormRequest
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
            'identification' =>[
                'required',
                'min:5',
                'max:15',                
                Rule::unique('third_parties', 'identification')->ignore($this->id)
            ],
            'first_name' =>[
                'required',
                'max:50'                
            ],
            'second_name' =>[
                'required',
                'max:50'                
            ],
            'last_name' =>[
                'required',
                'max:100'                
            ],
            'phone' =>[
                'required',
                'max:50'                
            ],
            'address' =>[
                'required',
                'max:400'                
            ],
            'city_id' =>[
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
            /*'email.required' => 'Email is required!',
            'name.required' => 'Name is required!',
            'password.required' => 'Password is required!'*/
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
            'first_name' => 'trim|uppercase|escape',
            'second_name' => 'trim|uppercase|escape',
            'last_name' => 'trim|uppercase|escape',
            'phone' => 'trim|escape',
            'address' => 'trim|escape'
        ];
    }
}
