<?php

namespace App\Services\Settings;

class SettingsDataService
{
  public function getEmailInstructions(): string
  {
    return session('settings.email_instructions', config('demo.email_instructions', ''));
  }

  public function saveEmailInstructions(string $instructions): void
  {
    session(['settings.email_instructions' => $instructions]);
  }
}
