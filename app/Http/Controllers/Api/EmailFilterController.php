<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmailFilters\EmailFiltersDataService;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\JsonResponse;

class EmailFilterController extends Controller
{
  public function __construct(
    private readonly EmailFiltersDataService $emailFiltersData,
    private readonly SettingsDataService $settingsData,
  ) {}

  public function information(): JsonResponse
  {
    $emails = $this->emailFiltersData->getEmails();

    return response()->json([
      'success' => true,
      'data' => [
        'count' => count($emails),
        'email_instructions' => $this->settingsData->getEmailInstructions(),
        'emails' => $emails,
      ],
    ]);
  }

  public function show(string $emailId): JsonResponse
  {
    $email = $this->emailFiltersData->findByEmailId($emailId);

    if ($email === null) {
      return response()->json([
        'success' => false,
        'message' => 'لم يتم العثور على رسالة بهذا EmailId.',
      ], 404);
    }

    return response()->json([
      'success' => true,
      'data' => [
        'email' => $email,
        'email_instructions' => $this->settingsData->getEmailInstructions(),
      ],
    ]);
  }
}
