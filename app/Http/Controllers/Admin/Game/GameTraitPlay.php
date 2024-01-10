<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPlay
{
    public function play(\Illuminate\Http\Request $request, $game_id)
    {
        // 特にポストするデータはなし。
        // $data = $request->all();
        // $data["enroll_id"] = $enroll_id;
        // $val = \App\Models\Lesson::validaterule();
        // \Validator::make($data, $val)->validate(); // throw exception

        $game = \App\Models\Game::find($game_id);
        $game->loadPlayers();

        return view("admin.game.play.main", compact(["game"]));
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
