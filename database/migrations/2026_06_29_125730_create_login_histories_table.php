<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('login_method');

            $table->string('ip_address');

            $table->text('browser');

            $table->timestamp('login_at');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};