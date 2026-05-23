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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('complaint_id');

            $table->foreignId('complainant_id')
                  ->constrained('users', 'user_id')
                  ->cascadeOnDelete();

            $table->foreignId('against_id')
                  ->constrained('users', 'user_id')
                  ->cascadeOnDelete();

            $table->foreignId('request_id')
                  ->constrained('service_requests', 'request_id')
                  ->cascadeOnDelete();

            $table->foreignId('admin_id')
                  ->nullable()
                  ->constrained('admins', 'admin_id')
                  ->nullOnDelete();

            $table->text('description');
            $table->enum('status', ['pending', 'resolved', 'rejected']);
            $table->text('response')->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};