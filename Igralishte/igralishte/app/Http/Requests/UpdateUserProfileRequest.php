<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // Assuming any authenticated user can update their own profile
        return auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'phone_number' => ['required','nullable', 'string', 'max:18'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Полето за име е задолжително.',
            'first_name.string' => 'Ве молиме, внесето го вашето име.',
            'first_name.max' => 'Вашето име не може да биде повеќе од 50 букви.',

            'last_name.required' => 'Полето за име е задолжително.',
            'last_name.string' => 'Ве молиме, внесето го вашето име.',
            'last_name.max' => 'Вашето име не може да биде повеќе од 50 букви.',

            'email.required' => 'Вашата email адреса е задолжителна.',
            'email.string' => 'Ве молиме, внесете валидна email адреса.',
            'email.email' => 'Ве молиме, внесете валидна email адреса.',
            'email.max' => 'Вашата email адреса не може да биде повеќе од 50 карактери.',
            'email.unique' => 'Оваа email е веќе искористена. Ве молиме, внесете друга.',

            'phone_number.string' => 'Ве молиме, внесете валиден телефонски број',
            'phone_number.max' => 'Вашиот број не смее да биде повеќе од 18 бројки.',

            'profile_photo.image' => 'Вашата профилна слика мора да е во валиден формат.',
            'profile_photo.mimes' => 'Вашата профилна слика мора да е во валиден формат: jpg, jpeg, png.',
            'profile_photo.max' => 'Вашата профилна слика не смее да е поголема од 2MB.',
        ];
    }
}
