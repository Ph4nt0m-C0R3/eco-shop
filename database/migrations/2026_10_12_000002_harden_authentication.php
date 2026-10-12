<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('auth_version')->default(1);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->unsignedBigInteger('two_factor_last_step')->nullable();
            $table->unsignedInteger('two_factor_attempts')->default(0);
            $table->timestamp('two_factor_attempts_at')->nullable();
        });
        Schema::create('password_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('account_hash', 64);
            $table->string('session_hash', 64);
            $table->string('code_hash')->nullable();
            $table->string('grant_hash', 64)->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('sent_at');
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('attempts_at');
            $table->unsignedInteger('send_count')->default(0);
            $table->timestamp('send_window_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->timestamps();
        });
        // Transient legacy plaintext OTPs and old reset grants must not survive.
        DB::table('password_otps')->delete();
        DB::table('password_reset_tokens')->delete();
        DB::table('admin_password_reset_tokens')->delete();
    }

    public function down(): void
    {
        Schema::dropIfExists('password_challenges');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn([
            'auth_version', 'two_factor_secret', 'two_factor_recovery_codes',
            'two_factor_confirmed_at', 'two_factor_last_step', 'two_factor_attempts', 'two_factor_attempts_at',
        ]));
    }
};
