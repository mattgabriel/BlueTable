function checkRestaurantStatus() {
    Action = 'retrieveRestaurantStatus';
    $.ajax({
        type: "POST",
        url: "RestaurantDetails_actions",
        data: {Action: Action},
        async: true,
        success: function(data, textStatus, jqXHR)
        {
            var tablesResult = $.parseJSON(data);
            var freeTablesReport = '<ul>';
            
            console.log(tablesResult);
            for (var i = 0; i < tablesResult['freeTables'].length; i++) {
                freeTablesReport += '<li>';
                freeTablesReport += '</p> Table ' + tablesResult['freeTables'][i].TableNumber + ' is free</p>';
                freeTablesReport += '</li>';
            }

            freeTablesReport += '</ul>';
            var occupiedTablesReport = '<ul>';
            
            for (var i = 0; i < tablesResult['occupiedTables'].length; i++) {
                occupiedTablesReport += '<li>';
                occupiedTablesReport += '<p> Table ' + tablesResult['occupiedTables'][i].TableNumber + ' is occupied by ' + tablesResult['occupiedTables'][i].Username;
                occupiedTablesReport += '</p></li>';
            }

            occupiedTablesReport += '</ul>';
            
            var todaysIncome = '<ul>';
            
            for (var i = 0; i < tablesResult['income'].length; i++) {
                todaysIncome += '<li>';
                todaysIncome += '<p> Order of &pound;' + tablesResult['income'][i].TotalPrice + ' at table ' + tablesResult['income'][i].TableNumber + ' was paid by ' + tablesResult['income'][i].Username;
                todaysIncome += '</p></li>';
            }

            todaysIncome += '</ul>';
            $('#freeTableDiv').html(freeTablesReport);
            $('#occupiedTableDiv').html(occupiedTablesReport);
            $('#priceIn').html(todaysIncome);
            setTimeout(function() {
                checkRestaurantStatus();
            }, 5000);
        },
    });
   
}

$(document).ready(function() {
    checkRestaurantStatus();
});



