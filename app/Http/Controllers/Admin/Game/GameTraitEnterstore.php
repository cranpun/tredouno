<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitEnterstore
{
    public function enterstore(\Illuminate\Http\Request $request, $game_id)
    {
        // 特にポストするデータはなし。
        // $data = $request->all();
        // $data["enroll_id"] = $enroll_id;
        // $val = \App\Models\Lesson::validaterule();
        // \Validator::make($data, $val)->validate(); // throw exception

        try {
            $row = \DB::transaction(function () use ($game_id){
                $row = \App\U\U::save(function () use ($game_id) {
                    $user = \App\Models\User::user();
                    $row = \App\Models\Game::find($game_id);
                    $row->last_event_at = now();
                    $row->addOrder($user->id);
                    $row->save();
                    return $row;
                }, "入室に失敗しました。");
                return $row;
            });

            return redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $row->id]);
        } catch (\Exception $e) {
            return back()->with("message-error", $e->getMessage())->withInput();
        }
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
