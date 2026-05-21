<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormatEmailInstructionsRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'email_instructions' => ['required', 'string', 'max:10000'],
    ];
  }

  public function messages(): array
  {
    return [
      'email_instructions.required' => 'تعليمات البريد مطلوبة للتنسيق.',
    ];
  }
}
