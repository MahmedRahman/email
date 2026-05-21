<?php

namespace App\Services\Dashboard;

class DashboardDataService
{
  public function getStats(): array
  {
    return [
      'active_filters' => 12,
      'connected_workflows' => 5,
      'processed_emails' => 1847,
      'failed_automations' => 3,
    ];
  }

  public function getRecentActivity(): array
  {
    return [
      [
        'workflow_name' => 'تصنيف البريد الوارد',
        'trigger' => 'بريد جديد',
        'status' => 'success',
        'last_run' => 'منذ 5 دقائق',
      ],
      [
        'workflow_name' => 'أرشفة النشرات الإخبارية',
        'trigger' => 'مطابقة فلتر',
        'status' => 'success',
        'last_run' => 'منذ 23 دقيقة',
      ],
      [
        'workflow_name' => 'تنبيه البريد العاجل',
        'trigger' => 'كلمة مفتاحية',
        'status' => 'running',
        'last_run' => 'قيد التشغيل',
      ],
      [
        'workflow_name' => 'إعادة توجيه فواتير الموردين',
        'trigger' => 'مرفق PDF',
        'status' => 'failed',
        'last_run' => 'منذ ساعتين',
      ],
      [
        'workflow_name' => 'تنظيف صندوق العروض',
        'trigger' => 'جدولة يومية',
        'status' => 'success',
        'last_run' => 'أمس 11:30 م',
      ],
    ];
  }
}
