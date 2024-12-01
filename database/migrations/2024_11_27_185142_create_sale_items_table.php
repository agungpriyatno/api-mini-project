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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->uuid('sale_id')->index();
            $table->string('item_code')->index();
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2);
            $table->timestamps();

            $table->primary(['sale_id', 'item_code']);
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('item_code')->references('code')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
