$("#logout-btn").on("click", function (e) {
    var userId = localStorage.getItem("userId");

    $.ajax({
        type: 'POST',
        url: 'https://nft-social-media-4eefe819eec9.herokuapp.com/api/signout/' + userId,
        data: {},
        dataType: 'json',
        beforeSend: function (xhr) {
            xhr.setRequestHeader(
                "Authorization",
                userToken,
            );
        },
        success: function (response) {
            localStorage.clear();
            window.location.replace("signin.html");
        },
        error: function (xhr, status, error) { }
    });
});