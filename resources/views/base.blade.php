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
    <header id="header">
        <nav role="navigation">
            <?php if(!$user): ?>
            @include('pub.topnav')
            <?php else: ?>
            @include('admin.topnav')
            <?php endif; ?>
        </nav>
    </header>
    <main id="main">
        @include('message')
        <section id="contents-{{ Route::currentRouteName() }}">
            @yield('main')
        <section>
    </main>
</body>

</html>
