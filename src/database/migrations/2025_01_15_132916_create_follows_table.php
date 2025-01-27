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
        Schema::create('follows', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->foreignId('follower_id') // フォローしているユーザー
                ->constrained('users') // usersテーブルのidを参照
                ->onDelete('cascade'); // ユーザー削除時に関連データも削除
            $table->foreignId('followee_id') // フォローされているユーザー
                ->constrained('users') // usersテーブルのidを参照
                ->onDelete('cascade'); // ユーザー削除時に関連データも削除
            $table->timestamps(); // created_at と updated_at
            $table->unique(['follower_id', 'followee_id']); // 重複防止
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
