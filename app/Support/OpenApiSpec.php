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
        'description' => 'Email assistant API — email filters and settings. No login required.',
        'version' => '1.0.0',
      ],
      'servers' => [
        ['url' => '/', 'description' => 'Current application'],
      ],
      'paths' => [
        '/api/settings' => [
          'get' => [
            'tags' => ['Settings'],
            'summary' => 'Get application settings',
            'description' => 'Returns email assistant settings (same data as /settings page).',
            'operationId' => 'getSettings',
            'responses' => [
              '200' => [
                'description' => 'Always returns HTTP 200',
                'content' => [
                  'application/json' => [
                    'schema' => [
                      'type' => 'object',
                      'properties' => [
                        'success' => ['type' => 'boolean', 'example' => true],
                        'data' => ['$ref' => '#/components/schemas/SettingsData'],
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
        ],
        '/api/email-filters/suggested-replies' => [
          'post' => [
            'tags' => ['Email Filters'],
            'summary' => 'Get suggested replies for an email',
            'description' => 'Analyzes the email and returns two suggested replies using DeepSeek and reply instructions from settings.',
            'operationId' => 'getSuggestedReplies',
            'requestBody' => [
              'required' => true,
              'content' => [
                'application/json' => [
                  'schema' => [
                    'type' => 'object',
                    'required' => ['id'],
                    'properties' => [
                      'id' => [
                        'type' => 'string',
                        'description' => 'Email message ID (EmailId)',
                        'example' => 'msg-10042',
                      ],
                    ],
                  ],
                ],
              ],
            ],
            'responses' => [
              '200' => [
                'description' => 'Always returns HTTP 200',
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
                                'reply_instructions' => ['type' => 'string'],
                                'replies' => [
                                  'type' => 'array',
                                  'items' => ['$ref' => '#/components/schemas/SuggestedReplyItem'],
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
        '/api/email-filters/update-status' => [
          'post' => [
            'tags' => ['Email Filters'],
            'summary' => 'Update email status',
            'description' => 'Updates email status using id (EmailId) and status.',
            'operationId' => 'updateEmailFilterStatus',
            'requestBody' => [
              'required' => true,
              'content' => [
                'application/json' => [
                  'schema' => [
                    'type' => 'object',
                    'required' => ['id', 'status'],
                    'properties' => [
                      'id' => [
                        'type' => 'string',
                        'description' => 'Email message ID (EmailId)',
                        'example' => 'msg-10042',
                      ],
                      'status' => [
                        'type' => 'string',
                        'enum' => ['waiting_reply', 'replied', 'ignored'],
                        'example' => 'replied',
                        'description' => 'waiting_reply | replied | ignored',
                      ],
                    ],
                  ],
                ],
              ],
            ],
            'responses' => [
              '200' => [
                'description' => 'Always returns HTTP 200',
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
        ],
        '/api/email-filters/mark-replied' => [
          'post' => [
            'tags' => ['Email Filters'],
            'summary' => 'Mark email as replied',
            'description' => 'Sets email status to replied using id (EmailId). Shortcut for update-status.',
            'operationId' => 'markEmailReplied',
            'requestBody' => [
              'required' => true,
              'content' => [
                'application/json' => [
                  'schema' => [
                    'type' => 'object',
                    'required' => ['id'],
                    'properties' => [
                      'id' => [
                        'type' => 'string',
                        'description' => 'Email message ID (EmailId)',
                        'example' => 'msg-10042',
                      ],
                    ],
                  ],
                ],
              ],
            ],
            'responses' => [
              '200' => [
                'description' => 'Always returns HTTP 200',
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
        ],
        '/api/email-filters/pending-replies' => [
          'get' => [
            'tags' => ['Email Filters'],
            'summary' => 'Get emails awaiting reply',
            'description' => 'يعيد الرسائل التي حالتها waiting_reply (في انتظار الرد).',
            'operationId' => 'getPendingReplyEmails',
            'responses' => [
              '200' => [
                'description' => 'Always returns HTTP 200',
                'content' => [
                  'application/json' => [
                    'schema' => [
                      'type' => 'object',
                      'properties' => [
                        'success' => ['type' => 'boolean', 'example' => true],
                        'data' => [
                          'type' => 'object',
                          'properties' => [
                            'count' => ['type' => 'integer', 'example' => 3],
                            'status' => ['type' => 'string', 'example' => 'waiting_reply'],
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
            ],
          ],
        ],
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
                                'status_counts' => [
                                  'type' => 'object',
                                  'properties' => [
                                    'all' => ['type' => 'integer', 'example' => 6],
                                    'waiting_reply' => ['type' => 'integer', 'example' => 3],
                                    'replied' => ['type' => 'integer', 'example' => 2],
                                    'ignored' => ['type' => 'integer', 'example' => 1],
                                  ],
                                ],
                                'email_instructions_enabled' => ['type' => 'boolean', 'example' => true],
                                'email_instructions' => ['type' => 'string'],
                                'reply_instructions' => ['type' => 'string'],
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
            'description' => 'تسجيل بيانات رسالة بريد جديدة. الحقل date اختياري — إن لم يُرسل يُستخدم تاريخ ووقت السيرفر.',
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
                                'email_instructions_enabled' => ['type' => 'boolean', 'example' => true],
                                'email_instructions' => ['type' => 'string'],
                                'reply_instructions' => ['type' => 'string'],
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
          'SettingsData' => [
            'type' => 'object',
            'properties' => [
              'email_instructions_enabled' => [
                'type' => 'boolean',
                'description' => 'تفعيل أو تعطيل استخدام تعليمات البريد داخل الـ workflow',
              ],
              'email_instructions' => [
                'type' => 'string',
                'description' => 'تعليمات تصنيف ومعالجة البريد',
              ],
              'reply_instructions' => [
                'type' => 'string',
                'description' => 'تعليمات صياغة الرد على البريد',
              ],
            ],
          ],
          'SuggestedReplyItem' => [
            'type' => 'object',
            'properties' => [
              'title' => ['type' => 'string', 'example' => 'رد رسمي'],
              'body' => ['type' => 'string', 'example' => 'مرحباً، شكراً لتواصلك...'],
            ],
          ],
          'EmailFilterCreateInput' => [
            'type' => 'object',
            'required' => ['email_id', 'from', 'subject'],
            'properties' => [
              'email_id' => ['type' => 'string', 'example' => 'msg-10043'],
              'from' => ['type' => 'string', 'example' => 'client@example.com'],
              'subject' => ['type' => 'string', 'example' => 'طلب عرض سعر'],
              'snippet' => ['type' => 'string', 'example' => 'نرجو إرسال عرض السعر خلال 48 ساعة...'],
              'date' => [
                'type' => 'string',
                'description' => 'اختياري — إن تُرك فارغاً يُستخدم تاريخ ووقت السيرفر',
                'example' => '2026-05-21 14:00',
              ],
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
              'status' => [
                'type' => 'string',
                'enum' => ['waiting_reply', 'replied', 'ignored'],
                'example' => 'waiting_reply',
                'description' => 'في انتظار الرد | تم الرد | تجاهل',
              ],
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
