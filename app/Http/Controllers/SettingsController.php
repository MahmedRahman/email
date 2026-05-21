<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormatEmailInstructionsRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Services\Settings\InstructionFormatterService;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
  public function __construct(
    private readonly SettingsDataService $settingsData,
    private readonly InstructionFormatterService $instructionFormatter,
  ) {}

  public function index(): View
  {
    return view('settings.index', [
      'emailInstructions' => $this->settingsData->getEmailInstructions(),
      'hasDeepSeekApiKey' => $this->settingsData->hasDeepSeekApiKey(),
    ]);
  }

  public function formatInstructions(FormatEmailInstructionsRequest $request): JsonResponse
  {
    try {
      $formatted = $this->instructionFormatter->format(
        $request->validated('email_instructions'),
      );
    } catch (\Throwable $exception) {
      return response()->json([
        'success' => false,
        'message' => $exception->getMessage(),
      ]);
    }

    return response()->json([
      'success' => true,
      'data' => [
        'email_instructions' => $formatted,
      ],
    ]);
  }

  public function update(UpdateSettingsRequest $request): RedirectResponse
  {
    $this->settingsData->saveEmailInstructions($request->validated('email_instructions'));

    if ($request->filled('deepseek_api_key')) {
      $this->settingsData->saveDeepSeekApiKey($request->input('deepseek_api_key'));
    }

    return redirect()
      ->route('settings.index')
      ->with('success', 'تم حفظ الإعدادات بنجاح.');
  }
}
