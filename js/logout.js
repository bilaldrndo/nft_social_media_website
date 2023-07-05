$("#logout-btn").on("click", function (e) {
    localStorage.clear();
    window.location.replace("signin.html");
});