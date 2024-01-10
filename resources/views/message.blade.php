<?php
$messageError = session('message-error');
$messageSuccess = session('message-success');
$hasErrors = $errors && count($errors->all());
?>
@if ($messageError || $messageSuccess || $hasErrors)
    <section id="message-group">
        <aside>
            @if (session('message-error'))
                <div id="message-error" class=''>{{ session('message-error') }}</div>
            @endif
            @if (session('message-success'))
                <div id="message-success" class=''>{{ session('message-success') }}</div>
            @endif
            <?php if($hasErrors) : ?>
            <div id="message-error-validation" class=''>
                入力内容に問題がございました。該当箇所にエラーメッセージが表示されています。ご確認の上、もう一度お試しください。</div>
            <?php endif; ?>
        </aside>
    </section>
@endif
