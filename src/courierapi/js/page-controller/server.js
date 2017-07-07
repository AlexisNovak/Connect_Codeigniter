define(['config/getConfig'], function (config) {
    var serverList = config.configObject["servers"];
    return {
        initServerSelect: function () {
            $.each(serverList, function (server) {
                $("#server-list").append("<li class=server><a href=\"#\">" + serverList[server]["name"] + "</a></li>");
            });
        },
        serverSelect: function (servername) {
            //$(".server").click(function () {
            $("#server-name").text(servername);
        },
        getServer: function (servername) {
            var serverURL;
            $.each(serverList,function(callback){
               if(serverList[callback]["name"]==servername){
                   serverURL = serverList[callback]["url"];
                   return false;
               } 
            });
            return serverURL;
        },
        getServerList: function () {
            return serverList;
        }
    }
});
