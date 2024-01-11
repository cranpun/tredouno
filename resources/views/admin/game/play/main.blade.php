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
        <h2>あなたの持ち札</h2>
        <ul>
            @foreach ($game->getCardsByStatus(\App\Models\User::user()->id) as $card)
                <li>{{ $card }}</li>
            @endforeach
        </ul>
    </div>
@endsection
