<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_city')->nullable()->after('shipping_method');
            $table->string('shipping_street')->nullable()->after('shipping_city');
            $table->string('shipping_house')->nullable()->after('shipping_street');
            $table->string('shipping_apartment')->nullable()->after('shipping_house');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_city',
                'shipping_street',
                'shipping_house',
                'shipping_apartment',
            ]);
        });
    }
};
