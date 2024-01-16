<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitColor
{
    public function color(\Illuminate\Http\Request $request, $game_id, $color)
    {
        $game = \App\Models\Game::find($game_id);
        $user = \App\Models\User::user();
        $ret = redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);

        $nextevent = [
            \App\L\CardEvent::ID_COLOR_WILD => \App\L\CardEvent::ID_WILD,
            \App\L\CardEvent::ID_COLOR_WILD4 => \App\L\CardEvent::ID_WILD4,
        ];

        if ($game->isPlaying()) {
            if ($game->isTurn($user->id)) {
                try {
                    $game->last_event_at = now();
                    // 順番を次の人へ
                    $order = $game->orderArr();
                    array_splice($order, 0, 1);
                    $order[] = $user->id;
                    $game->order = join(",", $order);

                    $game->setCardEvent($nextevent[$game->cardevent], $color);

                    $game = \DB::transaction(function () use ($game) {
                        $game = \App\U\U::save(function () use ($game) {
                            $game->save();
                            return $game;
                        }, "パスに失敗しました。");
                        return $game;
                    });
                } catch (\Exception $e) {
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