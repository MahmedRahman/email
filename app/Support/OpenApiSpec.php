<?php

namespace App\Support;

class OpenApiSpec
{
  public static function toArray(): array
  {
    return [
      'openapi' => '3.0.3',
      'info' => [
        'title' => 'Email Filter API',
        'description' => 'واجهة برمجة مساعد البريد — فلاتر البريد والإعدادات',
        'version' => '1.0.0',
      ],
      'servers' => [
        ['url' => url('/'), 'description' => 'التطبيق الحالي'],
      ],
      'paths' => [
        '/api/email-filters/information' => [
          'get' => [
            'tags' => ['Email Filters'],
            'summary' => 'Get email filter information',
            'description' => 'يعيد قائمة الرسائل المطابقة للفلاتر مع العدد وتعليمات البريد من الإعدادات.',
            'operationId' => 'getEmailFilterInformation',
            'responses' => [
              '200' => [
                'description' => 'معلومات فلاتر البريد',
                'content' => [
                  'application/json' => [
                    'schema' => [
                      'type' => 'object',
                      'properties' => [
                        'success' => ['type' => 'boolean', 'example' => true],
                        'data' => [
                          'type' => 'object',
                          'properties' => [
                            'count' => ['type' => 'integer', 'example' => 6],
                            'email_instructions' => ['type' => 'string'],
                            'emails' => [
                              'type' => 'array',
                              'items' => ['$ref' => '#/components/schemas/EmailFilterItem'],
                            ],
                          ],
                        ],
                      ],
                    ],
                  ],
                ],
              ],
              '401' => [
                'description' => 'غير مصرح — يلزم تسجيل الدخول',
                'content' => [
                  'application/json' => [
                    'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                  ],
                ],
              ],
            ],
          ],
        ],
        '/api/email-filters/{email_id}' => [
          'get' => [
            'tags' => ['Email Filters'],
            'summary' => 'Get email filter by EmailId',
            'description' => 'يعيد رسالة واحدة مطابقة لـ email_id المحدد.',
            'operationId' => 'getEmailFilterByEmailId',
            'parameters' => [
              [
                'name' => 'email_id',
                'in' => 'path',
                'required' => true,
                'description' => 'معرف الرسالة (EmailId)',
                'schema' => ['type' => 'string', 'example' => 'msg-10042'],
              ],
            ],
            'responses' => [
              '200' => [
                'description' => 'تفاصيل الرسالة',
                'content' => [
                  'application/json' => [
                    'schema' => [
                      'type' => 'object',
                      'properties' => [
                        'success' => ['type' => 'boolean', 'example' => true],
                        'data' => [
                          'type' => 'object',
                          'properties' => [
                            'email' => ['$ref' => '#/components/schemas/EmailFilterItem'],
                            'email_instructions' => ['type' => 'string'],
                          ],
                        ],
                      ],
                    ],
                  ],
                ],
              ],
              '404' => [
                'description' => 'EmailId غير موجود',
                'content' => [
                  'application/json' => [
                    'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                  ],
                ],
              ],
              '401' => [
                'description' => 'غير مصرح — يلزم تسجيل الدخول',
                'content' => [
                  'application/json' => [
                    'schema' => ['$ref' => '#/components/schemas/ErrorResponse'],
                  ],
                ],
              ],
            ],
          ],
        ],
      ],
      'components' => [
        'schemas' => [
          'EmailFilterItem' => [
            'type' => 'object',
            'properties' => [
              'id' => ['type' => 'string', 'example' => '18f3a2b1c4d5e6f7'],
              'email_id' => ['type' => 'string', 'example' => 'msg-10042'],
              'from' => ['type' => 'string', 'example' => 'billing@stripe.com'],
              'subject' => ['type' => 'string'],
              'snippet' => ['type' => 'string'],
              'date' => ['type' => 'string', 'format' => 'date-time', 'example' => '2026-05-21 09:42'],
            ],
          ],
          'ErrorResponse' => [
            'type' => 'object',
            'properties' => [
              'success' => ['type' => 'boolean', 'example' => false],
              'message' => ['type' => 'string'],
            ],
          ],
        ],
      ],
    ];
  }
}
