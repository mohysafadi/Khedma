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
        Schema::create('professionals', function (Blueprint $table) {
    $table->id('professional_id');
    $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
    $table->foreignId('category_id')->nullable()->constrained('service_categories', 'category_id')->nullOnDelete();
    $table->string('bio')->nullable();
    $table->integer('experience_years')->default(0);
    $table->string('tool_image')->nullable(); // صورة العدة
    $table->string('governorate');
    $table->string('professional_status')->default('pending');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};