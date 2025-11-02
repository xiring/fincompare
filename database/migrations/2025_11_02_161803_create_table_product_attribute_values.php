<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained('attributes')->cascadeOnDelete();
            $table->text('value_text')->nullable();
            $table->decimal('value_number', 15, 4)->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->json('value_json')->nullable();
            $table->timestamps();
            $table->unique(['product_id','attribute_id']);
            $table->index(['attribute_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('product_attribute_values'); }
};
