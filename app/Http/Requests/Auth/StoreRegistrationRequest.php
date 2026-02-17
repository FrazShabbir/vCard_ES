<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRegistrationRequest extends FormRequest
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
     * UK and international best practices: required names, email, phone, strong password, terms, account type.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'slug'       => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_\s-]+$/i'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'      => [
                'required',
                'string',
                'max:20',
                'regex:/^[\d\s\+\-\(\)]{10,20}$/',
            ],
            'password'   => ['required', 'confirmed', Password::defaults()],
            'account_type' => ['required', 'in:personal,business'],
            'terms'      => ['required', 'accepted'],
            'address'    => ['nullable', 'string', 'max:500'],
            'referral_code' => ['nullable', 'string', 'max:50'],
            'marketing_consent' => ['nullable', 'boolean'],
            // Optional profile fields
            'bio'         => ['nullable', 'string', 'max:1000'],
            'organization' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'website'     => ['nullable', 'string', 'url', 'max:255'],
            // Files
            'avatar'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'first_name'   => 'first name',
            'last_name'   => 'last name',
            'slug'        => 'profile URL',
            'account_type' => 'account type',
            'terms'       => 'terms and conditions',
            'marketing_consent' => 'marketing communications',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'phone.regex' => 'Please enter a valid phone number (e.g. +44 7700 900123 or 07700900123).',
            'slug.regex'  => 'Profile URL may only contain letters, numbers, spaces, hyphens and underscores (e.g. john-smith).',
            'account_type.in' => 'Please select Personal or Business.',
            'terms.accepted' => 'You must accept the terms of use and privacy policy.',
        ];
    }
}
