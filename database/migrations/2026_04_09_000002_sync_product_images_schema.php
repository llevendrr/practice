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
            if (! Schema::hasColumn('product_images', 'filename')) {
                $table->string('filename')->nullable();
            }

            if (! Schema::hasColumn('product_images', 'mime_type')) {
                $table->string('mime_type', 100)->nullable();
            }

            if (! Schema::hasColumn('product_images', 'image_data')) {
                $table->binary('image_data')->nullable();
            }

            if (! Schema::hasColumn('product_images', 'is_primary')) {
                $table->boolean('is_primary')->default(false);
            }

            if (! Schema::hasColumn('product_images', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0);
            }
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

        if (Schema::hasColumn('product_images', 'path')) {
            DB::statement("
                UPDATE product_images
                SET filename = COALESCE(filename, NULLIF(path, ''))
                WHERE (filename IS NULL OR filename = '')
            ");
        }

        if (Schema::hasColumn('product_images', 'url')) {
            DB::statement("
                UPDATE product_images
                SET filename = COALESCE(filename, NULLIF(url, ''))
                WHERE (filename IS NULL OR filename = '')
            ");
        }

        if (DB::connection()->getDriverName() === 'mysql' && Schema::hasColumn('product_images', 'image_data')) {
            DB::statement('ALTER TABLE product_images MODIFY image_data LONGBLOB NULL');
        }

        DB::statement("
            UPDATE product_images
            SET mime_type = COALESCE(NULLIF(mime_type, ''), 'application/octet-stream')
            WHERE mime_type IS NULL OR mime_type = ''
        ");

        Schema::table('product_images', function (Blueprint $table) {
            if (Schema::hasColumn('product_images', 'path')) {
                $table->dropColumn('path');
            }

            if (Schema::hasColumn('product_images', 'url')) {
                $table->dropColumn('url');
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
            if (! Schema::hasColumn('product_images', 'path')) {
                $table->string('path')->nullable();
            }

            if (! Schema::hasColumn('product_images', 'is_main')) {
                $table->boolean('is_main')->default(false);
            }

            if (! Schema::hasColumn('product_images', 'sort')) {
                $table->integer('sort')->default(0);
            }
        });

        DB::table('product_images')->update([
            'is_main' => DB::raw('is_primary'),
            'sort' => DB::raw('sort_order'),
            'path' => DB::raw('COALESCE(filename, path, "")'),
        ]);
    }
};
