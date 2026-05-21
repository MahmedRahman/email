<?php

namespace App\Services\Settings;

use App\Models\Setting;

class SettingsDataService
{
  private const EMAIL_INSTRUCTIONS_KEY = 'email_instructions';

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
}
