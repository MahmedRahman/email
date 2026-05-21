<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailFilter extends Model
{
  public $incrementing = false;

  protected $keyType = 'string';

  protected $fillable = [
    'id',
    'email_id',
    'from_address',
    'subject',
    'snippet',
    'date',
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
    ];
  }
}
