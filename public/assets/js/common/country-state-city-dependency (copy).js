$(document).on("change", "#country_id", function (e) {
    var selectedCountryId = $(this).val();
    var url = $("#state_id").data("url") + "/" + selectedCountryId;
    getLocationData(url, $("#state_id"), function () {
        // Trigger change event for state_id after data is loaded
        $("#state_id").trigger("change");
    });
});

$(document).on("change", "#state_id", function (e) {
    var selectedStateId = $(this).val();
    var url = $("#city_id").data("url") + "/" + selectedStateId;
    getLocationData(url, $("#city_id"));
});

$(document).ready(function () {
    var url = $("#country_id").data("url");
    getLocationData(url, $("#country_id"), function () {
        // Trigger change event for country_id after data is loaded
        $("#country_id").trigger("change");
    });
});

function getLocationData(url, $element, callback) {
    $.get(url, function (data) {
        if (data.success) {
            $element.html(data.html);
            if (typeof callback === "function") {
                callback(); // Execute the callback function if provided
            }
        }
    });
}
