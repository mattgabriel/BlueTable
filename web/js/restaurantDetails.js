function checkRestaurantStatus() {
    console.log('1');
    Action = 'retrieveUserAttTables';
    var feedback = $.ajax({
        type: "POST",
        url: "/app/controllers/admin/RestaurantDetails_actions",
        data: {Action: Action},
        async: false,
        success: function(data, textStatus, jqXHR)
        {
            setTimeout(function() {
                console.log(data);
                checkRestaurantStatus();
            }, 5000);
        },
    });
    
    $('#tableDiv').html(feedback);
}

$(document).ready(function() {
    checkRestaurantStatus();
});



