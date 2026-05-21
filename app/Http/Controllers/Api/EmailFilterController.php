<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmailFilterRequest;
use App\Services\EmailFilters\EmailFiltersDataService;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

  public function store(StoreEmailFilterRequest $request): JsonResponse
  {
    $email = $this->emailFiltersData->store($request->validated());

    if ($email === null) {
      return response()->json([
        'success' => false,
        'message' => 'معرف الرسالة email_id مستخدم مسبقاً.',
      ]);
    }

    return response()->json([
      'success' => true,
      'data' => [
        'email' => $email,
      ],
    ]);
  }

  public function show(Request $request): JsonResponse
  {
    $id = $request->query('id');

    if (! is_string($id) || $id === '') {
      return response()->json([
        'success' => false,
        'message' => 'معامل id مطلوب.',
      ]);
    }

    $email = $this->emailFiltersData->findByEmailId($id);

    if ($email === null) {
      return response()->json([
        'success' => false,
        'message' => 'لم يتم العثور على رسالة بهذا المعرف.',
      ]);
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
