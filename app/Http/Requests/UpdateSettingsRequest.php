<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'email_instructions' => ['required', 'string', 'max:10000'],
      'reply_instructions' => ['required', 'string', 'max:10000'],
      'deepseek_api_key' => ['nullable', 'string', 'max:500'],
    ];
  }

  public function messages(): array
  {
    return [
      'email_instructions.required' => 'تعليمات البريد مطلوبة.',
      'email_instructions.max' => 'تعليمات البريد طويلة جداً (الحد الأقصى 10000 حرف).',
      'reply_instructions.required' => 'تعليمات الرد مطلوبة.',
      'reply_instructions.max' => 'تعليمات الرد طويلة جداً (الحد الأقصى 10000 حرف).',
    ];
  }
}
