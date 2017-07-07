define(['config/getConfig'], function (config) {
    return {
        createIntroduction: function () {
            var configuration = config.configObject;
            var quickStart = "";
            $.each(configuration["instruction"], function (dataDiv) {
                var divisionName = configuration["instruction"][dataDiv]["division-name"];
                var divisionContent = configuration["instruction"][dataDiv]["division-content"];
                quickStart += "<div><h1 class=division-name>" + divisionName + "</h1><p class=\"division-content lead\">" + divisionContent + "</p></div>";
            });
            $(".quickstart").html(quickStart);
        },
        showIntroduction: function(){
            $(".quickstart").show();
            $(".information").hide();
        }
    }
});
