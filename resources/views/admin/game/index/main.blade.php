@extends('base')

@section('title')
    プレイルーム
@endsection

@section('labeltitle')
    プレイルーム
@endsection

@section('labelsubtitle')
@endsection

@section('main')
    <form method="POST" action="{{ route(\App\Models\User::user()->pr('-game-createstore')) }}" enctype="multipart/form-data" class="simple-form">
        @csrf
        <button type="submit">
            <b>新しい部屋</b>
        </button>
    </form>
    @if(count($games) > 0)
    <h2>昔の部屋</h2>
    <ul>
        @foreach($games as $game)
        <li>
            <a href="{{ route(\App\Models\User::user()->pr('-game-play'), ['game_id' => $game->id]) }}">{{ $game->created_at }}</a>
        </li>
        @endforeach
    </ul>
    @endif
@endsection
