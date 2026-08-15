<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('registration_number')->nullable();
            $table->string('timezone')->default('Asia/Kuala_Lumpur');
            $table->string('locale')->default('en_MY');
            $table->json('settings')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('subscription_status')->default('trial');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
