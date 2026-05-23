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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id('commission_id');

            $table->foreignId('admin_id')
                  ->constrained('admins', 'admin_id')
                  ->cascadeOnDelete();

            $table->foreignId('professional_id')
                  ->constrained('professionals', 'professional_id')
                  ->cascadeOnDelete();

            $table->foreignId('request_id')
                  ->constrained('service_requests', 'request_id')
                  ->cascadeOnDelete();

            $table->decimal('amount', 10, 2);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};