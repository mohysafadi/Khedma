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
       Schema::create('service_requests', function (Blueprint $table) {
    $table->id('request_id');
    $table->foreignId('customer_id')->constrained('customers', 'customer_id')->onDelete('cascade');
    $table->foreignId('category_id')->constrained('service_categories', 'category_id')->onDelete('cascade');
    $table->text('description');
    $table->string('address')->nullable();
    $table->string('photo')->nullable(); // صورة المشكلة
    $table->string('status')->default('pending');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};