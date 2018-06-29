// blogger/views/default/js/signup.js

$(document).ready(function () {
    $('#username').on('input', function (event) {
        var username = event.target.value;

        if (username.length > 3) {
            $.ajax({
                method: 'GET',
                url: '/blogger/ajax/checkuser.php?name='+username
            }).done(function (result) {
                console.log(result);
                if (result.hasUser) {
                    $(event.target).addClass('is-invalid');
                } else {
                    $(event.target).removeClass('is-invalid');
                }
            });
        } else {
            $(event.target).removeClass('is-invalid');
        }
    });
});

