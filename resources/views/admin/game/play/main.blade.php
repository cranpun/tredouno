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
    $turncolor = "#D0FAFF";
    ?>
    <div>
        <style type="text/css">
            .wrap-card {
                padding: 5px;
                border: 2px solid white;
                display: inline-block;
                margin: 5px;
            }

            .wrap-card.canput {
                border: 2px dotted orange;
            }

            .wrap-card.canput:hover {
                opacity: 0.6;
            }
        </style>
        @if ($game->isTurn($user->id))
            <style type="text/css">
            body {
                background: {{ $turncolor }};
            }
            .wrap-card {
                border-color: {{ $turncolor }};
            }
            </style>
            <?php
            $cards = $game->getCardsByStatus(\App\Models\User::user()->id);
            ?>
            <h2>あなたの番です</h2>
            {{--
            <div>
                <h3>まとめて出せる札</h3>
                <ul>
                    @foreach ($game->turninfo['groups'] as $group)
                        <li><?php print_r($group); ?></li>
                    @endforeach
                </ul>
            </div>
            --}}
            <div>
                <h3>あなたの持ち札</h3>
                <section style="justify-content: flex-start;">
                    @foreach ($cards as $card)
                        @if (\App\S\CardName::canPutCard($card, $game->getHeadCard(), $game->cardevent, $game->eventdata))
                            <form method="POST" enctype="multipart/form-data" class="simple-form wrap-card canput"
                                style="display: inline-block; min-width: auto;"
                                action="{{ route(\App\Models\User::user()->pr('-game-putcard'), ['game_id' => $game->id, 'cardname' => $card]) }}">
                                @csrf
                                <button type="submit" class="is-mini"
                                    style="border: none; background: transparent; margin: 0; padding: 0;">
                                    @include('admin.game._c.card', ['cardname' => $card])
                                </button>
                                @if (count($cards) == 2)
                                    <div style="text-align: center">UNO</div>
                                @elseif(count($cards) == 1)
                                    <div style="text-align: center">あがり</div>
                                @endif
                            </form>
                        @else
                            <span class="wrap-card">
                                @include('admin.game._c.card', ['cardname' => $card])
                            </span>
                        @endif
                    @endforeach
                </section>
            </div>
            <div id="div-event" style="padding-left: 7px; padding-top: 15px;">
                @if ($game->cardevent == \App\L\CardEvent::ID_AFTERPULL)
                    <form method="POST" enctype="multipart/form-data" class="simple-form" style="display: inline-block;"
                        action="{{ route(\App\Models\User::user()->pr('-game-pass'), ['game_id' => $game->id]) }}">
                        @csrf
                        <button type="submit" class="is-mini">このまま持つ</button>
                    </form>
                @elseif (in_array($game->cardevent, [\App\L\CardEvent::ID_DRAW2]))
                    <h3>ドロー2が使われました。カードを引いてください。</h3>
                    <form method="POST" enctype="multipart/form-data" class="simple-form" style="display: inline-block;"
                        action="{{ route(\App\Models\User::user()->pr('-game-pass'), ['game_id' => $game->id]) }}">
                        @csrf
                        <button type="submit" class="is-mini">2枚カードを引く</button>
                    </form>
                @elseif (in_array($game->cardevent, [\App\L\CardEvent::ID_WILD4]))
                    <h3>ワイルドドロー4が使われました。カードを引くか、チャレンジするか選んでください。</h3>
                    <div><small>※チャレンジ：他に出せるカードがあるのにワイルドドロー4を出したか確認</small>
                        <form method="POST" enctype="multipart/form-data" class="simple-form"
                            style="display: inline-block;"
                            action="{{ route(\App\Models\User::user()->pr('-game-challenge'), ['game_id' => $game->id, 'value' => \App\L\OnOff::ID_OFF]) }}">
                            @csrf
                            <button type="submit" class="is-mini">そのまま4枚カードを引く</button>
                        </form>
                        <form method="POST" enctype="multipart/form-data" class="simple-form"
                            style="display: inline-block;"
                            action="{{ route(\App\Models\User::user()->pr('-game-challenge'), ['game_id' => $game->id, 'value' => \App\L\OnOff::ID_ON]) }}">
                            @csrf
                            <button type="submit" class="is-mini">チャレンジ（失敗の場合は6枚）</button>
                        </form>
                    @elseif (in_array($game->cardevent, [\App\L\CardEvent::ID_COLOR_WILD, \App\L\CardEvent::ID_COLOR_WILD4]))
                        <h3>色を選択してください。</h3>
                        @foreach (\App\S\CardName::colors() as $clr)
                            <form method="POST" enctype="multipart/form-data" class="simple-form"
                                style="display: inline-block; min-width: auto;"
                                action="{{ route(\App\Models\User::user()->pr('-game-color'), ['game_id' => $game->id, 'color' => $clr]) }}">
                                @csrf
                                <button type="submit" class="is-mini"
                                    style="border: none; background-color: {{ \App\S\CardName::colorValue($clr) }}; color: #CCCCCC;">
                                    &nbsp;&nbsp;
                                </button>
                            </form>
                        @endforeach
                    @else
                        <form method="POST" enctype="multipart/form-data" class="simple-form"
                            style="display: inline-block;"
                            action="{{ route(\App\Models\User::user()->pr('-game-pullcard'), ['game_id' => $game->id]) }}">
                            @csrf
                            <button type="submit" class="is-mini">山札からカードを引く</button>
                        </form>
                @endif
            </div><!-- #div-controll -->
        @else
            <h2>{{ $game->players[0]->display_name }}の番です</h2>
            <h3>あなたの持ち札</h3>
            <section style="justify-content: flex-start;">
                @foreach ($game->getCardsByStatus(\App\Models\User::user()->id) as $card)
                    <span class="wrap-card">
                        @include('admin.game._c.card', ['cardname' => $card])
                    </span>
                @endforeach
            </section>
            {{-- 自動リロード --}}
            <script type="text/javascript">
                window.addEventListener("load", function() {
                    setInterval(function() {
                        location.reload();
                    }, 3 * 1000);
                });
            </script>
        @endif
    </div>

    @if ($game->getHeadCard())
        <div class="pr">
            <h2>場の札</h2>
            <div style="display: inline-block;">
                <span style="padding: 5px; margin: 5px;">
                    @include('admin.game._c.card', ['cardname' => $game->getHeadCard()])
                </span>
                @if (in_array($game->cardevent, [\App\L\CardEvent::ID_WILD]))
                    <div style="text-align: center">
                        （<span style="color: {{ \App\S\CardName::colorValue($game->eventdata) }}">■</span>）
                    </div>
                @endif
            </div>
        </div>
    @endif

    @include('admin.game._c.gamestatus')

    <hr />
    <a href="{{ route(\App\Models\User::user()->pr('-home')) }}">
        <b>部屋一覧に戻る</b>
    </a>

@endsection
