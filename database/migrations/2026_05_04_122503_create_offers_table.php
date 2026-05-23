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
        Schema::create('offers', function (Blueprint $table) {
    $table->id('offer_id');
    $table->foreignId('professional_id')->constrained('professionals', 'professional_id')->onDelete('cascade');
    $table->foreignId('request_id')->constrained('service_requests', 'request_id')->onDelete('cascade');
    $table->text('description');
    $table->decimal('price', 10, 2)->nullable();
    $table->string('duration')->nullable(); // مدة التنفيذ
    $table->string('status')->default('pending'); // pending / accepted / rejected
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};