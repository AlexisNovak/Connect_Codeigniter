define(["./menu"], function (menu) {
    return {
        //$(".api-section").click(function (e) {
        toggleSection: function (element) {
            var toggleId = element.find("span");
            $(element).toggleClass("active");
        }
    }
});
