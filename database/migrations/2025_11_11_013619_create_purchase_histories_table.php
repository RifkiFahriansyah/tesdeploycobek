<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->restrictOnDelete();

            // snapshot item agar histori tetap akurat walau menu berubah
            $table->string('menu_name');
            $table->decimal('unit_price', 12, 0);
            $table->unsignedInteger('qty')->default(1);

            $table->decimal('line_total', 12, 0);         
            $table->timestamps();
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_histories');
    }
};
