<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_chat', function (Blueprint $table) {
            $table->id('message_id');

            $table->foreignId('request_id')
                  ->constrained('service_requests', 'request_id')
                  ->cascadeOnDelete();

            $table->foreignId('sender_id')
                  ->constrained('users', 'user_id')
                  ->cascadeOnDelete();

            $table->text('message');
            $table->timestamp('sent_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_chat');
    }
};