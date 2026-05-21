<?php

namespace App\Services\EmailFilters;

use App\Services\AI\DeepSeekService;
use RuntimeException;

class EmailReplyGeneratorService
{
  public function __construct(
    private readonly DeepSeekService $deepSeek,
  ) {}

  /**
   * @param  array{id: string, email_id: string, from: string, subject: string, snippet: string, date: string}  $email
   * @return array<int, array{title: string, body: string}>
   */
  public function generate(array $email, string $replyInstructions): array
  {
    $replyInstructions = trim($replyInstructions);

    if ($replyInstructions === '') {
      throw new RuntimeException('تعليمات الرد غير مُعرَّفة. أضفها من صفحة الإعدادات.');
    }

    $userPrompt = <<<PROMPT
## تعليمات الرد (يجب الالتزام بها)
{$replyInstructions}

## الرسالة الواردة
- المرسل (From): {$email['from']}
- الموضوع (Subject): {$email['subject']}
- التاريخ: {$email['date']}
- EmailId: {$email['email_id']}

## محتوى الرسالة (snippet)
{$email['snippet']}

اقترح ردّين مختلفين مناسبين لهذه الرسالة.
PROMPT;

    $raw = $this->deepSeek->chat(
      (string) config('deepseek.email_reply_system_prompt'),
      $userPrompt,
      0.7,
    );

    return $this->parseReplies($raw);
  }

  /**
   * @return array<int, array{title: string, body: string}>
   */
  private function parseReplies(string $raw): array
  {
    $json = $raw;

    if (preg_match('/```(?:json)?\s*(\{.*\})\s*```/s', $raw, $matches)) {
      $json = $matches[1];
    } elseif (preg_match('/(\{.*"replies".*\})/s', $raw, $matches)) {
      $json = $matches[1];
    }

    $decoded = json_decode($json, true);

    if (! is_array($decoded) || ! isset($decoded['replies']) || ! is_array($decoded['replies'])) {
      throw new RuntimeException('تعذّر قراءة اقتراحات الرد من DeepSeek. حاول مرة أخرى.');
    }

    $replies = [];

    foreach ($decoded['replies'] as $index => $reply) {
      if (! is_array($reply)) {
        continue;
      }

      $title = trim((string) ($reply['title'] ?? ''));
      $body = trim((string) ($reply['body'] ?? ''));

      if ($body === '') {
        continue;
      }

      $replies[] = [
        'title' => $title !== '' ? $title : 'اقتراح رد '.($index + 1),
        'body' => $body,
      ];

      if (count($replies) >= 2) {
        break;
      }
    }

    if (count($replies) < 2) {
      throw new RuntimeException('لم يُرجع النموذج ردّين كاملين. حاول مرة أخرى.');
    }

    return $replies;
  }
}
