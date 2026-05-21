<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreEmailFilterRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  protected function prepareForValidation(): void
  {
    if ($this->has('date') && ! filled($this->input('date'))) {
      $this->merge(['date' => null]);
    }
  }

  public function rules(): array
  {
    return [
      'email_id' => ['required', 'string', 'max:255'],
      'from' => ['required', 'string', 'max:255'],
      'subject' => ['required', 'string', 'max:500'],
      'snippet' => ['nullable', 'string', 'max:10000'],
      'date' => ['nullable', 'string', 'max:32'],
    ];
  }

  public function messages(): array
  {
    return [
      'email_id.required' => 'معرف الرسالة email_id مطلوب.',
      'from.required' => 'حقل from (المرسل) مطلوب.',
      'subject.required' => 'حقل subject (الموضوع) مطلوب.',
    ];
  }

  protected function failedValidation(Validator $validator): void
  {
    throw new HttpResponseException(response()->json([
      'success' => false,
      'message' => $validator->errors()->first(),
    ]));
  }
}
