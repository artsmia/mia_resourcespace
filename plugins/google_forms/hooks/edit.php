<?php
function HookGoogle_formsEditAutolivejs(){
?>
<script type="text/javascript">
//jQuery("document").ready(function(){
    var formfields = [3,100,105];
    var clearLocation = function(){
	for(f=0; f<formfields.length; f++){
            jQuery('#field_'+formfields[f]).val("").effect("highlight").parent(".Question").hide("slow");
            jQuery(".coords").remove();
        }
    };
//    jQuery(document).ready(function(){
       for (a=0; a<formfields.length; a++){
          if(jQuery("#field_"+formfields[a]).val()==""){
            jQuery("#field_"+formfields[a]).parent(".Question").css("display","none")
          }
       }
//    })
    var identifier = jQuery('#field_103').parent(".Question").prop("id");
    if(jQuery('#location-selector').length == 0) {
    jQuery("#"+identifier).after().append("<div class='Question'><label>Location depicted (selector) <br/> <sup>City / State / Country </sup></label><input type='text' class='stdwidth' id='location-selector'><a href='#' onClick='clearLocation()'>Clear</a></div>");
    }
    for(f=0; f<formfields.length; f++){
       jQuery('#field_'+formfields[f]).prop("readonly","readonly").addClass("readonly");
    }
    jQuery('.readonly').click(function(){
        jQuery('#location-selector').effect("highlight");
    });
    initialize();
    var placeSearch, autocomplete;
    var googfields = {
        locality: 'field_100',
        country: 'field_3',
        administrative_area_level_1: 'field_105',
    };
    var componentForm = {
    //  street_number: 'short_name',
    //  route: 'long_name',
      locality: 'long_name',
      administrative_area_level_1: 'long_name',
      country: 'long_name',
     // postal_code: 'short_name'
    };
    function initialize() {
      autocomplete = new google.maps.places.Autocomplete(
       /** @type {HTMLInputElement} */(document.getElementById('location-selector')),
        { types: ['(regions)'] });
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          fillInAddress();
      });
    }
    // [START region_fillform]
    function fillInAddress() {
      var place = autocomplete.getPlace();
      var coords = place.geometry.location;
      //make sure existing coords are removed
      jQuery(".coords").remove();
      //append coordinate data
      jQuery("#mainform").append("<input type='hidden' class='coords' name='coordslat' value='"+coords.D+"'/><input type='hidden' name='coordslong' value='"+coords.k+"' class='coords'/>");
      //clear the values
      for (var component in googfields) {
        document.getElementById(googfields[component]).value = '';
      }
      //populate the values
      for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
          var val = place.address_components[i][componentForm[addressType]];
          document.getElementById(googfields[addressType]).value = val;
          jQuery("#"+googfields[addressType]).parent(".Question").show("slow");
        }
      }
      //clear the selector
      for (var component in googfields) {
        if(document.getElementById(googfields[component]).value != ''){
           document.getElementById('location-selector').value = '';
        }
      }
    }
    // [END region_fillform]
  google.maps.event.addDomListener(window, 'load', initialize);
//});
</script>
<?php
}
?>
