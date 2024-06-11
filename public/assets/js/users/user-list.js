$(function () {
    var dataTable = $("#userDataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#userDataTable").data("load"),
            data: function (d) {},
        },
        columns: [
            {
                data: "first_name",
                name: "first_name",
                orderable: false,
            },
            { data: "email", name: "email", orderable: false },
            { data: "phone", name: "phone", orderable: false },
            {
                data: "native_village",
                name: "native_village",
                orderable: false,
            },
            { data: "gender", name: "gender", orderable: false },
            {
                data: "status",
                name: "status",
                orderable: false,
                render: function (data) {
                    if (data == 0) {
                        return '<span class="badge bg-danger-subtle text-danger">Inactive</span>';
                    } else if (data == 1) {
                        return '<span class="badge bg-success-subtle text-success">Active</span>';
                    } else {
                        return '<span class="badge bg-warning-subtle text-warning">Deceased</span>';
                    }
                },
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
            {
                data: "middle_name",
                name: "middle_name",
                visible: false,
            },
            {
                data: "last_name",
                name: "last_name",
                visible: false,
            },
        ],
        fixedHeader: true,
        scrollX: true,
        order: [],
        initComplete: function (settings, json) {
            // Initialize tooltips after DataTable data is loaded and rendered
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
    });
    $("#nativeVillage").on("change", function () {
        var selectedValue = $(this).val();
        var columnIndex = 2;
        dataTable.column(columnIndex).search(selectedValue).draw();
    });
    $("#userTypeStatus").on("change", function () {
        var selectedValue = $(this).val();
        var columnIndex = 4;
        dataTable.column(columnIndex).search(selectedValue).draw();
    });
    // var defaultSelection = $("#flatpickrRange").attr("data-default");
    // if (defaultSelection!= ''){
    //     $("#flatpickrRange").val(defaultSelection);
    //      var columnIndex = 3;
    //      dataTable.column(columnIndex).search(defaultSelection).draw();
    //     setTimeout(function () {
    //         // dataTableLoadFilter(defaultSelection, dataTable);
    //     }, 500);
    // }
    // var flatpickrInstance = flatpickr("#flatpickrRange", {
    //     mode: "range",
    //     dateFormat: "Y-m-d",
    //     // defaultDate: [startDate, endDate],
    //     onClose: function (selectedDates, dateStr, instance) {
    //         // dataTableLoadFilter(dateStr, dataTable);
    //         var columnIndex = 3;
    //         dataTable.column(columnIndex).search(dateStr).draw();
    //     },
    // });

});
function dataTableLoadFilter(dateStr, dataTable) {
    var dateRange = dateStr.split(" to ");
    var startDate = dateRange[0];
    var endDate = dateRange[1];
    // Filter rows based on date range
    dataTable.rows().every(function () {
        var rowData = this.data();

        // Parse the date from the table in the format YYYY-MM-DD
        var tableDate = rowData.created_at.split(" ")[0];

        if (
            (startDate === "" || tableDate >= startDate) &&
            (endDate === "" || tableDate <= endDate)
        ) {
            $(this.node()).show();
        } else {
            $(this.node()).hide();
        }

        return true;
    });
}
$(document).on("change", "#date_filter", function () {
    getDateRange();
});
function getDateRange() {
    var date_filter = $("#date_filter").val();
    var custom_range = $("#flatpickrRange").val();
    if (date_filter == "custom") {
        $("#customRangeContainer").css("visibility", "visible");
    } else {
        // $("#customRangeContainer").css("visibility", "hidden");
        // Get the current date
        var currentDate = new Date();
        switch (date_filter) {
            case "current_week":
                var startDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate() - currentDate.getDay()
                ); // Start date of the current week
                var endDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate() - currentDate.getDay() + 6
                ); // End date of the current week
                break;
            case "current_month":
                var startDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    1
                ); // Start date of the current month
                var endDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth() + 1,
                    0
                ); // End date of the current month
                break;
            case "last_7_days":
                var startDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate() - 6
                ); // Start date is 7 days ago
                var endDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate()
                ); // End date is today
                break;
            case "last_30_days":
                var startDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate() - 29
                ); // Start date is 30 days ago
                var endDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate()
                ); // End date is today
                break;
            case "custom":
                // Replace these dates with your custom start and end dates
                var startDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate()
                ); // End date is today
                var endDate = new Date(
                    currentDate.getFullYear(),
                    currentDate.getMonth(),
                    currentDate.getDate()
                ); // End date is today
                break;
            default:
                // Handle the case where an invalid status is provided
                break;
        }
          var flatpickrInstance = $("#flatpickrRange")[0]._flatpickr;
        // Set the start date
        flatpickrInstance.set("minDate",  
            startDate.toISOString().slice(0, 10));
        flatpickrInstance.set("maxDate",  
            endDate.toISOString().slice(0, 10));

        // Set the end date
        // flatpickrInstance.set("maxDate", endDate.toISOString().slice(0, 10));
        console.log(startDate.toISOString().slice(0, 10));
        flatpickrInstance.close();
    }
}
