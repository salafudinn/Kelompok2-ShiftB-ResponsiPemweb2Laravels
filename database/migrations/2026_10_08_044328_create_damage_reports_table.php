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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->nullable()->constrained('borrowings')->onDelete('cascade');
            $table->foreignId('iot_kit_id')->constrained('iot_kits')->onDelete('cascade');
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->enum('damage_type', ['MINOR_COMPONENT', 'BROKEN_BOARD', 'MISSING_PARTS']);
            $table->text('description');
            $table->enum('repair_status', ['REPORTED', 'IN_REPAIR', 'RESOLVED', 'DISCARDED'])->default('REPORTED');
            $table->text('repair_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
