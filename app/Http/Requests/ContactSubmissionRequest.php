<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:120'],
            'budget' => ['nullable', 'string', 'max:120'],
            'service_interest' => ['nullable', 'string', 'max:120'],
            'project_timeline' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:20', 'max:3000'],
            'website' => ['nullable', 'string', 'max:255'],
            'captcha_token' => ['nullable', 'string', 'max:4096'],
        ];

        if ((bool) config('services.captcha.enabled', false)) {
            $rules['captcha_token'][0] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'captcha_token.required' => 'Please complete the captcha challenge before submitting.',
        ];
    }
}
