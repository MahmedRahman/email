<?php

namespace App\Http\Controllers;

use App\Services\EmailFilters\EmailFiltersDataService;
use App\Services\EmailFilters\EmailReplyGeneratorService;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailFiltersController extends Controller
{
  public function __construct(
    private readonly EmailFiltersDataService $emailFiltersData,
    private readonly SettingsDataService $settingsData,
    private readonly EmailReplyGeneratorService $replyGenerator,
  ) {}

  public function index(): View
  {
    return view('filters.index', [
      'emails' => $this->emailFiltersData->getEmails(),
    ]);
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
}
