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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('tracking_code')->nullable();
            $table->text('card_number')->nullable();
            $table->text('amount')->nullable();
            $table->text('date')->nullable();
            $table->text('payers_name')->nullable();
            $table->longText('file_base64')->nullable();
            $table->text('file_path')->nullable();
            $table->text('mime_type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
