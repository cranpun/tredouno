<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitReady
{
    public function ready(\Illuminate\Http\Request $request, $game_id)
    {
        // 特にポストするデータはなし。
        // $data = $request->all();
        // $data["enroll_id"] = $enroll_id;
        // $val = \App\Models\Lesson::validaterule();
        // \Validator::make($data, $val)->validate(); // throw exception

        $game = \App\Models\Game::find($game_id);
        if($game->isPlaying()) {
            // プレイ中なのでフィールドに移動
            return redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);
        }

        $game->loadPlayers();

        return view("admin.game.ready.main", compact(["game"]));
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
