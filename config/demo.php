<?php

return [

  'user' => [
    'email' => env('DEMO_USER_EMAIL', 'admin@example.com'),
    'password' => env('DEMO_USER_PASSWORD', 'password'),
    'name' => env('DEMO_USER_NAME', 'محمد'),
  ],

  'email_instructions' => <<<'TEXT'
صنّف البريد الوارد حسب الأولوية:
- عاجل: فواتير، تنبيهات أمنية، طلبات عملاء
- عادي: مراسلات داخلية وتحديثات المشاريع
- منخفض: نشرات إخبارية وعروض ترويجية

أرشف الرسائل الترويجية تلقائياً، وأعد توجيه الفواتير إلى مجلد "الموردين".
TEXT,

];
