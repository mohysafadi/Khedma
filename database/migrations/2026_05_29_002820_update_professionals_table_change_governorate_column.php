<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('professionals', function (Blueprint $table) {

            // لا تحذف governorate لأنه غير موجود أصلاً

            // فقط تأكد أن governorate_id موجود
            if (!Schema::hasColumn('professionals', 'governorate_id')) {
                $table->unsignedBigInteger('governorate_id')->after('category_id');
            }
        });
    }

    public function down()
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('governorate_id');
        });
    }
};
