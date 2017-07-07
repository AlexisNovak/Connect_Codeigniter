define(["config/getConfig", "jsonSerialize", "notify"], function (config, jsonSerialize, notify) {
    var keyName = config.configObject["key-name"];
    return {
        genParameter: function (api) {

            var form = "";
            //check for parameters, exit if none
            if (api["request-parameters"].length == 0) {
                $("#api-request-parameters").html(form);
                return false;
            }

            var b64Target = null;
            var filenameTarget = null;
            var multipleSubmit = api.hasOwnProperty("multipleSubmit") ? true : false;
            var hasFileInput = api["hasFileInput"] ? true : false;

            //check if there is file input, if true, display file input, else hide it.
            if (api["hasFileInput"]) {
                $.each(api["request-parameters"], function (key) {
                    $.each(api["request-parameters"][key], function (k) {
                        var isFileInput = api["request-parameters"][key][k].hasOwnProperty("target") ? true : false;
                        if (isFileInput) {
                            if (api["request-parameters"][key][k]["target"] == "b64") {
                                b64Target = k;
                            } else if (api["request-parameters"][key][k]["target"] == "filename") {
                                filenameTarget = k;
                            } else {
                                console.log("Missing target in " + api);
                            }
                        }

                    });
                });
                
                $("#filepicker").prop("disabled", false);
                $("#filepicker-wrapper").show();
                $("#filepicker").on('change', function () {
                    if (this.files && this.files[0]) {
                        var FR = new FileReader();
                        var filename = this.files[0].name;
                        $("input[name=" + filenameTarget + "]").val(filename);
                        FR.onload = function (e) {
                            var base64 = e.target.result;
                            var data = base64.split(',');
                            var encode = data[1];
                            $("input[name=" + b64Target + "]").val(encode);
                        }
                        FR.readAsDataURL(this.files[0]);
                    }
                });
            } else {
                $("#filepicker").prop("disabled", true);
                $("#filepicker-wrapper").hide();
                b64Target = null;
                filenameTarget = null;
            }
            //generate form params
            if (api["request-parameters"].length != 0) {
                $.each(api["request-parameters"], function (key) {
                    $.each(api["request-parameters"][key], function (k) {
                        var required = api["request-parameters"][key][k]["mandatory"];
                        var type = api["request-parameters"][key][k]["type"];
                        if (required) {
                            form += "<div class=form-group><div class=\"input-group input-group-default\"><span class=\"input-group-addon\" id=\"sizing-addon1\"><i class=\"fa fa-star fa-1 important\"></i>" + k + "</span><input name=\"" + k + "\" type=" + type + " class=\"form-control\" aria-describedby=\"sizing-addon1\"></div></div>";
                        } else {
                            form += "<div class=form-group></span><div class=\"input-group input-group-default\"><span class=\"input-group-addon\" id=\"sizing-addon1\"><i class=\"fa fa-star-half-o fa-1 optional\"></i>" + k + "</span><input name=\"" + k + "\" type=" + type + " class=\"form-control\" aria-describedby=\"sizing-addon1\"></div></div>";
                        }
                    })
                });
                $("#api-request-parameters").html(form);
            }
        },
        mockSubmit: function (apiWrapper) {
            //do something to test local
            var api = apiWrapper.api;
            var dataToSend = $('#api-json-form').serializeJSON();
            var parameters = api["request-parameters"];
            var missingParam = false;
            var isLoginAPI = false;

            if (api.multipleSubmit) {
                dataToSend = $.parseJSON("{\"access_key\":\"" + dataToSend.access_key + "\",\""+api.arrayName+"\":[" + JSON.stringify(dataToSend) + "]}");
            };
            
            $("#request-data").val(JSON.stringify(dataToSend, null, '\t'));
            $.each(api["request-parameters"], function (key) {
                $.each(api["request-parameters"][key], function (k) {
                    if (api["request-parameters"][key][k]["mandatory"]) {
                        if (dataToSend[k] == "") {
                            missingParam = true;
                            return false
                        };
                    };
                })
            });
            if (apiWrapper.name == "login") {
                isLoginAPI = true;
            }

            if (!isLoginAPI && dataToSend["access_key"] == "") {
                $("#response-data").val(JSON.stringify(api["response"][api["tool-response"]["no-token"]]["json"], null, '\t'));
                $("input[value=Submit]").notify('No Token', {
                    className: 'error',
                    position: 'bottom right'
                });
                return false;
            }

            if (!missingParam) {
                $("input[value=Submit]").notify("Successfully retrieve API", {
                    className: 'success',
                    position: 'bottom right'
                });
                $("#response-data").val(JSON.stringify(api["response"][api["tool-response"]["success"]]["json"], null, '\t'));
                if (api["response"][api["tool-response"]["success"]]["json"].hasOwnProperty(keyName)) {
                    $("input[name='access_key']").val(api["response"][api["tool-response"]["success"]]["json"][keyName]);
                }
            } else if (missingParam) {
                $("input[value=Submit]").notify("Missing Parameters", {
                    className: 'error',
                    position: 'bottom right'
                });
                $("#response-data").val(JSON.stringify(api["response"][api["tool-response"]["failure"]]["json"], null, '\t'));
            }

        },
        serverSubmit: function (url, api) {
            $("input[value=Submit]").hide();
            $("#loader").show();
            var dataToSend = $('#api-json-form').serializeJSON();
            $("#request-data").val(JSON.stringify(dataToSend, null, '\t'));
            if (api.multipleSubmit) {
                dataToSend = $.parseJSON("{\"access_key\":\"" + dataToSend.access_key + "\",\""+dataToSend.arrayName+"\":[" + JSON.stringify(dataToSend) + "]}");
            };
            $.ajax({
                url: url,
                type: api.method,
                data: dataToSend,
                dataType: "json",
                contentType: api.contentType,
                success: function (data) {
                    var accessKey = "";
                    if (data.hasOwnProperty(keyName)) {
                        $("input[name='access_key']").val(data[keyName]);
                        accessKey = data[keyName];
                    }
                    $("input[name='baseUrl']").val(url + "?" + $("#api-json-form").serialize());
                    $("#response-data").val(JSON.stringify(data, null, '\t'));
                    $("#loader").hide();
                    $("input[value=Submit]").show();
                    if (data["code"] == 0) {
                        $("input[value=Submit]").notify("Successfully retrieve resources", {
                            className: 'success',
                            position: 'bottom right'
                        });
                    } else if (data["code"] == 100) {
                        $("input[value=Submit]").notify("Invalid input in parameters.", {
                            className: 'warn',
                            position: 'bottom right'
                        });
                    } else {
                        $("input[value=Submit]").notify("Error with Request", {
                            className: 'error',
                            position: 'bottom right'
                        });
                    }
                    return accessKey;
                },
                error: function () {
                    $("#loader").hide();
                    $("input[value=Submit]").show();
                }
            })

        },
        setUrl: function (url) {
            $("input[name='baseUrl']").val(url);
        }
    }
});

//if ($("#wrapper").hasClass("hide-menu")) {
//    $("#wrapper").toggleClass("hide-menu");
//}
