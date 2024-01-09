<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = "user";

    public static function validaterule(): array
    {
        return [
            "name" => "required|unique:user",
            "display_name" => "required",
            "password"=> "required|min:8|confirmed",
        ];
    }

    public function setData(array $data)
    {
        // これ以外のパラメータは変更しない
        $this->password = \App\U\U::getd($data, "password", $this->password);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guaded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return \App\Models\User | null
     */
    public static function user(): \App\Models\User | null
    {
        $user = \Auth::user();
        return $user; /** @phpstan-ignore-line */
    }

    public static function isLogin()
    {
        $user = \App\Models\User::user();
        return $user;
    }

    public function pr(string $str): string
    {
        return "{$this->role}{$str}";
    }
}
