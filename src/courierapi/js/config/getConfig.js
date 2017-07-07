define(['json!../data/config.json'], function (data) {
    console.log(data);
    var config = data;
    return {
        configObject: config,
        setBaseUri: function () {
            return data["servers"];
        },
        
    }
});
