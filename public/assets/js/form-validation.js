
let emailValidation = {
    required: true,
    email: true
};

emailCheckUrl =
    typeof emailCheckUrl != "undefined" && emailCheckUrl != ""
        ? emailCheckUrl
        : "";

// $(function() {
// LOGIN FORM VALIDATION
$("#loginForm").validate({
    rules: {
        email: emailValidation,
        password: {
            required: true,
            minlength: 6
        }
    },
    errorPlacement: function (label, element) {
        label.addClass("mt-1 text-danger");
        label.insertAfter(element);
    }
});


//Change password form validation
// $("#changePasswordForm").validate({
//     rules: {
//         current_password: {
//             required: true,
//             minlength: 6,
//             remote: {
//                 url: checkCurrentPasswordUrl,
//                 type: "POST",
//                 data: {
//                     "_token": $('[name="_token"]').attr('content')
//                     // current_password: function () {
//                     //     return $("#current_password").val();
//                     // }
//                 }
//             }
//         },
//         new_password: {
//             required: true,
//             minlength: 6
//         },
//         new_password_confirmation: {
//             required: true,
//             minlength: 6,
//             equalTo: "#new_password"
//         }
//     },
//     messages: {
//         new_password_confirmation: {
//             equalTo: "Password confirmation does not match"
//         }
//     },
//     errorPlacement: function (label, element) {
//         label.addClass("mt-1 text-danger");
//         label.insertAfter(element);
//     }
// });


// Forgot password email form
$("#forgotPasswordEmailForm").validate({
    rules: {
        registered_email: emailValidation
    },
    errorPlacement: function (label, element) {
        label.addClass("mt-1 text-danger");
        label.insertAfter(element);
    }
});

//Password reset form
$("#passwordResetForm").validate({
    rules: {
        password: {
            required: true,
            minlength: 6
        },
        password_confirmation: {
            required: true,
            minlength: 6,
            equalTo: "#password"
        },
        email: emailValidation
    },
    messages: {
        password_confirmation: {
            equalTo: "Password confirmation does not match"
        }
    },
    errorPlacement: function (label, element) {
        label.addClass("mt-1 text-danger");
        label.insertAfter(element);
    }
});

