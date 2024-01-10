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
    <form method="POST" action="{{ route(\App\Models\User::user()->pr('-game-createstore')) }}" enctype="multipart/form-data"
        class="simple-form">
        @csrf
        <button type="submit">
            <b>新しい部屋</b>
        </button>
    </form>
    @if (count($games) > 0)
        <h2>部屋リスト</h2>
        <ul>
            @foreach ($games as $game)
                <li>
                    <form method="POST" enctype="multipart/form-data" class="simple-form"
                        action="{{ route(\App\Models\User::user()->pr('-game-enterstore'), ['game_id' => $game->id]) }}">
                        @csrf
                        <button type="submit" class="simple-button">{{ $game->created_at }}</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
