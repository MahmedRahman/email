<?php

namespace App\Services\AI;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class DeepSeekService
{
  public function chat(string $systemPrompt, string $userPrompt): string
  {
    $apiKey = config('deepseek.api_key');

    if (blank($apiKey)) {
      throw new RuntimeException('مفتاح DeepSeek غير مُعرَّف. أضف DEEPSEEK_API_KEY في ملف .env');
    }

    try {
      $response = Http::baseUrl(rtrim((string) config('deepseek.base_url'), '/'))
        ->withToken($apiKey)
        ->acceptJson()
        ->timeout((int) config('deepseek.timeout', 90))
        ->post('/chat/completions', [
          'model' => config('deepseek.model', 'deepseek-chat'),
          'messages' => [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userPrompt],
          ],
          'temperature' => 0.3,
        ])
        ->throw();
    } catch (RequestException $exception) {
      $message = $exception->response?->json('error.message')
        ?? $exception->response?->body()
        ?? $exception->getMessage();

      throw new RuntimeException('فشل الاتصال بـ DeepSeek: '.$message, 0, $exception);
    }

    $content = trim((string) data_get($response->json(), 'choices.0.message.content', ''));

    if ($content === '') {
      throw new RuntimeException('لم يُرجع DeepSeek أي نص.');
    }

    return $content;
  }
}
