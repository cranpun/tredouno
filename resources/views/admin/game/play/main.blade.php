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

<ul>
@foreach($game->players as $player)
<li>{{ $player->display_name }}</li>
@endforeach
</ul>

@endsection
