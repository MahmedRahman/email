<?php

namespace App\Services\EmailFilters;

use App\Models\EmailFilter;
use Illuminate\Support\Str;

class EmailFiltersDataService
{
  /**
   * @return array<int, array<string, mixed>>
   */
  public function getEmails(?string $status = null): array
  {
    $query = EmailFilter::query()->orderByDesc('date');

    if ($status !== null && $status !== 'all') {
      $query->where('status', $status);
    }

    return $query
      ->get()
      ->map(fn (EmailFilter $email) => $email->toApiArray())
      ->all();
  }

  /**
   * @return array<int, array<string, mixed>>
   */
  public function getEmailsAwaitingReply(): array
  {
    return $this->getEmails(EmailFilter::STATUS_WAITING_REPLY);
  }

  /**
   * @return array{all: int, waiting_reply: int, replied: int, ignored: int}
   */
  public function getStatusCounts(): array
  {
    $counts = EmailFilter::query()
      ->selectRaw('status, COUNT(*) as total')
      ->groupBy('status')
      ->pluck('total', 'status');

    $waiting = (int) ($counts[EmailFilter::STATUS_WAITING_REPLY] ?? 0);
    $replied = (int) ($counts[EmailFilter::STATUS_REPLIED] ?? 0);
    $ignored = (int) ($counts[EmailFilter::STATUS_IGNORED] ?? 0);

    return [
      'all' => $waiting + $replied + $ignored,
      'waiting_reply' => $waiting,
      'replied' => $replied,
      'ignored' => $ignored,
    ];
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
   * @param  array{email_id: string, from: string, subject: string, snippet?: string|null, date?: string|null, status?: string|null}  $data
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
      'status' => $this->normalizeStatus($data['status'] ?? EmailFilter::STATUS_WAITING_REPLY),
    ]);

    return $email->toApiArray();
  }

  /**
   * @param  array{email_id: string, from: string, subject: string, snippet?: string|null, date: string, status: string}  $data
   */
  public function update(string $id, array $data): ?array
  {
    $email = EmailFilter::query()->whereKey($id)->first();

    if ($email === null) {
      return null;
    }

    $email->update([
      'email_id' => $data['email_id'],
      'from_address' => $data['from'],
      'subject' => $data['subject'],
      'snippet' => $data['snippet'] ?? '',
      'date' => trim($data['date']),
      'status' => $this->normalizeStatus($data['status']),
    ]);

    return $email->fresh()->toApiArray();
  }

  public function updateStatus(string $id, string $status): bool
  {
    $email = EmailFilter::query()->whereKey($id)->first();

    if ($email === null) {
      return false;
    }

    $email->update(['status' => $this->normalizeStatus($status)]);

    return true;
  }

  public function updateStatusByEmailId(string $emailId, string $status): ?array
  {
    $email = EmailFilter::query()->where('email_id', $emailId)->first();

    if ($email === null) {
      return null;
    }

    $email->update(['status' => $this->normalizeStatus($status)]);

    return $email->fresh()->toApiArray();
  }

  public function deleteById(string $id): bool
  {
    return EmailFilter::query()->whereKey($id)->delete() > 0;
  }

  public function deleteAll(): int
  {
    return EmailFilter::query()->delete();
  }

  private function normalizeStatus(string $status): string
  {
    return match ($status) {
      EmailFilter::STATUS_REPLIED => EmailFilter::STATUS_REPLIED,
      EmailFilter::STATUS_IGNORED => EmailFilter::STATUS_IGNORED,
      default => EmailFilter::STATUS_WAITING_REPLY,
    };
  }
}
