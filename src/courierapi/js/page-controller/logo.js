define(["config/getConfig"], function (config) {
    return {
        setLogo: function () {
            var logo = config.configObject["logo"];
            var name = config.configObject["brand-name"]
            $("#logo").attr("src", logo);
            $("#brand-name").html("<b>"+name+"</b>&nbsp;");
        }
    }
});
