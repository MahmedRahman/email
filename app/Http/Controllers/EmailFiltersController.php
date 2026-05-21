<?php

namespace App\Http\Controllers;

use App\Services\EmailFilters\EmailFiltersDataService;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailFiltersController extends Controller
{
  public function __construct(
    private readonly EmailFiltersDataService $emailFiltersData,
    private readonly SettingsDataService $settingsData,
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
