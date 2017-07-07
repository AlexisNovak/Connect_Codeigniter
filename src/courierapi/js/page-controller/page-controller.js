define(function () {
    return {
        initWindow: function () {
            $("#api-toggle").unbind("click");
            $("#menu-toggle").unbind("click");
            if ($(window).width() > 800) {
                $("#api-toggle").click(function (e) {
                    $(".wrapper").toggleClass("show-test-tool");
                });

                $("#menu-toggle").click(function (e) {
                    $(".wrapper").toggleClass("show-index");
                });
            } else if ($(window).width() <= 800) {
                if ($(".wrapper").hasClass("show-index")) {
                    $(".wrapper").toggleClass("show-index");
                }
                if ($(".wrapper").hasClass("show-test-tool")) {
                    $(".wrapper").toggleClass("show-test-tool");
                }

                $("#api-toggle").click(function (e) {
                    $(".wrapper").toggleClass("show-test-tool");
                    $(".wrapper").removeClass("show-index");
                });

                $("#menu-toggle").click(function (e) {
                    $(".wrapper").toggleClass("show-index");
                    $(".wrapper").removeClass("show-test-tool");
                });
            }
            console.log($(window).width());
        }

    }
});
