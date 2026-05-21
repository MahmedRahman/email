<?php

namespace App\Services\EmailFilters;

use App\Models\EmailFilter;
use Illuminate\Support\Str;

class EmailFiltersDataService
{
  public function getEmails(): array
  {
    return EmailFilter::query()
      ->orderByDesc('date')
      ->get()
      ->map(fn (EmailFilter $email) => $email->toApiArray())
      ->all();
  }

  public function findById(string $id): ?array
  {
    $email = EmailFilter::query()->whereKey($id)->first();

    return $email?->toApiArray();
  }

  public function findByEmailId(string $emailId): ?array
  {
    $email = EmailFilter::query()->where('email_id', $emailId)->first();

    return $email?->toApiArray();
  }

  /**
   * @param  array{email_id: string, from: string, subject: string, snippet?: string|null, date?: string|null}  $data
   */
  public function store(array $data): ?array
  {
    if (EmailFilter::query()->where('email_id', $data['email_id'])->exists()) {
      return null;
    }

    $email = EmailFilter::query()->create([
      'id' => Str::replace('-', '', (string) Str::uuid()),
      'email_id' => $data['email_id'],
      'from_address' => $data['from'],
      'subject' => $data['subject'],
      'snippet' => $data['snippet'] ?? '',
      'date' => filled($data['date'] ?? null)
        ? trim((string) $data['date'])
        : now()->format('Y-m-d H:i'),
    ]);

    return $email->toApiArray();
  }

  public function deleteById(string $id): bool
  {
    return EmailFilter::query()->whereKey($id)->delete() > 0;
  }
}
