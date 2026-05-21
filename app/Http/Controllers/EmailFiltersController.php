<?php

namespace App\Http\Controllers;

use App\Services\EmailFilters\EmailFiltersDataService;
use Illuminate\View\View;

class EmailFiltersController extends Controller
{
  public function __construct(
    private readonly EmailFiltersDataService $emailFiltersData,
  ) {}

  public function index(): View
  {
    return view('filters.index', [
      'emails' => $this->emailFiltersData->getEmails(),
    ]);
  }
}
