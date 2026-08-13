<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->change();
            $table->string('availability')->default('Consultar disponibilidad')->after('colors');
            $table->unsignedInteger('sort_order')->default(0)->after('active');
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->index(['active', 'featured', 'sort_order'], 'idx_products_public_listing');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index(['active', 'sort_order'], 'idx_categories_public_menu');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_public_listing');
            $table->dropForeign(['category_id']);
            $table->dropColumn(['availability', 'sort_order']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_public_menu');
        });
    }
};
