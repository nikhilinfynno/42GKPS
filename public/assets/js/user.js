$(function () {
    if (typeof isListShow != "undefined" && isListShow == true) {
        getUserList();
    }
});

window.getUserList = function (page = 1, reset_page = false) {
    let characterSearch = $("#characterSearch").val() ?? "",
        sortType = $("#sort_type").val() ?? "",
        sortColumn = $("#sort_column").val() ?? "",
        recordPerPage = $("#record-per-page").val() ?? "",
        url = $("#tableList").attr("data-action");
    masterEle = $("#tableList").parents(".master-board");
    if (reset_page) {
        page = 1;
    }

    // Show ajax loader
    masterEle.find(".ajax-loader-row").show();

    $.get(
        url +
            "?page=" +
            page +
            "&record_per_page=" +
            recordPerPage +
            "&character_search=" +
            characterSearch +
            "&sort_type=" +
            sortType +
            "&sort_column=" +
            sortColumn,
        function (response, status) {
            masterEle.find(".ajax-loader-row").hide();
            $("#ajax-pagination").html("");
            $("#rowData").html(response.html);
            $("#ajax-pagination").html($(".pagination").html());
            $("#paging_info_update").html($("#paging_info").html());
            $("#current_page").val(response.page);
        }
    );
};

$(document).on("submit", "#addUserForm", function (e) {
    e.preventDefault();
    let $this = $(this),
        url = $this.attr("action"),
        formData = $this.serialize(),
        saveFrom = $(".saveFrom");
    ajaxSetupFun();
    subLoader(saveFrom, true);
    $.ajax({
        url: url,
        method: "POST",
        data: formData,
        success: function (response) {
            subLoader(saveFrom, false, "Create");
            if (response.success) {
                resetForm($("#addUserForm"));
                $("#addUserModal").offcanvas("hide");
                if (typeof isListShow != "undefined" && isListShow == true) {
                    getUserList();
                }
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
                $("#editUserData").html(response.html);
                $("#editLeadForm").validate({
                    rules: {
                        contact_person_id: {
                            required: true,
                        },
                        company_id: {
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

$(document).on("submit", "#editLeadForm", function (e) {
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

function leadStatus(element) {
    let $this = element,
        url = $this.attr("data-action"),
        status = $this.attr("data-status"),
        id = $this.attr("data-lead-id");
    if (status == 2) {
        ajaxSetupFun();
        $.ajax({
            url: url,
            method: "post",
            data: {
                status: status,
                id: id,
            },
            success: function (response) {
                if (response.success) {
                    swalWithBootstrapButtons.fire(
                        "success",
                        response.message,
                        "success"
                    );
                    window.location.href = response.url;
                } else {
                    swalWithBootstrapButtons.fire(
                        "Error",
                        response.message,
                        "error"
                    );
                }
            },
            error: function (jqXHR, exception) {
                let msg = ajaxGetErrorMessage(jqXHR, exception);
                swalWithBootstrapButtons.fire("Cancelled", msg, "error");
            },
        });
    } else {
        $("#reasonLeadId").val(id);
        $("#leadStatus").val(status);
    }
}

function statusReason(element) {
    let $this = element,
        statusReason = $this.val();
    if (statusReason == 5) {
        $("#otherReason").removeClass("d-none");
    } else {
        $("#otherReason").addClass("d-none");
    }
}

if ($("#addLeadStatusReasonForm").length > 0) {
    $("#addLeadStatusReasonForm").validate({
        rules: {
            reason: {
                required: true,
            },
            other_reason: {
                required: function (element) {
                    return $("#reason").val() == 5;
                },
            },
        },

        messages: {
            reason: {
                required: "Please provide the reason.",
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

$(document).on("submit", "#addLeadStatusReasonForm", function (e) {
    e.preventDefault();
    let $this = $(this),
        url = $this.attr("action"),
        formData = $this.serialize(),
        saveFrom = $(".saveFrom");
    ajaxSetupFun();
    subLoader(saveFrom, true);
    $.ajax({
        url: url,
        method: "POST",
        data: formData,
        success: function (response) {
            subLoader(saveFrom, false, "Create");
            if (response.success) {
                resetForm($("#addLeadStatusReasonForm"));
                $("#addLeadStatusModal").modal("hide"); //Close the modal
                swalWithBootstrapButtons.fire(
                    "success",
                    response.message,
                    "success"
                );
                window.location.href = response.url;
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

$(document).on("change", ".filter", function () {
    getUserList();
    $(".clearButton").removeClass("d-none");
});

$(document).on("click", ".clearButton", function () {
    $(this).addClass("d-none");
    location.reload();
});

function leadStageChange(element) {
    ($this = element),
        (url = $this.attr("data-action")),
        (leadStage = $this.val()),
        (leadId = $this.attr("data-id"));
    ajaxSetupFun();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            lead_stage: leadStage,
            id: leadId,
        },
        success: function (response) {
            if (response.success) {
                getUserList();
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
            let msg = ajaxGetErrorMessage(jqXHR, exception);
            swalWithBootstrapButtons.fire("Cancelled", msg, "error");
        },
    });
}

function contactPersonCompany(element) {
    let $this = element,
        url = $this.attr("data-action"),
        contactPersonId = $this.val();
    ajaxSetupFun();
    $.ajax({
        url: url,
        method: "get",
        data: {
            contact_person_id: contactPersonId,
        },
        success: function (response) {
            if (response.success) {
                $(".contactPeronCompany").empty();
                $(".contactPeronCompany").append(
                    '<option value="">Select Company</option>'
                );
                $(".contactPeronCompany").append(response.html);
            } else {
                $(".contactPeronCompany").empty();
                $(".contactPeronCompany").append(
                    '<option value="">Select Company</option>'
                );
            }
        },
        error: function (jqXHR, exception) {
            let msg = ajaxGetErrorMessage(jqXHR, exception);
            swalWithBootstrapButtons.fire("Cancelled", msg, "error");
        },
    });
}

$(document).on("click", "#addNewCompanyData", function () {
    let contactPersonId = $("#contactPerson").val();
    $("#companyContactPersonId").val(contactPersonId);
});

function leadAssignedUserChange(element) {
    ($this = element),
        (url = $this.attr("data-action")),
        (leadAssing = $this.val()),
        (leadId = $this.attr("data-id"));
    ajaxSetupFun();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            lead_assign_to_user: leadAssing,
            id: leadId,
        },
        success: function (response) {
            if (response.success) {
                getUserList();
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
            let msg = ajaxGetErrorMessage(jqXHR, exception);
            swalWithBootstrapButtons.fire("Cancelled", msg, "error");
        },
    });
}
