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
        Schema::table('customers', function (Blueprint $table) {

            // لا تحذف governorate و city لأنهم غير موجودين
            // فقط أضف city_id إذا كان غير موجود

            if (!Schema::hasColumn('customers', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable()->after('governorate_id');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('city_id');
        });
    }
};
