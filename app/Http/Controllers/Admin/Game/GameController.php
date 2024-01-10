<?php

namespace App\Http\Controllers\Admin\Game;

use Illuminate\Http\Request;

class GameController extends \App\Http\Controllers\Controller
{
    // *********************************************************
    // utils
    // *********************************************************

    // *********************************************************
    // action
    // *********************************************************
    use \App\Http\Controllers\Admin\Game\GameTraitIndex;
    use \App\Http\Controllers\Admin\Game\GameTraitCreatestore;
    use \App\Http\Controllers\Admin\Game\GameTraitEnterstore;
    use \App\Http\Controllers\Admin\Game\GameTraitPlay;
}