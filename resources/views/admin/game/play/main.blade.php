@extends('base')

@section('title')
    プレイ
@endsection

@section('labeltitle')
    プレイ
@endsection

@section('labelsubtitle')
@endsection

@section('main')
    <?php
    // 処理に使うデータ
    $user = \App\Models\User::user();
    $hCard = $game->getHeadCard();
    // MYTODO イベントがあるならそれに応じたダイアログを。
    ?>
    <style type="text/css">
        header {
            display: none;
        }

        .is-mini {
            padding: 0px 10px;
        }
    </style>

    <a href="{{ route(\App\Models\User::user()->pr('-home')) }}">
        <b>戻る</b>
    </a>

    <h1>ID. {{ $game->id }} ({{ $game->created_at }})の部屋</h1>

    <div>
        <h2>プレイヤーと残り枚数</h2>
        <ul>
            @foreach ($game->players as $player)
                <li>{{ $player->display_name }} ({{ count($game->getCardsByStatus($player->id)) }})</li>
            @endforeach
        </ul>
    </div>
    <div>
        <h2>場の札</h2>
        <div>
            {{ $hCard }}
            @if(in_array($game->cardevent, [\App\L\CardEvent::ID_WILD, \App\L\CardEvent::ID_WILD4]))
            （{{ \App\S\CardName::colorName($game->eventdata) }}）
            @endif
        </div>
    </div>
    <div>
        @if ($game->isTurn($user->id))
            <h2>あなたの番です</h2>
            <div>
                <h3>まとめて出せる札</h3>
                <ul>
                    @foreach ($game->turninfo['groups'] as $group)
                        <li><?php print_r($group); ?></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3>あなたの持ち札</h3>
                <ul>
                    @foreach ($game->getCardsByStatus(\App\Models\User::user()->id) as $card)
                        <li>
                            {{ $card }}
                            @if (\App\S\CardName::canPutCard($card, $hCard, $game->cardevent, $game->eventdata))
                                <form method="POST" enctype="multipart/form-data" class="simple-form"
                                    style="display: inline-block;"
                                    action="{{ route(\App\Models\User::user()->pr('-game-putcard'), ['game_id' => $game->id, 'cardname' => $card]) }}">
                                    @csrf
                                    <button type="submit" class="is-mini">出す</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div id="div-event">
                @if ($game->cardevent == \App\L\CardEvent::ID_AFTERPULL)
                    <form method="POST" enctype="multipart/form-data" class="simple-form" style="display: inline-block;"
                        action="{{ route(\App\Models\User::user()->pr('-game-pass'), ['game_id' => $game->id]) }}">
                        @csrf
                        <button type="submit" class="is-mini">このまま持つ</button>
                    </form>
                @elseif (in_array($game->cardevent, [\App\L\CardEvent::ID_COLOR_WILD, \App\L\CardEvent::ID_COLOR_WILD4]))
                    @foreach (\App\S\CardName::colors() as $clr)
                        <form method="POST" enctype="multipart/form-data" class="simple-form"
                            style="display: inline-block;"
                            action="{{ route(\App\Models\User::user()->pr('-game-color'), ['game_id' => $game->id, 'color' => $clr]) }}">
                            @csrf
                            <button type="submit" class="is-mini">
                                {{ \App\S\CardName::colorName($clr) }}
                            </button>
                        </form>
                    @endforeach
                @else
                    <form method="POST" enctype="multipart/form-data" class="simple-form" style="display: inline-block;"
                        action="{{ route(\App\Models\User::user()->pr('-game-pullcard'), ['game_id' => $game->id]) }}">
                        @csrf
                        <button type="submit" class="is-mini">山札からカードを引く</button>
                    </form>
                @endif
            </div><!-- #div-controll -->
        @else
            <h2>{{ $game->players[0]->display_name }}の番です</h2>
            <h3>あなたの持ち札</h3>
            <ul>
                @foreach ($game->getCardsByStatus(\App\Models\User::user()->id) as $card)
                    <li>{{ $card }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
