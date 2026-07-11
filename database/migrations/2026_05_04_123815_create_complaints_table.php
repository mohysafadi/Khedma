<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id('complaint_id');

            // صاحب الشكوى (user)
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->onDelete('cascade');

            // الطلب المرتبط بالشكوى
            $table->foreignId('request_id')
                ->constrained('service_requests', 'request_id')
                ->onDelete('cascade');

            // الزبون صاحب الطلب
            $table->foreignId('customer_id')
                ->constrained('customers', 'customer_id')
                ->onDelete('cascade');

            $table->text('message');
            $table->string('status')->default('pending'); // pending, in_review, resolved

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
