<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPlaystore
{
    public function playstore(\Illuminate\Http\Request $request, $game_id)
    {
        $row = \App\Models\Game::find($game_id);
        if (!$row->isPlaying()) {
            try {
                $row = \DB::transaction(function () use ($row) {
                    $row = \App\U\U::save(function () use ($row) {
                        $row->last_event_at = now();
                        $row->playing = \App\L\OnOff::ID_ON;;
                        $row->save();
                        return $row;
                    }, "入室に失敗しました。");
                    return $row;
                });
            } catch (\Exception $e) {
                return back()->with("message-error", $e->getMessage())->withInput();
            }
        }

        // 例外が発生しなければ正常に移動
        return redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $row->id]);
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
