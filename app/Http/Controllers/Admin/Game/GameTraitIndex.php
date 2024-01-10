<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitIndex
{
    public function index(\Illuminate\Http\Request $request)
    {
        $games = $this->index_load();

        return view("admin.game.index.main", compact(["games"]));
    }


    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
    private function index_load()
    {
        $rows = \App\Models\Game::query()->orderBy("created_at", "DESC")->get();
        return $rows;
    }
}
