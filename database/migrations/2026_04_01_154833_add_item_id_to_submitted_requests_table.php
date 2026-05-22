<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('submitted_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('submitted_requests', function (Blueprint $table) {
            $table->dropColumn('item_id');
        });
    }
};