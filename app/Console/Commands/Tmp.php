<?php

namespace App\Console\Commands;

use App\L\CardState;
use Illuminate\Console\Command;

class Tmp extends Command
{
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
        $game = \App\Models\Game::find(5);
        print_r($game->getCardsByStatus(2));
    }
}
