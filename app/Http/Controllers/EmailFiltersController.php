<?php

namespace App\Http\Controllers;

use App\Services\EmailFilters\EmailFiltersDataService;
use App\Services\EmailFilters\EmailReplyGeneratorService;
use App\Services\Settings\SettingsDataService;
use App\Models\EmailFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailFiltersController extends Controller
{
  public function __construct(
    private readonly EmailFiltersDataService $emailFiltersData,
    private readonly SettingsDataService $settingsData,
    private readonly EmailReplyGeneratorService $replyGenerator,
  ) {}

  public function index(Request $request): View
  {
    $status = $request->query('status', 'all');
    $allowed = ['all', EmailFilter::STATUS_WAITING_REPLY, EmailFilter::STATUS_REPLIED];

    if (! in_array($status, $allowed, true)) {
      $status = 'all';
    }

    return view('filters.index', [
      'emails' => $this->emailFiltersData->getEmails($status === 'all' ? null : $status),
      'statusCounts' => $this->emailFiltersData->getStatusCounts(),
      'activeStatus' => $status,
    ]);
  }

  public function updateStatus(Request $request, string $id): RedirectResponse
  {
    $status = $request->input('status', EmailFilter::STATUS_WAITING_REPLY);

    if (! $this->emailFiltersData->updateStatus($id, $status)) {
      return redirect()
        ->route('filters.index')
        ->with('error', 'لم يتم العثور على الرسالة.');
    }

    $message = $status === EmailFilter::STATUS_REPLIED
      ? 'تم تحديث الحالة إلى: تم الرد.'
      : 'تم تحديث الحالة إلى: في انتظار الرد.';

    return redirect()
      ->back()
      ->with('success', $message);
  }

  public function show(string $id): View|RedirectResponse
  {
    $email = $this->emailFiltersData->findById($id);

    if ($email === null) {
      return redirect()
        ->route('filters.index')
        ->with('error', 'لم يتم العثور على الرسالة.');
    }

    return view('filters.show', [
      'email' => $email,
      'emailInstructions' => $this->settingsData->getEmailInstructions(),
      'replyInstructions' => $this->settingsData->getReplyInstructions(),
      'hasDeepSeekApiKey' => $this->settingsData->hasDeepSeekApiKey(),
    ]);
  }

  public function generateReplies(string $id): JsonResponse
  {
    $email = $this->emailFiltersData->findById($id);

    if ($email === null) {
      return response()->json([
        'success' => false,
        'message' => 'لم يتم العثور على الرسالة.',
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
        'replies' => $replies,
      ],
    ]);
  }

  public function destroy(string $id): RedirectResponse
  {
    if (! $this->emailFiltersData->deleteById($id)) {
      return redirect()
        ->route('filters.index')
        ->with('error', 'لم يتم العثور على الرسالة.');
    }

    return redirect()
      ->route('filters.index')
      ->with('success', 'تم حذف الرسالة بنجاح.');
  }

  public function destroyAll(): RedirectResponse
  {
    $deleted = $this->emailFiltersData->deleteAll();

    if ($deleted === 0) {
      return redirect()
        ->route('filters.index')
        ->with('error', 'لا توجد رسائل للحذف.');
    }

    return redirect()
      ->route('filters.index')
      ->with('success', "تم حذف {$deleted} رسالة بنجاح.");
  }
}
