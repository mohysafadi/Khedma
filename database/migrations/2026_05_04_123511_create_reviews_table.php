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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');

            $table->foreignId('customer_id')
                ->constrained('customers', 'customer_id')
                ->cascadeOnDelete();

            $table->foreignId('professional_id')
                ->constrained('professionals', 'professional_id')
                ->cascadeOnDelete();

            $table->foreignId('request_id')
                ->constrained('service_requests', 'request_id')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating'); // من 1 إلى 10
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
