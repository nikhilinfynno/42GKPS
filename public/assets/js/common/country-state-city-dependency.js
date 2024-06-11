$(document).on("change", ".tab-pane.active .country_id", function (e) {
    var selectedCountryId = $(this).val();
    var id = $(this).attr("data-id");
    var stateElement = $(".tab-pane.active #state_id_" + id);
    var url = stateElement.data("url") + "/" + selectedCountryId;
    getLocationData(url, stateElement, function ($this) {
        // Trigger change event for state_id after data is loaded
        $this.trigger("change");
    });
});

$(document).on("change", ".tab-pane.active .state_id", function (e) {
    var selectedStateId = $(this).val();
    var id = $(this).attr("data-id");
    var cityElement = $(".tab-pane.active #city_id_" + id);
    var url = cityElement.data("url") + "/" + selectedStateId;
    getLocationData(url, $("#city_id_" + id));
});

$(document).ready(function () {
    getCountryList();
});
 // load country state city on tab change
$('a.member-tab[data-bs-toggle="pill"]').on("shown.bs.tab", function (e) {
    var member = $(this).attr("data-member");
     var newUrl = new URL(window.location.href);
    newUrl.searchParams.set("member", member);
    history.pushState(null, "", newUrl);
    getCountryList();
    $("input.form-control").removeClass("is-invalid");
    $("label.error").remove();
});

function getCountryList(){
    var url = $(".tab-pane.active .country_id").data("url");
    var id = $(".tab-pane.active  .country_id").attr("data-id");
   
    getLocationData(
        url,
        $(".tab-pane.active  #country_id_" + id),
        function ($this) {
            // Trigger change event for country_id after data is loaded
            $this.trigger("change");
        },
        true
    );
}
function getLocationData(url, $element, callback,is_country = false) {
    var selected = $element.attr("data-selected") ?? null;
   
    if (selected != null || selected != undefined) {
        
        url = url + "?" + "data=" + selected;
    }
     
    $.get(url, function (data) {
        if (data.success) {
            $element.html(data.html);
            if (typeof callback === "function") {
                if (is_country === true) {
                    $(".country_id").each(function () {
                        if ($(this).length > 0) {
                            callback($(this));
                        }
                    });
                }else{
                    callback($element); // Execute the callback function if provided
                }
                    
            }
        }
    });
}
