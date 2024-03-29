<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPass
{
    public function pass(\Illuminate\Http\Request $request, $game_id)
    {
        $game = \App\Models\Game::find($game_id);
        $user = \App\Models\User::user();
        $ret = redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);

        if ($game->isPlaying()) {
            if ($game->isTurn($user->id)) {
                try {
                    $game->last_event_at = now();

                    // DRAW系のイベントだったらカードを引く
                    if ($game->cardevent == \App\L\CardEvent::ID_DRAW2) {
                        $game->deal($user->id,  2);
                        // カードを引いたのでイベントを書き換え
                        $game->setCardEvent(null, null);
                    } else if ($game->cardevent == \App\L\CardEvent::ID_AFTERPULL && $game->eventdata) {
                        // afterpullの後、退避しているデータがあれば復旧
                        $obj = json_decode($game->eventdata);
                        $game->setCardEvent($obj->cardevent, $game->eventdata = $obj->eventdata);
                    }

                    // 順番を次の人へ
                    $game->nextOrder();

                    $game = \DB::transaction(function () use ($game, $user) {
                        $game = \App\U\U::save(function () use ($game, $user) {
                            $game->save();
                            return $game;
                        }, "失敗しました。");
                        return $game;
                    });
                } catch (\Exception $e) {
                    \Log::error($e);
                    return back()->with("message-error", $e->getMessage())->withInput();
                }
            }
        }

        // 例外が発生しなければ正常に移動
        return $ret;
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
