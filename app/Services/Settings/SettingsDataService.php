<?php

namespace App\Services\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;

class SettingsDataService
{
  private const EMAIL_INSTRUCTIONS_KEY = 'email_instructions';

  private const DEEPSEEK_API_KEY = 'deepseek_api_key';

  public function getEmailInstructions(): string
  {
    $stored = Setting::query()
      ->where('key', self::EMAIL_INSTRUCTIONS_KEY)
      ->value('value');

    if ($stored !== null) {
      return $stored;
    }

    return (string) config('demo.email_instructions', '');
  }

  public function saveEmailInstructions(string $instructions): void
  {
    Setting::query()->updateOrCreate(
      ['key' => self::EMAIL_INSTRUCTIONS_KEY],
      ['value' => $instructions],
    );
  }

  public function hasDeepSeekApiKey(): bool
  {
    return filled($this->getDeepSeekApiKey());
  }

  public function getDeepSeekApiKey(): ?string
  {
    $stored = Setting::query()
      ->where('key', self::DEEPSEEK_API_KEY)
      ->value('value');

    if ($stored !== null) {
      try {
        return Crypt::decryptString($stored);
      } catch (\Throwable) {
        return $stored !== '' ? $stored : null;
      }
    }

    $envKey = config('deepseek.api_key');

    return filled($envKey) ? (string) $envKey : null;
  }

  public function saveDeepSeekApiKey(string $apiKey): void
  {
    Setting::query()->updateOrCreate(
      ['key' => self::DEEPSEEK_API_KEY],
      ['value' => Crypt::encryptString(trim($apiKey))],
    );
  }
}
