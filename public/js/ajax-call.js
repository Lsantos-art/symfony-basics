function like(id) {
    var Route = Routing.generate('likes');
    $.ajax({
        type: 'POST',
        url: Route,
        data: ({ id: id }),
        async: true,
        dataType: "json",
        success: function(data) {
            window.location.reload();
        }
    });
}