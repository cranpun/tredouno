<div>
    <style type="text/css">
        header {
            display: none;
        }

        .is-mini {
            padding: 0px 10px;
        }

        .pr {
            padding-right: 15px;
        }
    </style>

    <h1>ID. {{ $game->id }} ({{ $game->created_at }})の部屋</h1>

    <section style="justify-content: flex-start; ">
        <div class="pr">
            <h2>プレイヤーと残り枚数</h2>
            <ul>
                @foreach ($game->players as $player)
                    <?php $cnt = count($game->getCardsByStatus($player->id)); ?>
                    <li>
                        {{ $player->display_name }} ({{ $cnt }})
                        @if ($cnt == 1)
                            【UNO!】
                        @endif
                    </li>
                @endforeach
                <div>
                    山札：{{ count($game->getCardsByStatus(\App\L\CardState::ID_DECK)) }}
                </div>
            </ul>
        </div>
    </section>
</div>
