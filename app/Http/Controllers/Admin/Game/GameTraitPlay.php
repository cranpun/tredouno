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

        // 自分のターンであればturn情報を作成
        $user_id = \App\Models\User::user()->id;
        if($game->isTurn($user_id)) {
            $game->turninfo = [];
            $cards = $game->getCardsByStatus($user_id);
            $game->turninfo["groups"] = \App\S\CardName::makePutGroups($cards);
        } else {
            $game->turninfo = null;
        }

        return view("admin.game.play.main", compact(["game"]));
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
