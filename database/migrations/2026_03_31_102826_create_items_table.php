<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();              // Stock Keeping Unit
            $table->string('category');                   // e.g. Electronics, Tools, Raw Materials
            $table->integer('quantity')->default(0);      // Stock on hand
            $table->decimal('unit_price', 10, 2);         // Price per unit
            $table->string('supplier')->nullable();       // Supplier name
            $table->string('location')->nullable();       // Warehouse location / bin
            $table->string('status')->default('active');  // active, inactive, low_stock
            $table->text('description')->nullable();
            $table->string('image')->nullable();          // Image upload (bonus)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};