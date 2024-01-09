const U = {};
U.toast = {}
U.toast.normal = function(title, message) {
    Swal.fire({
        title: title,
        text: message,
        timer: 3000,
        toast: true,
        position: "bottom",
        showConfirmButton: false
    });
}
U.toast.error = function(title, message) {
    Swal.fire({
        title: title,
        text: message,
        icon: "error",
        showCancelButton: true,
    });
}
U.toast.confirm = async function(title, message) {
    const ret = await Swal.fire({
        title: title,
        text: message,
        icon: "question",
        showCancelButton: true,
    });
    return ret.isConfirmed;
}
