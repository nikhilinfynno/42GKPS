/*
-> All global js constants, variables are initilized here.
-> All global function are defined here.
*/

// Global js contants

// const swalWithBootstrapButtons = Swal.mixin({
//     customClass: {
//         confirmButton: "btn btn-success",
//         cancelButton: "btn btn-danger",
//     },
//     buttonsStyling: false,
// });

/*Global function starts here*/

// Ajax post request csrf token setup
function ajaxSetupFun() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
}

// Ajax exception handler
function ajaxGetErrorMessage(jqXHR, exception) {
    let loginRedirectMessage =
        "If you weren't active on system since last 24 hours then you will be redirected to login page within 3 seconds";
    if (jqXHR.status === 0) {
        msg = "Not connect.\n Verify Network.";
    } else if (jqXHR.status == 404) {
        msg = "Requested page not found. [404]";
    } else if (jqXHR.status == 500) {
        msg = "Internal Server Error [500].";
    } else if (jqXHR.status == 400) {
        msg = jqXHR.responseJSON.message;
    } else if (jqXHR.status == 422) {
        msg = jqXHR.responseJSON.message;
    } else if (jqXHR.status == 403) {
        msg = jqXHR.responseJSON.message;
    } else if (jqXHR.status == 401) {
        msg = jqXHR.responseJSON.message;
        msg += loginRedirectMessage;
        setTimeout(() => {
            location.reload();
        }, 3000);
    } else if (jqXHR.status == 204) {
        msg = jqXHR.responseJSON.message;
    } else if (exception === "parsererror") {
        msg = "Requested JSON parse failed.";
    } else if (exception === "timeout") {
        msg = "Time out error.";
    } else if (exception === "abort") {
        msg = "Ajax request aborted.";
    } else {
        msg = "Uncaught Error.\n" + jqXHR.responseJSON.message;
        msg += loginRedirectMessage;
        setTimeout(() => {
            location.reload();
        }, 3000);
    }
    return msg;
}

function subLoader(ele, isDisable = true, text = "") {
    if (isDisable) {
        ele.attr("disabled", "disabled");
        ele.html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Loading'
        );
    } else {
        ele.removeAttr("disabled");
        ele.text(text);
    }
}

function ajaxLoader(ele, isDisable = true) {
    if (isDisable) {
        ele.html("");
    } else {
        // ele.html('<div class="p-2 text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
        $(ele).html(
            '<tr><div class="text-center"><div class="spinner-border" role="status"></div></div></tr>'
        );
    }
}

function select2Element(elements) {
    $(elements).each(function () {
        let $this = $(this),
            checkDropdownParent = $this.parents(".offcanvas").length ?? 0,
            dropdownParentEle =
                checkDropdownParent != 0 ? $this.parents(".offcanvas") : null;
        if ($this.length > 0) {
            $this.select2({
                width: "100%",
                dropdownParent: dropdownParentEle,
            });
        }
    });
    return true;
}

function select2ElementForModal(elements) {
    $(elements).each(function () {
        let $this = $(this),
            checkDropdownParent = $this.parents(".modal.fade").length ?? 0,
            dropdownParentEle =
                checkDropdownParent != 0 ? $this.parents(".modal.fade") : null;
        if ($this.length > 0) {
            $this.select2({
                width: "100%",
                dropdownParent: dropdownParentEle,
            });
        }
    });
    return true;
}

function oneColumnChecked(ele, className) {
    let parentEle = ele;
    let isParentChecked = parentEle.prop("checked");
    parentEle
        .parents("table")
        .find("tbody tr")
        .each(function () {
            let $this = $(this);
            if ($this.find("." + className).not(":disabled").length) {
                $this
                    .find("." + className)
                    .not(":disabled")
                    .prop("checked", isParentChecked);
            }
        });
    return true;
}

function emptyElementContent(ele) {
    ele.html("");
    return true;
}

 



function resetForm(formEle) {
    let select2Eles = formEle.find(".select2-elements");
    let datepicker = formEle.find(".datepicker-elements");
    resetSelect2Elements(select2Eles);
    resetDatepickerElements(datepicker);
    formEle[0].reset();
    return true;
}

function resetSelect2Elements(elements) {
    $(elements).each(function () {
        if ($(this).length > 0) {
            $(this).val("").trigger("change");
        }
    });
    return true;
}

function resetDatepickerElements(elements) {
    $(elements).each(function () {
        if ($(this).length > 0) {
            $(this).datepicker("setDate", "").trigger("change");
        }
    });
    return true;
}

