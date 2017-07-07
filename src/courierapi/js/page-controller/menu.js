define(["api/initApi"], function (apidata) {
    return {
        generateIndex: function () {
            var apis = apidata.api;
            $.each(apis, function (keySection) {
                var sectionName = keySection.split("-").join(" ");
                $("#api-index").append("<li><a class=\"api-section\" href=#" + keySection + " data-toggle='collapse' data-parent=#api-index>" + sectionName + "<span id=" + keySection + "-icon class=\"pull-right glyphicon glyphicon-chevron-left\"></span></a>")
                $("#api-index").append("<ul id=\"" + keySection + "\" class='collapse list-unstyled'>")
                $.each(apis[keySection], function (keyApi) {
                    $.each(apis[keySection][keyApi], function (keyName) {
                        var serviceName = keyName;
                        if (serviceName.length > 25) {
                            serviceName = serviceName.replace(keyName.substr(25, keyName.length), "...");
                        }
                        keyName = keyName.split(" ").join("_");
                        $("#" + keySection).append("<li class='api-wrapper'><a href='#' id='" + keyName + "' class='api'>" + serviceName + "</a></li>");
                    });
                });
                $("#api-index").append("</ul>");
                $("#api-index").append("</li>");
            });
        }
    }
});
