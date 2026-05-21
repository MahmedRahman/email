<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Services\Settings\SettingsDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
  public function __construct(
    private readonly SettingsDataService $settingsData,
  ) {}

  public function index(): View
  {
    return view('settings.index', [
      'emailInstructions' => $this->settingsData->getEmailInstructions(),
    ]);
  }

  public function update(UpdateSettingsRequest $request): RedirectResponse
  {
    $this->settingsData->saveEmailInstructions($request->validated('email_instructions'));

    return redirect()
      ->route('settings.index')
      ->with('success', 'تم حفظ الإعدادات بنجاح.');
  }
}
