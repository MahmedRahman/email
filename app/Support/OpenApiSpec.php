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
        ['url' => '/', 'description' => 'التطبيق الحالي'],
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
                'description' => 'الاستجابة دائماً HTTP 200 — النجاح أو الفشل يُحدَّد عبر success في الجسم',
                'content' => [
                  'application/json' => [
                    'schema' => [
                      'oneOf' => [
                        [
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
                        ['$ref' => '#/components/schemas/ErrorResponse'],
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
        ],
        '/api/email-filters' => [
          'post' => [
            'tags' => ['Email Filters'],
            'summary' => 'Register email filter',
            'description' => 'تسجيل بيانات رسالة بريد جديدة في فلاتر البريد.',
            'operationId' => 'storeEmailFilter',
            'requestBody' => [
              'required' => true,
              'content' => [
                'application/json' => [
                  'schema' => ['$ref' => '#/components/schemas/EmailFilterCreateInput'],
                ],
              ],
            ],
            'responses' => [
              '200' => [
                'description' => 'الاستجابة دائماً HTTP 200 — النجاح أو الفشل يُحدَّد عبر success في الجسم',
                'content' => [
                  'application/json' => [
                    'schema' => [
                      'oneOf' => [
                        [
                          'type' => 'object',
                          'properties' => [
                            'success' => ['type' => 'boolean', 'example' => true],
                            'data' => [
                              'type' => 'object',
                              'properties' => [
                                'email' => ['$ref' => '#/components/schemas/EmailFilterItem'],
                              ],
                            ],
                          ],
                        ],
                        ['$ref' => '#/components/schemas/ErrorResponse'],
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
          'get' => [
            'tags' => ['Email Filters'],
            'summary' => 'Get email filter by id',
            'description' => 'يعيد رسالة واحدة مطابقة لمعرف id المُمرَّر في Query Parameters.',
            'operationId' => 'getEmailFilterById',
            'parameters' => [
              [
                'name' => 'id',
                'in' => 'query',
                'required' => true,
                'description' => 'معرف الرسالة (EmailId)',
                'schema' => ['type' => 'string', 'example' => 'msg-10042'],
              ],
            ],
            'responses' => [
              '200' => [
                'description' => 'الاستجابة دائماً HTTP 200 — النجاح أو الفشل يُحدَّد عبر success في الجسم',
                'content' => [
                  'application/json' => [
                    'schema' => [
                      'oneOf' => [
                        [
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
                        ['$ref' => '#/components/schemas/ErrorResponse'],
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
        ],
      ],
      'components' => [
        'schemas' => [
          'EmailFilterCreateInput' => [
            'type' => 'object',
            'required' => ['email_id', 'from', 'subject'],
            'properties' => [
              'email_id' => ['type' => 'string', 'example' => 'msg-10043'],
              'from' => ['type' => 'string', 'example' => 'client@example.com'],
              'subject' => ['type' => 'string', 'example' => 'طلب عرض سعر'],
              'snippet' => ['type' => 'string', 'example' => 'نرجو إرسال عرض السعر خلال 48 ساعة...'],
              'date' => ['type' => 'string', 'example' => '2026-05-21 14:00'],
            ],
          ],
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
