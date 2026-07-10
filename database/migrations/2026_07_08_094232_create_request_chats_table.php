<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_chats', function (Blueprint $table) {
            $table->id('chat_id'); // PK
            $table->unsignedBigInteger('request_id');      // الطلب
            $table->unsignedBigInteger('customer_id');     // الزبون
            $table->unsignedBigInteger('professional_id'); // المهني
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_chats');
    }
};
