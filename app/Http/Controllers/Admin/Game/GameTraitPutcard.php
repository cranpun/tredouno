<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPutcard
{
    // MYTODO 実装
    public function putcard(\Illuminate\Http\Request $request, $game_id, $cardname)
    {
        $game = \App\Models\Game::find($game_id);

        // 一応出せるカードか確認

        // 一応、自分のカードか確認

        // 今の先頭札を捨て札に

        // 今回のカードを先頭札に

        // カードがイベント札であればイベント情報を登録
        // ドロー2、ドロー4くらい？

        // 順番に関する操作
        // // skipとリバース、はそのように
        // // それ以外は今の人


        // if (!$game->isPlaying()) {
        //     try {
        //         $game = \DB::transaction(function () use ($game) {
        //             $game = \App\U\U::save(function () use ($game) {
        //                 $game->last_event_at = now();
        //                 $game->playing = \App\L\OnOff::ID_ON;
        //                 $game = $this->playstore_dealcard($game);
        //                 $game->save();
        //                 return $game;
        //             }, "入室に失敗しました。");
        //             return $game;
        //         });
        //     } catch (\Exception $e) {
        //         return back()->with("message-error", $e->getMessage())->withInput();
        //     }
        // }

        \Log::info($cardname);

        // 例外が発生しなければ正常に移動
        return redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
