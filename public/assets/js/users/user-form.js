 flatpickr(".flatpickr-input", {
     altInput: true,
     altFormat: "F j, Y",
     dateFormat: "Y-m-d",
     maxDate: "today",
       
 });
 
 document
     .querySelector("#user-image-input")
     .addEventListener("change", function () {
         var preview = document.querySelector("#user-img");
         var file = document.querySelector("#user-image-input").files[0];
         var reader = new FileReader();
         reader.addEventListener(
             "load",
             function () {
                 preview.src = reader.result;
             },
             false
         );
         if (file) {
             reader.readAsDataURL(file);
         }
     });
$(document).on("click", ".address-clone",function(){
    var section = $(this).attr("data-section");
    // get HOF address Details
    var nativeVillage = $(
        '#HOF-member-address select[name="native_village_id"]'
    ).val();
    var address = $('#HOF-member-address input[name="address"]').val();
    var countryId = $('#HOF-member-address select[name="country_id"]').val();
    var cityId = $('#HOF-member-address select[name="city_id"]').val();
    var stateId = $('#HOF-member-address select[name="state_id"]').val();
    
    // set address to member
    // $("#" + section + ' select[name="native_village_id"]').val(nativeVillage);
      $("#" + section + ' select[name="native_village_id"]')
          .val(nativeVillage)
          .change();
    $("#" + section + ' input[name="address"]').val(address);
    $("#" + section + ' select[name="country_id"]')
        .val(countryId)
        .change();
    setTimeout(
        function(){
                $("#" + section + ' select[name="state_id"]')
                    .val(stateId)
                    .change();
        },500
    );
    setTimeout(function () {
        $("#" + section + ' select[name="city_id"]')
            .val(cityId)
            .change();
    }, 1000);
    
});