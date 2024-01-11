@extends('base')

@section('title')
    ログイン
@endsection

@section('labeltitle')
    ログイン
@endsection

@section('labelsubtitle')
@endsection

@section('main')
    <section>
        <style type="text/css">
            header {
                display: none;
            }

            section {
                align-items: center;
                flex-flow: column;
            }
        </style>
        <figure style="text-align: center;">
            <img alt="tredouno" src="{{ \App\U\U::publicfiletimelink('logo.jpg') }}"
                style="display: block; max-width: 335px; width: 100%;">
        </figure>
        <form method="POST" action="{{ route('authenticate') }}">
            @csrf
            <fieldset>
                <label for="name">アカウント</label>
                <input name="name" id="name" type="text" value="{{ old('name') }}" placeholder="アカウントを入力" />
            </fieldset>
            <fieldset>
                <label for="password">パスワード</label>
                <input name="password" id="password" type="password" value="" placeholder="パスワードを入力" />
            </fieldset>
            <fieldset>
                <button id="act-login" type="submit">
                    ログイン
                </button>
            </fieldset>
        </form>
    </section>
@endsection
