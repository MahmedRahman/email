<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
  public function run(): void
  {
    Setting::query()->updateOrCreate(
      ['key' => 'email_instructions'],
      ['value' => (string) config('demo.email_instructions', '')],
    );

    Setting::query()->updateOrCreate(
      ['key' => 'email_instructions_enabled'],
      ['value' => '1'],
    );

    Setting::query()->updateOrCreate(
      ['key' => 'reply_instructions'],
      ['value' => (string) config('demo.reply_instructions', '')],
    );
  }
}
