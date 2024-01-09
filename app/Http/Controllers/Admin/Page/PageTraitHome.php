<?php

namespace App\Http\Controllers\Admin\Page;

trait PageTraitHome
{
    public function home(\Illuminate\Http\Request $request)
    {
        $rows = $this->home_load();

        return view("admin.page.home.main", compact(["rows"]));
    }


    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
    private function home_load()
    {
        return [];
    }
}
