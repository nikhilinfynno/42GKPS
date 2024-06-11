
$(function () {
    if (typeof isListShow != "undefined" && isListShow == true) {
        getroleList();
    }
});


window.getroleList = function (page = 1, reset_page = false) {
    let characterSearch = $("#characterSearch").val() ?? '',
        recordPerPage = $("#record-per-page").val(),
        url = $("#roleLists").attr("data-action");
        masterEle = $('#roleLists').parents('.master-board');
    if (reset_page) {
        page = 1;
    }
    emptyElementContent($("#inviteUser"));
    masterEle.find('.ajax-loader-row').show();
    
    $.get(url +
        "?page=" +
        page +
        "&record_per_page=" +
        recordPerPage + "&character_search=" +characterSearch,
        function (response, status) {
            masterEle.find('.ajax-loader-row').hide();
            $("#ajax-pagination").html("");
            $('#roleData').html(response.html)
            $("#ajax-pagination").html($(".pagination").html());
            $("#paging_info_update").html($("#paging_info").html());
        }
    );
};

jQuery.validator.addMethod("alpha", function(value, element) {
    return this.optional(element) || /^[A-Za-z\s]+$/i.test(value);
}, "Only alphabets and space are accepted");

if($("#addRoleForm").length > 0){
    $('#addRoleForm').validate({
        rules: {
            name: {
                required:true,
                alpha:true,
                minlength: 3,
                maxlength: 256,
            },
        }, 

        messages: {
            name: {
                required : "Please provide the name.",
                minlength: "Please enter a value greater than or equal to 3",
                maxlength: "Please enter a value less than or equal to 256.",
            },
        },

        errorPlacement: function (label, element) {
            label.addClass("m-t-1 text-danger");
            $(element).removeClass('error');
            label.insertAfter(element);
        }
    });
}

$(document).on('submit', '#addRoleForm', function (e) {
    e.preventDefault();
    let $this = $(this),
        url = $this.attr('action'),
        saveFrom = $('.saveForm'),
        formData = $this.serialize();
    ajaxSetupFun();
    subLoader(saveFrom, true);
    $.ajax({
        url: url,
        method: "POST",
        data: formData,
        success: function (response) {
            if (response.success) {
                subLoader(saveFrom, false, "Create");
                resetForm($('#addRoleForm'));
                $("#addRoleModal").offcanvas("hide"); //Close the modal    
                getroleList();
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
        }
    });
})

$(document).on('click', '.editRole', function (e) {
    e.preventDefault();
    let $this = $(this),
        url = $this.attr('data-action');
    ajaxSetupFun();
    $.ajax({
        url: url,
        method: "get",
        success: function (response) {
            if (response.success) {
                $('#editRoleData').html(response.html);
                $('#editRoleForm').validate({
                    rules: {
                        name: {
                            required:true,
                            alpha:true,
                            minlength: 3,
                            maxlength: 255,
                        },
                    }, 
            
                    messages: {
                        name: {
                            required : "Please provide the name.",
                            minlength: "Please enter a value greater than or equal to 3",
                            maxlength: "Please enter a value less than or equal to 255.",
                        },
                    },
            
                    errorPlacement: function (label, element) {
                        label.addClass("m-t-1 text-danger");
                        $(element).removeClass('error');
                        label.insertAfter(element);
                    }
                });
            } else {
                swalWithBootstrapButtons.fire(
                    "Error",
                    response.message,
                    "error"
                    );
                $('#editRoleModal').modal('hide');
            }
        },
        error: function (jqXHR, exception) {
            let msg = ajaxGetErrorMessage(jqXHR, exception);
            swalWithBootstrapButtons.fire("Cancelled", msg, "error");
        }
    });
})

$(document).on('submit', '#editRoleForm', function (e) {
    e.preventDefault();
    let $this = $(this),
        url = $this.attr('action'),
        formData = $this.serialize(),
        saveFrom = $('.saveForm'),
        currentPage = $("#current_page").val();
    ajaxSetupFun();
    subLoader(saveFrom, true);
    $.ajax({
        url: url,
        method: "PUT",
        data: formData,
        success: function (response) {
            if (response.success) {
                subLoader(saveFrom, false, "Create");
                resetForm($('#editRoleForm'));
                $("#editRoleModal").offcanvas("hide"); //Close the modal 
                getroleList(currentPage);
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
        }
    });
})
