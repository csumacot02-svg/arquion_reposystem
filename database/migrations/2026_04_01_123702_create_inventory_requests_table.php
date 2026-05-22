<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('department');
            $table->string('item_name');
            $table->string('category');
            $table->integer('quantity_requested');
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->date('date_needed');
            $table->text('purpose');
            $table->text('remarks')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('warehouse_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_requests');
    }
};
