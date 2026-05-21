<?php

namespace App\Services\Settings;

use App\Services\AI\DeepSeekService;
use RuntimeException;

class InstructionFormatterService
{
  public function __construct(
    private readonly DeepSeekService $deepSeek,
  ) {}

  public function format(string $instructions): string
  {
    $instructions = trim($instructions);

    if ($instructions === '') {
      throw new RuntimeException('أدخل تعليمات البريد أولاً ثم اضغط التنسيق.');
    }

    return $this->deepSeek->chat(
      (string) config('deepseek.instruction_format_system_prompt'),
      $instructions,
    );
  }
}
