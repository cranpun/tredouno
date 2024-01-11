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
                <?php $label = "ID : {$game->id} ( {$game->created_at} )"; ?>
                <li>
                    @if ($game->isPlaying())
                        <a href="{{ route(\App\Models\User::user()->pr('-game-play'), ['game_id' => $game->id]) }}">
                            {{ $label }}【プレイ中】
                        </a>
                    @else
                        <form method="POST" enctype="multipart/form-data" class="simple-form"
                            action="{{ route(\App\Models\User::user()->pr('-game-enterstore'), ['game_id' => $game->id]) }}">
                            @csrf
                            <button type="submit" class="simple-button">
                                {{ $label }}
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
@endsection
