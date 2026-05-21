<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('email_filters', function (Blueprint $table) {
      $table->string('id', 32)->primary();
      $table->string('email_id')->unique();
      $table->string('from_address');
      $table->string('subject');
      $table->text('snippet')->nullable();
      $table->string('date', 32);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('email_filters');
  }
};
