<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
  public function __construct(
    private readonly SettingsDataService $settingsData,
  ) {}

  public function show(): JsonResponse
  {
    return response()->json([
      'success' => true,
      'data' => [
        'email_instructions_enabled' => $this->settingsData->getEmailInstructionsEnabled(),
        'email_instructions' => $this->settingsData->getEmailInstructions(),
        'reply_instructions' => $this->settingsData->getReplyInstructions(),
      ],
    ]);
  }
}
