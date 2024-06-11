jQuery.validator.addMethod(
    "alpha",
    function (value, element) {
        return this.optional(element) || /^[A-Za-z\s]+$/i.test(value);
    },
    "Only alphabets and space are accepted"
);
$.validator.addMethod(
    "customPassword",
    function (value, element) {
        // Password must contain at least one uppercase letter, one lowercase letter, one special character, and one number
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(
            value
        );
    },
    "Password must have at least one uppercase letter, one lowercase letter, one special character, and one number."
);
$.validator.addMethod(
    "customCountryCode",
    function (value, element) {
        // Input must contain only one "+" and only numbers, with a maximum length of 4
        return /^(\+\d{1,3})?$/.test(value);
    },
    "Invalid input. Only one '+' and numbers are allowed, with a maximum length of 4."
);

if ($("#addUserForms").length > 0) {
    $("#addUserForms").validate({
        rules: {
            first_name: {
                required: true,
                alpha: true,
                minlength: 3,
                maxlength: 191,
            },
            last_name: {
                required: true,
                alpha: true,
                minlength: 3,
                maxlength: 191,
            },
            email: {
                required: true,
                minlength: 3,
                maxlength: 255,
                email: true,
            },
            phone_number_country_code: {
                required: true,
                minlength: 2,
                maxlength: 4,
                customCountryCode: true,
            },
            phone_number: {
                required: true,
                minlength: 10,
                maxlength: 10,
                number: true,
            },
            password: {
                required: "required",
                minlength: 6,
                customPassword: true,
            },
            password_confirm: {
                minlength: 5,
                equalTo: '[name="password"]',
            },
        },

        messages: {
            first_name: {
                required: "Please provide the first name.",
                minlength: "Please provide the valid first name.",
                maxlength: "Please provide the valid first name.",
            },
            last_name: {
                required: "Please provide the last name.",
                minlength: "Please provide the valid last name.",
                maxlength: "Please provide the valid last name.",
            },
            email: {
                required: "Please provide the email address.",
                email: "Please provide the valid email address.",
                minlength: "Please provide the valid email address.",
                maxlength: "Please provide the valid email address.",
            },
            phone_number_country_code: {
                required: "Please phone code.",
                minlength: "Please provide the valid phone code",
                maxlength: "Please provide the valid phone code",
            },
            password: {
                required: "Password is required.",
                minlength: "Please provide the valid phone code",
                maxlength: "Please provide the valid phone code",
            },
            confirm_password: {
                required: "Confirm Password is required",
                equalTo: "Password not matching",
            },
        },

        errorPlacement: function (label, element) {
            label.addClass("m-t-1 text-danger");
            $(element).removeClass("error");
            if (element.is("select")) {
                label.appendTo(element.parent("div.perform-error-block"));
            } else {
                label.insertAfter(element);
            }
        },
    });
}

$(document).on("click", ".editUser", function (e) {
    e.preventDefault();
    let $this = $(this),
        url = $this.attr("data-action");
    ajaxSetupFun();
    $.ajax({
        url: url,
        method: "get",
        success: function (response) {
            if (response.success) {
                $("#editLeadData").html(response.html);

                $("#editLeadForm").validate({
                    rules: {
                        contact_person_id: {
                            required: true,
                        },
                        company_id: {
                            required: true,
                        },
                        lead_domain_id: {
                            required: true,
                        },
                        lead_source_id: {
                            required: true,
                        },
                        lead_potential: {
                            required: true,
                        },
                        lead_stage: {
                            required: true,
                        },
                        "technologies[]": "required",
                        title: {
                            required: true,
                            alpha: true,
                            minlength: 3,
                            maxlength: 256,
                        },
                    },

                    messages: {
                        contact_person_id: {
                            required: "Please provide the contact person.",
                        },
                        lead_domain_id: {
                            required: "Please provide the lead domain.",
                        },
                        lead_source_id: {
                            required: "Please provide the lead source.",
                        },
                        company_id: {
                            required: "Please provide the company.",
                        },
                        lead_potential: {
                            required: "Please provide the lead potential.",
                        },
                        lead_stage: {
                            required: "Please provide the lead stage.",
                        },
                        "technologies[]": {
                            required: "Please provide technologies.",
                        },
                        title: {
                            required: "Please provide the title.",
                            minlength:
                                "Please enter a value greater than or equal to 3",
                            maxlength:
                                "Please enter a value less than or equal to 255.",
                        },
                    },

                    errorPlacement: function (label, element) {
                        label.addClass("m-t-1 text-danger");
                        $(element).removeClass("error");
                        if (element.is("select")) {
                            label.appendTo(
                                element.parent("div.perform-error-block")
                            );
                        } else {
                            label.insertAfter(element);
                        }
                    },
                });
            } else {
                swalWithBootstrapButtons.fire(
                    "Error",
                    response.message,
                    "error"
                );
                $("#editCompanyModal").modal("hide");
            }
        },
        error: function (jqXHR, exception) {
            let msg = ajaxGetErrorMessage(jqXHR, exception);
            swalWithBootstrapButtons.fire("Cancelled", msg, "error");
        },
    });
});

$(document).on("submit", "#editUserForm", function (e) {
    e.preventDefault();
    let $this = $(this),
        url = $this.attr("action"),
        formData = $this.serialize(),
        currentPage = $("#current_page").val();
    saveFrom = $(".saveFrom");
    ajaxSetupFun();
    subLoader(saveFrom, true);
    $.ajax({
        url: url,
        method: "PUT",
        data: formData,
        success: function (response) {
            subLoader(saveFrom, false, "Create");
            if (response.success) {
                resetForm($("#editLeadForm"));
                $("#editLeadModal").offcanvas("hide"); //Close the modal
                getUserList(currentPage);
                swalWithBootstrapButtons.fire(
                    "success",
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
            subLoader(saveFrom, false, "Create");
            let msg = ajaxGetErrorMessage(jqXHR, exception);
            swalWithBootstrapButtons.fire("Cancelled", msg, "error");
        },
    });
});
