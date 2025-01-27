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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('goals_name', 15)->require();
            $table->string('goals_description', 240)->nullable($value = true);
            $table->boolean('goals_is_achieved')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestampsTz($precision = 2);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
