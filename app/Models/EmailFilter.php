<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailFilter extends Model
{
  public const STATUS_WAITING_REPLY = 'waiting_reply';

  public const STATUS_REPLIED = 'replied';

  public $incrementing = false;

  protected $keyType = 'string';

  protected $fillable = [
    'id',
    'email_id',
    'from_address',
    'subject',
    'snippet',
    'date',
    'status',
  ];

  public function toApiArray(): array
  {
    return [
      'id' => $this->id,
      'email_id' => $this->email_id,
      'from' => $this->from_address,
      'subject' => $this->subject,
      'snippet' => $this->snippet ?? '',
      'date' => $this->date,
      'status' => $this->status,
    ];
  }
}
