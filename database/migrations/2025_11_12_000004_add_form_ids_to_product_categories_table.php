<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->foreignId('pre_form_id')->nullable()->after('is_active')->constrained('forms')->nullOnDelete();
            $table->foreignId('post_form_id')->nullable()->after('pre_form_id')->constrained('forms')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['pre_form_id']);
            $table->dropForeign(['post_form_id']);
            $table->dropColumn(['pre_form_id', 'post_form_id']);
        });
    }
};

