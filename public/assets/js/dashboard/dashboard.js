$(function(){
    $(document).on("change", "#date_filter",function(){
        getDashboardCount();
    });
   var currentDate = new Date();

   // Get the current day of the week (0 for Sunday, 1 for Monday, ..., 6 for Saturday)
   var currentDayOfWeek = currentDate.getDay();

   // Calculate the difference between the current day and the start of the week (Sunday)
   var startOfWeekDiff = currentDayOfWeek - 0; // Assuming Sunday is the start of the week

   // Calculate the start date of the current week by subtracting the difference from the current date
   var startDate = new Date(currentDate);
   startDate.setDate(currentDate.getDate() - startOfWeekDiff);

   // Calculate the end date of the current week by adding the remaining days until Saturday
   var endOfWeekDiff = 6 - currentDayOfWeek; // Remaining days until Saturday
   var endDate = new Date(currentDate);
   endDate.setDate(currentDate.getDate() + endOfWeekDiff);

   // Format the start and end dates as "YYYY-mm-dd" strings
   var formattedStartDate = startDate.toISOString().slice(0, 10);
   var formattedEndDate = endDate.toISOString().slice(0, 10);
    
    flatpickr("#flatpickrRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [formattedStartDate, formattedEndDate],
        onClose: function (selectedDates, dateStr, instance) {
            getDashboardCount();
        },
    });
});

function getDashboardCount(){
    var url = $(location).attr("href");
    var date_filter = $("#date_filter").val();
    var custom_range = $("#flatpickrRange").val();
    // if (date_filter == "custom"){
    //     $("#customRangeContainer").css("visibility",'visible');
    // }else{
    //     $("#customRangeContainer").css("visibility", "hidden");
    // }
    if (
        date_filter != "custom" ||
        (date_filter == "custom" && custom_range != "" && custom_range) !==
            undefined
    ) {
       var concatUrl =
        //    "?date_filter=" +
           encodeURIComponent(date_filter) +
           "?custom_range=" +
          custom_range;
        var userTileUrl = $("#userTile").attr("href");
        var postTileUrl = $("#postTile").attr("href");
        
        // Parse the URL to extract the query parameters
        var urlParams = new URLSearchParams(userTileUrl.split("?")[1]);

        // Set the query parameters
        // urlParams.set('date_filter', encodeURIComponent(date_filter));
        urlParams.set('custom_range', custom_range);

        
        $("#userTile").attr(
            "href",
            userTileUrl.split("?")[0] + "?" + urlParams.toString()
        );
        $("#postTile").attr(
            "href",
            postTileUrl.split("?")[0] + "?" + urlParams.toString()
        );
        
        $.get(url + "?" + urlParams, function (response) {
            if (response.success) {
                $("#totalUsers").text(response.data.totalUsers);
                $("#totalPost").text(response.data.totalPosts);

                $("#totalUsers").attr("data-target", response.data.totalUsers);
                $("#totalPost").data("data-target", response.data.totalPosts);
            } else {
                // showSnackbar(response.success, response.message);
            }
        });
    }
}