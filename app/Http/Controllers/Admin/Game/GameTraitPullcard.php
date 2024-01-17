<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPullcard
{
    public function pullcard(\Illuminate\Http\Request $request, $game_id)
    {
        $game = \App\Models\Game::find($game_id);
        $user = \App\Models\User::user();
        $ret = redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);

        if ($game->isPlaying()) {
            if ($game->isTurn($user->id)) {
                try {
                    $game->last_event_at = now();
                    $pullname = $game->deal($user->id, 1);

                    // draw系等、引いた後には出せないカードの判定
                    $cardObj = new \App\S\CardName($pullname[0]);
                    $isAllow = !in_array($cardObj->kind, ["wild4", "draw2"]); // これ以外ならOK

                    if ($isAllow && \App\S\CardName::canPutCard($pullname[0], $game->getHeadCard(), $game->cardevent, $game->eventdata)) {
                        // 出せるカードだったら出す。
                        if ($game->cardevent) {
                            // 有効なCardEventの最中だったら次に渡すために保持
                            $eventdata = json_encode([
                                "cardevent" => $game->cardevent,
                                "eventdata" => $game->eventdata,
                            ]);
                        } else {
                            $eventdata = null;
                        }
                        $game->setCardEvent(\App\L\CardEvent::ID_AFTERPULL, $eventdata);
                        $ret->with("message-success", "出せるカードを引きました。出すのであれば選択してください。そのままであればパスを押してください。");
                    } else {
                        // 順番を次の人へ
                        $game->nextOrder();
                    }

                    $game = \DB::transaction(function () use ($game, $user) {
                        $game = \App\U\U::save(function () use ($game, $user) {
                            $game->save();
                            return $game;
                        }, "カードに引くのを失敗しました。");
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
