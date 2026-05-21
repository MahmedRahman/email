<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardDataService;
use Illuminate\View\View;

class DashboardController extends Controller
{
  public function __construct(
    private readonly DashboardDataService $dashboardData,
  ) {}

  public function index(): View
  {
    return view('dashboard.index', [
      'user' => session('auth.user'),
      'stats' => $this->dashboardData->getStats(),
      'recentActivity' => $this->dashboardData->getRecentActivity(),
    ]);
  }
}
