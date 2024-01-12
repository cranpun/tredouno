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
    ?>
    <style type="text/css">
        header {
            display: none;
        }
    </style>

    <a href="{{ route(\App\Models\User::user()->pr('-home')) }}">
        <b>戻る</b>
    </a>

    <h1>{{ $game->created_at }}の部屋</h1>

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
        <div>{{ $hCard }}</div>
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
                            @if (\App\S\CardName::canPutCard($card, $hCard))
                            【出す】
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
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
