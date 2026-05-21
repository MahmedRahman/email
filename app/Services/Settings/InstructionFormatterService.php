<?php

namespace App\Services\Settings;

use App\Services\AI\DeepSeekService;
use RuntimeException;

class InstructionFormatterService
{
  public function __construct(
    private readonly DeepSeekService $deepSeek,
  ) {}

  public function format(string $instructions, string $type = 'email'): string
  {
    $instructions = trim($instructions);

    if ($instructions === '') {
      throw new RuntimeException(
        $type === 'reply'
          ? 'أدخل تعليمات الرد أولاً ثم اضغط التنسيق.'
          : 'أدخل تعليمات البريد أولاً ثم اضغط التنسيق.',
      );
    }

    $systemPrompt = $type === 'reply'
      ? (string) config('deepseek.reply_instruction_format_system_prompt')
      : (string) config('deepseek.instruction_format_system_prompt');

    return $this->deepSeek->chat($systemPrompt, $instructions);
  }
}
