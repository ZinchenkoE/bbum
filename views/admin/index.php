<div id="Index" data-objs="Index" style="padding: 50px;">
    <div id="np-map">
        <button type="button" id="npw-map-open-button">НАЙБЛИЖЧЕ ВІДДІЛЕННЯ</button>
        <script async="" defer=""
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPhm7Q29X5ldwjLtA7IMYHU_0xATiWK3A"></script>
    </div>
    <button id="qq">Запрос</button>
    <script>
        var Index = {
            handlers: {
                "#qq:click": function(){
                    var params = {
                        "modelName": "Address",
                        "calledMethod": "getCities",
                        "apiKey": "fe6e03d4eecde92caf8c527979c861bf"
                    };
                    $.ajax({
                        url: 'https://api.novaposhta.ua/v2.0/json/?' + $.param(params),
                        type: 'POST',
                        dataType: 'jsonp',
                    }).done(function (res) {
                        console.log(res);
                    }).fail(function () {
                        console.error('__error__');
                    });
                },
            },
            ready: function () {}
        };
    </script>
</div>
