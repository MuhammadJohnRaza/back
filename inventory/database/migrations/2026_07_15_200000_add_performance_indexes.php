<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add performance indexes to frequently queried columns.
 *
 * Q&A Note: Indexes improve read performance for WHERE, ORDER BY, and JOIN
 * operations. The tradeoff is slightly slower writes (INSERT/UPDATE) because
 * MySQL must also update the index B-tree. For an inventory system where reads
 * far outnumber writes, these indexes are well worth the tradeoff.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // products: indexed for the low-stock filter (quantity <= minimum_stock_level)
        // and for slug-based lookups
        Schema::table('products', function (Blueprint $table) {
            $table->index('quantity', 'products_quantity_index');
            $table->index('slug', 'products_slug_index');
        });

        // categories: slug is used in friendly URL lookups
        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug', 'categories_slug_index');
        });

        // stock_movements: created_at is used for date-range reports
        // (e.g. movements_today in the summary report)
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('created_at', 'stock_movements_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_quantity_index');
            $table->dropIndex('products_slug_index');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_slug_index');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('stock_movements_created_at_index');
        });
    }
};
