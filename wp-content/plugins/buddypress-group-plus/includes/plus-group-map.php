<?php
/* Map Stuff */
//return the address
function plus_map_address() {
	global $bp, $wpdb;
	$address = groups_get_groupmeta( $bp->groups->current_group->id, 'group_plus_map_field' );
	return $address;
}

function bpgp_group_maps_field() {
	global $bp, $wpdb;
	?>
	<label for="group_plus_map_field"><?php echo get_option('group_plus_map_name') ?></label>
	<input type="text" name="group_plus_map_field" id="group_plus_map_field" value="<?php echo plus_map_address() ?>" />
    <?php
    }

function bpgp_show_group_maps() {
	
		if (!plus_map_address() == "") {
	
	?>
    
    <div id="plus-map-holder" style="float: right;margin: 0 0 0 4%;width: 26%;">
    <h5><?php echo get_option('group_plus_map_name'); ?></h5>
    <p><?php echo plus_map_address() ?></p>
	<div class="map-display">

  <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
  <script type="text/javascript">
      
  var geocoder;
  var map;
  function initialize() {
    geocoder = new google.maps.Geocoder();
       var latlng = new google.maps.LatLng(-34.397, 150.644);
       var myOptions = {
       zoom: 13,
       center: latlng,
       navigationControl: true,
       navigationControlOptions: {
       style: google.maps.NavigationControlStyle.SMALL
       },
       mapTypeControl: true,
       mapTypeControlOptions: {
       style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
       },
       mapTypeId: google.maps.MapTypeId.ROADMAP
       }
     map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
  }      
  function showAddress(address) {
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map, 
            position: results[0].geometry.location
        });
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }      
      
  jQuery(document).ready( function() { initialize(); showAddress('<?php echo plus_map_address() ?>'); } );

  </script>

  <div id="map_canvas" style="width: 250px; height: 220px; position: relative; background-color: rgb(229, 227, 223);"></div>
  </div>
  <form style="padding:19px 0 0;" action="http://maps.google.com/maps" method="get" target="_blank">

<input type="text" name="saddr" class="input" onblur="if (this.value == '') {this.value = 'Your Address';}" onfocus="if (this.value == 'Your Address') {this.value = '';}" value="Your Address" />
<input type="hidden" name="daddr" value="<?php echo plus_map_address() ?>" />
<input type="submit" value="get directions" />
</form>
</div>

  <?php
		}
}
/* END - Map Stuff */

?>