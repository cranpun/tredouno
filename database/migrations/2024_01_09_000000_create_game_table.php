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
        Schema::create('game', function (Blueprint $table) {
            $table->id();
            $table->string("playing")->default(\App\L\OnOff::ID_OFF)->comment("プレイ中か否か。onならプレイ中");
            $table->string("order")->nullable()->comment("プレイ順。カンマつなぎのユーザID。");

            foreach (\App\Models\Game::cardNames() as $cn) {
                $table->integer($cn)->default(\App\L\CardState::ID_DECK); // デフォルトは山札
            }
            $table->datetime("last_event_at")->comment("最後のイベントが発生した日付");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palyer');
    }
};
