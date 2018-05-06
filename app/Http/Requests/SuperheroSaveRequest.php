<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuperheroSaveRequest extends FormRequest
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
        $rules = [
            'nickname' => 'string|required',
            'real_name' => 'string|required',
            'origin_description' => 'required',
            'superpowers' => 'required',
            'catch_phrase' => 'required',
            'images' => 'array|between:1,4|required'
        ];

        if ($this->method() == 'PUT') {
            $rules = [
                'id' => 'integer|required',
                'nickname' => 'string|required_without_all:real_name,origin_description,superpowers,catch_phrase,images',
                'real_name' => 'string|required_without_all:nickname,origin_description,superpowers,catch_phrase,images',
                'origin_description' => 'required_without_all:nickname,real_name,superpowers,catch_phrase,images',
                'superpowers' => 'required_without_all:nickname,real_name,origin_description,catch_phrase,images',
                'catch_phrase' => 'required_without_all:nickname,real_name,origin_description,superpowers,images',
                'images' => 'array|between:1,4|required_without_all:nickname,real_name,origin_description,superpowers,catch_phrase'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages =  [
            'nickname.required' => "Superhero's nickname is required",
            'real_name.required' => "Superhero's real name is required",
            'origin_description.required' => "Superhero's origin description is required",
            'superpowers.required' => "At least one superhero's superpower is required",
            'catch_phrase.required' => "Superhero's catch phrase is required",
            'images.required' => "At least one superhero's image is required"
        ];

        if ($this->method() == 'PUT') {
            $messages['id.required'] = "Superhero's id is required";
        }

        return $messages;
    }
}
