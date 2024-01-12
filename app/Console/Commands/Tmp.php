<?php

namespace App\Console\Commands;

use App\L\CardState;
use Illuminate\Console\Command;

class Tmp extends Command
{
    use \App\Http\Controllers\Admin\Game\GameTraitPlaystore;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tmp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $game = new \App\Models\Game();
        $game->addOrder(1);
        $game->addOrder(2);
        $game->last_event_at = now();
        $game->playing = \App\L\OnOff::ID_ON;
        $game->save();
        $game = \App\Models\Game::find($game->id);
        $game = $this->playstore_dealcard($game);
        $game->delete();
    }
}
