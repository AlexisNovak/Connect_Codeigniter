define(["api/initApi"], function (apis) {
    return {
        //$(".api").click(function (e) {
        getApi: function (element) {
            var section = element.closest('ul').attr('id');
            var buttonId = element.attr('id').split("_").join(" ");
            var result = apis.api[section];
            var api = {};
            var name;

            $.each(result, function (keyPos, key) {
                $.each(result[keyPos], function (index) {
                    if (index == buttonId) {
                        api = result[keyPos][index];
                        name = index;
                        return false;
                    }
                })
            });

            return {
                api: api,
                name: name
            }
        },
        generateApiInfo: function (apiWrapper, accessKey) {
            $(".quickstart").hide();
            $(".information").show();

            if (accessKey != '') {
                $("input[name='access_key']").val(accessKey);
            }

            $("#response-data").val('');
            $("#request-data").val('');
            var api = apiWrapper.api;
            var uri = api["uri"];
            var method = api["method"];
            var description = api["description"];
            var parameters = "<table class=\"table table-striped\"><th>Name</th><th>Description</th><th>Required?</th></tr>";
            var responseParameters = "<table class=\"table table-striped\"><th>Name</th><th>Description</th><th>type</th></tr>";
            var requestHeader =""
            
            $.each(api["request-header"],function(callback){
                requestHeader+="<tr><th>"+callback+"</th><td>"+api["request-header"][callback]+"</td></tr>"
            });

            if (api["request-parameters"].length != 0) {
                $.each(api["request-parameters"], function (key) {
                    parameters += "<tr>";
                    $.each(api["request-parameters"][key], function (k) {
                        var paramDesc = api["request-parameters"][key][k]["description"];
                        var required = api["request-parameters"][key][k]["mandatory"];
                        var type = api["request-parameters"][key][k]["type"];

                        parameters += "<td>" + k + "</td><td>" + paramDesc + "</td>";

                        if (required) {
                            parameters += "<td class='yes text-right'>Yes</td>";
                        } else {
                            parameters += "<td class='no text-right'>No</td>";
                        }
                    });
                });
                parameters += "</tr>";
            } else {
                parameters += "<tr class=\"none\"><td colspan=3>none</td></tr>"
            }

            if (api["response-parameters"].length != 0) {
                $.each(api["response-parameters"], function (key) {
                    var hasExtraInfo = key.hasOwnProperty("additional info");
                    responseParameters += "<tr>"
                    $.each(api["response-parameters"][key], function (k) {
                        var paramDesc = api["response-parameters"][key][k]["description"];
                        var type = api["response-parameters"][key][k]["type"];

                        responseParameters += "<td>" + k + "</td>"
                        responseParameters += "<td>" + paramDesc + "</td>";
                        responseParameters += "<td>" + type + "</td>";
                    });
                });
                responseParameters += "</tr>";
            } else {
                responseParameters += "<tr class=\"none\"><td colspan=3>none</td></tr>"
            }
            responseParameters += "</table>";
            var apiResponse = "";
            $.each(api["response"], function (key) {
                apiResponse += "<div class=response><h3 class=page-header>Response</h3><div class=response-content><h4 class=page-header>Header</h4><div class=\"table-responsive table-request-header\"><table class=\"table table-borderless\">";
                $.each(api["response"][key]["header"], function (header) {
                    apiResponse += "<tr><th>" + header + "</th><td>" + api["response"][key]["header"][header] + "</td></tr>";
                });
                apiResponse += "</table></div>"
                apiResponse += "<h4 class=page-header>Body</h4><a href=\"#\" class=\"response-block\"><pre><code>" + JSON.stringify(api["response"][key]["json"], null, '\t') + "</code></pre></a></div></div>";
            });

            $("#api-response").html(apiResponse);
            $("#api-name").html(apiWrapper.name);
            $("#method").html(method);
            $("#request-header").html(requestHeader);
            $("#api-url").html(" "+uri);
            $("#api-description").html(description);
            $("#response-parameter-table").html(responseParameters);
            $("#parameter-table").html(parameters);
        }
    }
});
