<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->string('filename')->nullable()->after('product_id');
            $table->string('mime_type', 100)->nullable()->after('filename');
            $table->longBlob('image_data')->nullable()->after('mime_type');
            $table->boolean('is_primary')->default(false)->after('image_data');
            $table->unsignedInteger('sort_order')->default(0)->after('is_primary');
        });

        if (Schema::hasColumn('product_images', 'is_main')) {
            DB::table('product_images')->update([
                'is_primary' => DB::raw('is_main'),
            ]);
        }

        if (Schema::hasColumn('product_images', 'sort')) {
            DB::table('product_images')->update([
                'sort_order' => DB::raw('sort'),
            ]);
        }

        Schema::table('product_images', function (Blueprint $table) {
            if (Schema::hasColumn('product_images', 'path')) {
                $table->dropColumn('path');
            }

            if (Schema::hasColumn('product_images', 'is_main')) {
                $table->dropColumn('is_main');
            }

            if (Schema::hasColumn('product_images', 'sort')) {
                $table->dropColumn('sort');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->string('path')->nullable()->after('product_id');
            $table->boolean('is_main')->default(false)->after('path');
            $table->integer('sort')->default(0)->after('is_main');
        });

        DB::table('product_images')->update([
            'is_main' => DB::raw('is_primary'),
            'sort' => DB::raw('sort_order'),
            'path' => DB::raw("COALESCE(path, '')"),
        ]);

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn(['filename', 'mime_type', 'image_data', 'is_primary', 'sort_order']);
        });
    }
};
