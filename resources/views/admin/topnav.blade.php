<?php $user = \App\Models\User::user(); ?>
<a id="topnav-logo" href="{{ route(\App\Models\User::user()->pr('-home')) }}">
    <img alt="{{ config('app.name') }} : tredouno" src="{{ \App\U\U::publicfiletimelink('logo.jpg') }}"
        style="width: 96px;">
</a>

<ul>
    <li>
        @include('changepassword')
    </li>
    <li>
        @include('logout')
    </li>
</ul>
