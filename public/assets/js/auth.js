$(document).on("submit", "#forgotPasswordEmailForm", function (e) {
    e.preventDefault();
    let url = $(this).attr("action");
    let email = $("#registered_email").val();
    let $this = $(".send-password-reset-link-button");

    subLoader($this, true);
    ajaxSetupFun();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            email: email,
        },
        success: function (response) {
            subLoader($this, false, "Send");
            if (response.success) {
                document.getElementById("forgotPasswordEmailForm").reset(); // Reset form
                $("#forgotPasswordModal").modal("hide"); //Close the modal

                swalWithBootstrapButtons.fire(
                    "Password reset link sent",
                    response.message,
                    "success"
                );
            } else {
                swalWithBootstrapButtons.fire(
                    "Error",
                    response.message,
                    "error"
                );
            }
        },
        error: function (jqXHR, exception) {
            subLoader($this, false, "Send");
            let msg = ajaxGetErrorMessage(jqXHR, exception);
            swalWithBootstrapButtons.fire("Cancelled", msg, "error");
        },
    });
});
$(document).ready(function () {
    $(".hide-show-password").on("click", function () {
        const password = $("#password-input");
        const type = password.attr("type") === "password" ? "text" : "password";
        password.attr("type", type);

        $("#password-input-icon").toggleClass("ri-eye-off-fill ri-eye-fill");
    });
});
