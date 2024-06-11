$(document).ready(function () {
      var dropdownElement = document.getElementById(
          "page-header-user-dropdown"
      );
      var dropdown = new bootstrap.Dropdown(dropdownElement);
});
$(function () {
    
    
    var dataTable = $("#postDataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#postDataTable").data("load"),
            data: function (d) {
                // add category filter data
                d.category_id = $("#categoryFilter").val();
            },
        },
        columns: [
            {
                data: "title",
                name: "title",
                render: function (data) {
                    return data.length > 50 ? data.substr(0, 50) + "..." : data;
                },
            },
            { data: "categories", name: "categories" },
            {
                data: "status",
                name: "status",
                render: function (data) {
                    return data == 1
                        ? '<span class="badge bg-primary-subtle text-primary text-uppercase p-2">Draft</span>'
                        : '<span class="badge bg-success-subtle text-success text-uppercase p-2">Published</span>';
                },
            },
            {
                data: "is_free",
                name: "is_free",
                render: function (data) {
                    return data == 1
                        ? '<span class="badge bg-secondary-subtle text-secondary text-uppercase p-2">Free</span>'
                        : '<span class="badge bg-warning-subtle text-warning text-uppercase p-2">Premium</span>';
                },
            },
            { data: "created_at", name: "created_at", searchable: false },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
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
    var defaultSelection = $("#flatpickrRange").attr("data-default");
    if (defaultSelection != "") {
        $("#flatpickrRange").val(defaultSelection);
         var columnIndex = 4;
         dataTable.column(columnIndex).search(defaultSelection).draw();
        setTimeout(function () {
            // dataTableLoadFilter(defaultSelection, dataTable);
        }, 500);
    }
    var flatpickrInstance = flatpickr("#flatpickrRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        // defaultDate: [startDate, endDate],
        onClose: function (selectedDates, dateStr, instance) {
            // dataTableLoadFilter(dateStr, dataTable);
             var columnIndex = 4;
             dataTable.column(columnIndex).search(dateStr).draw();
        },
    });
    function dataTableLoadFilter(dateStr, dataTable) {
        var dateRange = dateStr.split(" to ");
        var min = dateRange[0];
        var max = dateRange[1];
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            let date = new Date(data[4]);
            console.log(date);
            if (
                (min === null && max === null) ||
                (min === null && date <= max) ||
                (min <= date && max === null) ||
                (min <= date && date <= max)
            ) {
                return true;
            }
            return false;
        });
        dataTable.draw();
        
    }
    $("#postStatus").on("change", function () {
        var selectedValue = $(this).val();
        var columnIndex = 2; // Index of the column to search in (zero-based index)
        dataTable.column(columnIndex).search(selectedValue).draw();
    });
    $("#postType").on("change", function () {
        var selectedValue = $(this).val();
        var columnIndex = 3;
        dataTable.column(columnIndex).search(selectedValue).draw();
    });
    $("#categoryFilter").on("change", function () {
        var selectedValue = $(this).val();
        var columnIndex = 1;
        dataTable.column(columnIndex).search(selectedValue).draw();
    });
    $("#postTypeStatus").on("change", function () {
        var selectedValue = $(this).val();
        var columnIndex = 5;
        dataTable.column(columnIndex).search(selectedValue).draw();
    });
});
