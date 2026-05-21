<?php

namespace App\Services\AI;

use App\Services\Settings\SettingsDataService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class DeepSeekService
{
  public function __construct(
    private readonly SettingsDataService $settingsData,
  ) {}

  public function chat(string $systemPrompt, string $userPrompt): string
  {
    $apiKey = $this->settingsData->getDeepSeekApiKey();

    if (blank($apiKey)) {
      throw new RuntimeException('مفتاح DeepSeek غير مُعرَّف. أدخله من صفحة الإعدادات.');
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
