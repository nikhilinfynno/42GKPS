$(document).on("click", ".delete-record", function (e) {
    let url = $(this).attr("data-action");
    Swal.fire({
        title: "Are you sure?",
        text: "Are you want delete this record.",
        icon: "warning",
        showCancelButton: true,
        // confirmButtonClass: "ms-2",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true,
    }).then((result) => {
        if (result.value) {
            ajaxSetupFun();
            $.ajax({
                url: url,
                method: "DELETE",
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Deleted", response.message, "success");
                        window.location.reload();
                    } else {
                        Swal.fire("Error", response.message, "error");
                    }
                },
                error: function (jqXHR, exception) {
                    let msg = ajaxGetErrorMessage(jqXHR, exception);
                    Swal.fire("Cancelled", msg, "error");
                },
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Cancelled", "Your data is safe :)", "error");
        }
    });
});
