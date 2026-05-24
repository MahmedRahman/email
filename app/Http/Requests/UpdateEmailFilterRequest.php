<?php

namespace App\Http\Requests;

use App\Models\EmailFilter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailFilterRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $id = $this->route('id');

    return [
      'email_id' => [
        'required',
        'string',
        'max:255',
        Rule::unique('email_filters', 'email_id')->ignore($id, 'id'),
      ],
      'from' => ['required', 'string', 'max:255'],
      'subject' => ['required', 'string', 'max:500'],
      'snippet' => ['nullable', 'string', 'max:10000'],
      'date' => ['required', 'string', 'max:32'],
      'status' => ['required', 'string', Rule::in(EmailFilter::statuses())],
    ];
  }

  public function messages(): array
  {
    return [
      'email_id.required' => 'معرف الرسالة (EmailId) مطلوب.',
      'email_id.unique' => 'معرف الرسالة مستخدم مسبقاً لرسالة أخرى.',
      'from.required' => 'حقل المرسل (From) مطلوب.',
      'subject.required' => 'حقل الموضوع (Subject) مطلوب.',
      'date.required' => 'حقل التاريخ مطلوب.',
      'status.required' => 'حالة الرسالة مطلوبة.',
      'status.in' => 'حالة الرسالة غير صالحة.',
    ];
  }
}
