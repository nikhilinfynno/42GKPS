$(function() {
    $(".hof_form").validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            phone: {
                required: true,
                maxlength: 10,
                minlength: 10,
            },
            first_name: {
                required: true,
                minlength: 3,
            },
            middle_name: {
                required: true,
                minlength: 3,
            },
            last_name: {
                required: true,
                minlength: 3,
            },
            occupation_id: {
                required: true,
            },
            education_id: {
                required: true,
            },
            country_id: {
                required: true,
            },
            state_id: {
                required: true,
            },
            city_id: {
                required: true,
            },
            address: {
                required: true,
                minlength: 3,
            },
            native_village_id: {
                required: true,
            },
            relation: {
                required: true,
            },
        },
        errorPlacement: function (label, element) {
            label.addClass("mt-1 text-danger");
            label.insertAfter(element);
        },
    });
    $(".member_form").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
            },
            middle_name: {
                required: true,
                minlength: 3,
            },
            last_name: {
                required: true,
                minlength: 3,
            },
            email: {
                required: false,
                email: true,
            },
            phone: {
                required: false,
                maxlength: 10,
                minlength: 10,
            },
            relation: {
                required: true,
            },
            occupation_id: {
                required: true,
            },
            education_id: {
                required: true,
            },
            country_id: {
                required: true,
            },
            state_id: {
                required: true,
            },
            city_id: {
                required: true,
            },
            address: {
                required: true,
                minlength: 3,
            },
            native_village_id: {
                required: true,
            },
        },
        errorPlacement: function (label, element) {
            label.addClass("mt-1 text-danger");
            label.insertAfter(element);
        },
    });
});

