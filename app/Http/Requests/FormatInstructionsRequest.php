<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormatInstructionsRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'instructions' => ['required', 'string', 'max:10000'],
      'type' => ['required', 'string', 'in:email,reply'],
    ];
  }

  public function messages(): array
  {
    return [
      'instructions.required' => 'التعليمات مطلوبة للتنسيق.',
      'type.in' => 'نوع التعليمات غير صالح.',
    ];
  }
}
