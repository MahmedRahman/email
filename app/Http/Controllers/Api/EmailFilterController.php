<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkEmailRepliedRequest;
use App\Http\Requests\StoreEmailFilterRequest;
use App\Http\Requests\SuggestedRepliesRequest;
use App\Models\EmailFilter;
use App\Services\EmailFilters\EmailFiltersDataService;
use App\Services\EmailFilters\EmailReplyGeneratorService;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailFilterController extends Controller
{
  public function __construct(
    private readonly EmailFiltersDataService $emailFiltersData,
    private readonly SettingsDataService $settingsData,
    private readonly EmailReplyGeneratorService $replyGenerator,
  ) {}

  public function information(): JsonResponse
  {
    $emails = $this->emailFiltersData->getEmails();

    return response()->json([
      'success' => true,
      'data' => [
        'count' => count($emails),
        'status_counts' => $this->emailFiltersData->getStatusCounts(),
        'email_instructions' => $this->settingsData->getEmailInstructions(),
        'reply_instructions' => $this->settingsData->getReplyInstructions(),
        'emails' => $emails,
      ],
    ]);
  }

  public function pendingReplies(): JsonResponse
  {
    $emails = $this->emailFiltersData->getEmailsAwaitingReply();

    return response()->json([
      'success' => true,
      'data' => [
        'count' => count($emails),
        'status' => EmailFilter::STATUS_WAITING_REPLY,
        'reply_instructions' => $this->settingsData->getReplyInstructions(),
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

  public function markReplied(MarkEmailRepliedRequest $request): JsonResponse
  {
    $email = $this->emailFiltersData->updateStatusByEmailId(
      $request->validated('id'),
      EmailFilter::STATUS_REPLIED,
    );

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
      ],
    ]);
  }

  public function suggestedReplies(SuggestedRepliesRequest $request): JsonResponse
  {
    $email = $this->emailFiltersData->findByEmailId($request->validated('id'));

    if ($email === null) {
      return response()->json([
        'success' => false,
        'message' => 'لم يتم العثور على رسالة بهذا المعرف.',
      ]);
    }

    try {
      $replies = $this->replyGenerator->generate(
        $email,
        $this->settingsData->getReplyInstructions(),
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
        'email' => $email,
        'reply_instructions' => $this->settingsData->getReplyInstructions(),
        'replies' => $replies,
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
        'reply_instructions' => $this->settingsData->getReplyInstructions(),
      ],
    ]);
  }
}
