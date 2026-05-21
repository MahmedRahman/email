<?php

namespace Database\Seeders;

use App\Models\EmailFilter;
use Illuminate\Database\Seeder;

class EmailFilterSeeder extends Seeder
{
  public function run(): void
  {
    $emails = [
      [
        'id' => '18f3a2b1c4d5e6f7',
        'email_id' => 'msg-10042',
        'from_address' => 'billing@stripe.com',
        'subject' => 'فاتورة شهر مايو — تم الدفع',
        'snippet' => 'تم استلام دفعتك بنجاح. يمكنك تحميل الفاتورة من لوحة التحكم...',
        'date' => '2026-05-21 09:42',
      ],
      [
        'id' => '18f2e8910a1b2c3d',
        'email_id' => 'msg-10041',
        'from_address' => 'noreply@github.com',
        'subject' => '[Email_Filter] Pull request merged',
        'snippet' => 'Your pull request #12 was merged into main by mohamed...',
        'date' => '2026-05-20 18:15',
      ],
      [
        'id' => '18f1d44556677889',
        'email_id' => 'msg-10040',
        'from_address' => 'newsletter@medium.com',
        'subject' => 'أفضل 5 مقالات عن الأتمتة هذا الأسبوع',
        'snippet' => 'اكتشف كيف تربط n8n مع Gmail لبناء فلاتر ذكية...',
        'date' => '2026-05-20 08:00',
      ],
      [
        'id' => '18f0abc123456789',
        'email_id' => 'msg-10039',
        'from_address' => 'hr@company.com',
        'subject' => 'تذكير: اجتماع الفريق غداً',
        'snippet' => 'مرحباً، نذكّرك باجتماع الفريق الأسبوعي الساعة 10 صباحاً...',
        'date' => '2026-05-19 14:30',
      ],
      [
        'id' => '18ef9876543210ab',
        'email_id' => 'msg-10038',
        'from_address' => 'alerts@n8n.io',
        'subject' => 'Workflow execution failed',
        'snippet' => 'Workflow "تنبيه البريد العاجل" failed at node Gmail Trigger...',
        'date' => '2026-05-19 11:02',
      ],
      [
        'id' => '18ee112233445566',
        'email_id' => 'msg-10037',
        'from_address' => 'support@amazon.ae',
        'subject' => 'تم شحن طلبك #4021-8834',
        'snippet' => 'طلبك في الطريق. التسليم المتوقع يوم الأحد...',
        'date' => '2026-05-18 16:45',
      ],
    ];

    foreach ($emails as $email) {
      EmailFilter::query()->updateOrCreate(
        ['email_id' => $email['email_id']],
        $email,
      );
    }
  }
}
