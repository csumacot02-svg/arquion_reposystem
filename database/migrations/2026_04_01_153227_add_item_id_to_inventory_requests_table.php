<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->foreignId('item_id')
                ->nullable()
                ->after('department')
                ->constrained('items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('item_id');
        });
    }
};