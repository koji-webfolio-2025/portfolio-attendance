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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // 紐づくユーザー
            $table->date('date'); // 勤務日
            $table->timestamp('clock_in')->nullable(); // 出勤時刻
            $table->timestamp('clock_out')->nullable(); // 退勤時刻
            $table->json('breaks')->nullable(); // 休憩情報（配列で格納）
            $table->text('note')->nullable(); // 備考（修正申請用）
            $table->boolean('is_requesting')->default(false); // 修正申請中かどうか
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
