<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submitted_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_request_id')->nullable(); // ID returned from Warehouse IMS
            $table->string('requester_name');
            $table->string('requester_email');
            $table->string('department');
            $table->string('item_name');
            $table->string('category');
            $table->integer('quantity_requested');
            $table->string('priority');
            $table->date('date_needed');
            $table->text('purpose');
            $table->text('remarks')->nullable();
            $table->string('submission_status')->default('sent'); // sent, failed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submitted_requests');
    }
};
