<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->index('status');
            $table->index('profile_completed');
            $table->index('min_price');
            $table->index('max_price');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('is_banned');
        });

        Schema::table('pending_registrations', function (Blueprint $table) {
            $table->index('otp_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['profile_completed']);
            $table->dropIndex(['min_price']);
            $table->dropIndex(['max_price']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_banned']);
        });

        Schema::table('pending_registrations', function (Blueprint $table) {
            $table->dropIndex(['otp_expires_at']);
        });
    }
};
