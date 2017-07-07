requirejs.config({
    deps: ["app"],
    baseUrl: 'js/lib',
    shim: {
        'bootstrap': {
            deps: ['jquery']
        }
    },
    paths: {
        'jquery': 'jquery',
        'json': 'json',
        'text': 'text',
        'notify': 'notify',
        'jsonSerialize': 'jquery.serializejson.min',
        'bootstrap': 'bootstrap.min',
        'config': '../config',
        'api': '../api',
        'page': '../page-controller'
    }
});

// Start the main app logic.
requirejs(['jquery', "bootstrap", "page/page-controller", "page/logo", "page/introduction", "page/menu", "page/toggleMenu", "page/api", "page/server", "api/tool", 'notify'],
    function ($, bootstrap, pageCtrl, logo, intro, menu, toggle, api, server, tool, jsonSerialize, notify) {
        var accessKey = "";
        var serverList = server.getServerList();
        var baseUrl = server.getServer("Production");
        var currentApi;

        $.each(serverList, function (key) {
            console.log(serverList[key]);
        });
        pageCtrl.initWindow();
        $("#loader").hide();
        $("#filepicker-wrapper").hide();
        $(".information").hide();
        $("#introduction-link").click(function (e) {
            intro.showIntroduction();
        })
        server.initServerSelect();

        $(".server").click(function (e) {
            $("#response-data").text("");
            server.serverSelect($(this).text());
        });

        /** if screen-width < 800 **/
        $(window).resize(function () {
            pageCtrl.initWindow();
        });

        /** configuration **/
        intro.createIntroduction();
        logo.setLogo();
        menu.generateIndex();
        $(".api-section").click(function (e) {
            toggle.toggleSection($(this));
        });

        $(".api").click(function (e) {
            if ($(window).width() < 800) {
                $("#menu-toggle").click();
            }
            currentApi = api.getApi($(this));
            api.generateApiInfo(currentApi, accessKey);
        
            tool.setUrl(baseUrl + currentApi.api.uri);
            tool.genParameter(currentApi.api);
        });

        $('form').submit(function (e) {
            e.preventDefault();
            if ($("#server-name").text() != "Mock") {
                console.log(baseUrl + currentApi.api.uri);
                var submitResult = tool.serverSubmit(baseUrl + currentApi.api.uri, currentApi.api);
                if (submitResult != null) {
                    accessKey = submitResult;
                };
            } else {
                tool.mockSubmit(currentApi);
            }
        });
    });
