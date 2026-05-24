<?php

namespace App\Http\Requests;

use App\Models\EmailFilter;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateEmailFilterStatusRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'id' => ['required', 'string', 'max:255'],
      'status' => ['required', 'string', Rule::in(EmailFilter::statuses())],
    ];
  }

  public function messages(): array
  {
    return [
      'id.required' => 'معامل id مطلوب.',
      'status.required' => 'معامل status مطلوب.',
      'status.in' => 'قيمة status غير صالحة. المسموح: waiting_reply, replied, ignored.',
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
