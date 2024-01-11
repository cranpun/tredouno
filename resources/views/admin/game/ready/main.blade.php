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
        <h2>プレイヤー</h2>
        <ul>
            @foreach ($game->players as $player)
                <li>{{ $player->display_name }}</li>
            @endforeach
        </ul>
    </div>

    <div>
        <form method="POST" enctype="multipart/form-data" class="simple-form"
            action="{{ route(\App\Models\User::user()->pr('-game-playstore'), ['game_id' => $game->id]) }}">
            @csrf
            <button type="submit">始める</button>
        </form>
    </div>
@endsection
