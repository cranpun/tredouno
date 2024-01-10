<!DOCTYPE html>
<html lang="ja">
<?php $user = \App\Models\User::user(); ?>

<head>
    @include('head')
    <title>
        @yield('title') - tredouno
    </title>
</head>

<body id="body">
    <header id="header" style="padding-bottom: 0px;">
        <nav role="navigation" style="margin-bottom: 0px;">
            <?php if(!$user): ?>
            @include('pub.topnav')
            <?php else: ?>
            @include('admin.topnav')
            <?php endif; ?>
        </nav>
    </header>
    <main id="main">
        @include('message')
        <div id="contents-{{ Route::currentRouteName() }}">
            @yield('main')
        <div>
    </main>
</body>

</html>
