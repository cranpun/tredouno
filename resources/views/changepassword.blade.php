<a id="topnav-changepassword">
    パスワード変更
</a>
<form id="topnav-changepassword-form" method="POST"
    action="{{ route(\App\Models\User::user()->pr('-user-changepassword')) }}" enctype="multipart/form-data"
    style="display: none;">
    @csrf
</form>
<script type="text/javascript">
    window.addEventListener("load", function() {
        document.querySelector("#topnav-changepassword").addEventListener("click", function() {
            Swal.fire({
                title: "パスワード変更",
                html: "新しいパスワードを入力してください。<br/>（8文字以上）",
                icon: "info",
                input: "password",
                inputAttributes: {
                    minlength: 8,
                },
                showCancelButton: true,
            }).then(function(res) {
                if (res.isConfirmed) {
                    const pass = res.value;
                    const input = document.createElement("input");
                    input.value = pass;
                    input.name = "password";
                    input.type = "hidden";
                    const form = document.querySelector("#topnav-changepassword-form");
                    form.appendChild(input);
                    form.submit();
                }
            });
        });
    });
</script>
