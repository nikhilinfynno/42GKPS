window.showToastbar = function (status, message) {
     if (status === "success") {
         var toastbartype = "success";
     } else {
         var toastbartype = "danger";
     }
    
    Toastify({
        duration: 2000,
        text: message,
        newWindow: true,
        close: true,
        className: "bg-" + toastbartype,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        offset: {
            x: 50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
            y: 10, // vertical axis - can be a number or a string indicating unity. eg: '2em'
        },
        // style: {
        //     background: "linear-gradient(to right, #00b09b, #96c93d)",
        // },
        onClick: function () {}, // Callback after click
    }).showToast();
}

