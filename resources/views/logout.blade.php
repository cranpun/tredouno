<a id="act-logout" class="navbar-item"
    onclick="event.preventDefault(); document.querySelector('#act-logout-form').submit();">
    <span>ログアウト
        <form id="act-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </span>
</a>
