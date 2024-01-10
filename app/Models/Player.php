<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;
    protected $table = "player";
    protected $guarded = [
        "id",
    ];

    public static function validaterule(): array
    {
        return [
            "user_id" => "integer|required",
            "game_id" => "integer|required",
        ];
    }

    public function setData(array $data) {
        $this->user_id = \App\U\U::getd($data, "user_id", $this->user_id);
        $this->game_id = \App\U\U::getd($data, "game_id", $this->game_id);
    }
}
