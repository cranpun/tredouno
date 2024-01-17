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

    <h1>ID. {{ $game->id }} ({{ $game->created_at }})の部屋</h1>
    @if ($game->cardevent == \App\L\CardEvent::ID_END)
        @foreach ($game->players as $player)
            @if (count($game->getCardsByStatus($player->id)) <= 0)
                <div>
                    <h2>ゲーム終了：勝者 {{ $player->display_name }} </h2>
                </div>
            @endif
        @endforeach
    @endif
    <div>
        <h2>プレイヤー</h2>
        <ul>
            @foreach ($game->players as $player)
                <li>{{ $player->display_name }} ({{ count($game->getCardsByStatus($player->id)) }})</li>
            @endforeach
        </ul>
    </div>

    @if (count(explode(',', $game->order)) > 1)
        <div>
            <form method="POST" enctype="multipart/form-data" class="simple-form"
                action="{{ route(\App\Models\User::user()->pr('-game-playstore'), ['game_id' => $game->id]) }}">
                @csrf
                <button type="submit">始める</button>
            </form>
        </div>
    @endif
    {{-- 自動リロード --}}
    <script type="text/javascript">
        window.addEventListener("load", function() {
            setInterval(function() {
                location.reload();
            }, 3 * 1000);
        });
    </script>

@endsection
