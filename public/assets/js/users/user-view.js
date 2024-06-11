$(function () {
    $("#userWalletDataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#userWalletDataTable").data("load"),
        },
        columns: [
            { data: "transaction_id", name: "transaction_id" },
            { data: "payment_id", name: "payment_id" },
            {
                data: "amount",
                name: "amount",
                render: function (data, type, row) {
                    // Check if the type of the transaction is 'credit'
                    if (row.type === "credit") {
                        // Format the number as currency
                        return (
                            '<span class="text-success">' +
                            // parseFloat(data).toFixed(2)
                            data +
                            "</span>"
                        );
                    } else {
                        return (
                            '<span class="text-danger">' +
                            // parseFloat(data).toFixed(2)
                            data +
                            "</span>"
                        );
                    }
                },
            },
            {
                data: "type",
                name: "type",
                render: function (data) {
                    return data == "credit"
                        ? '<span class="badge border border-success text-success">Credit</span>'
                        : '<span class="badge border border-danger text-danger">Debit</span>';
                },
            },
            {
                data: "status",
                name: "status",
                render: function (data) {
                    var $statusBadge;
                    if (data == "1") {
                        $statusBadge =
                            '<span class="badge bg-success-subtle text-success p-2">Completed</span>';
                    } else if (data == "2") {
                        $statusBadge =
                            '<span class="badge bg-warning-subtle text-warning p-2">Pending</span>';
                    } else {
                        $statusBadge =
                            '<span class="badge bg-danger-subtle text-danger p-2">Failed</span>';
                    }
                    return $statusBadge;
                },
            },
            { data: "description", name: "description" },
            { data: "created_at", name: "created_at" },
        ],
        fixedHeader: true,
        scrollX: true,
        order: [],
    });
    
    var userSubscriptionDataTable = $("#userSubscriptionDataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#userSubscriptionDataTable").data("load"),
        },
        columns: [
            { data: "plan", name: "plan" },
            {
                data: "amount",
                name: "amount",
                // render: function (data) {
                //     return "$" + parseFloat(data).toFixed(2);
                // },
            },
            { data: "started_at", name: "started_at" },
            { data: "expires_at", name: "expires_at" },
            {
                data: "is_active",
                name: "is_active",
                render: function (data) {
                    var $statusBadge;
                    if (data == 1) {
                        $statusBadge =
                            '<span class="badge bg-success-subtle text-success p-2">Active</span>';
                    } else {
                        $statusBadge =
                            '<span class="badge bg-danger-subtle text-danger p-2">Cancelled</span>';
                    }
                    return $statusBadge;
                },
            },
        ],
        fixedHeader: true,
        scrollX: true,
        order: [],
    });
     $("#subscriptionStatus").on("change", function () {
         var selectedValue = $(this).val();
         var columnIndex = 4;
         userSubscriptionDataTable
             .column(columnIndex)
             .search(selectedValue)
             .draw();
     });
});
