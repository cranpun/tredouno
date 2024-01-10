<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $passchangeme = '$2y$10$Sbjg0oCnsavSp71.IJvtEuiiFAc9S6vgBpX.3AwanJkzJk88rCG.C';
        $now = now();
        $base = [
            'password' => $passchangeme,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        \DB::table('user')->insert([
            [
                'name' => 'tredouno_dev_admin',
                'display_name' => 'こうちゃん',
            ] + $base,
            [
                'name' => 'yochan',
                'display_name' => 'ようちゃん',
            ] + $base,
            [
                'name' => 'haruchan',
                'display_name' => 'ハルちゃん',
            ] + $base,
        ]);
    }
}
