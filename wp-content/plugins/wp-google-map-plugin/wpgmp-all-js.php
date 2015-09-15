<?php
function wpgmp_js_head()
{
global $wpdb,$post;
if( !empty($_GET['location']) )
{
	$user_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations where location_id=%d",$location_name));
	$group_marker = $wpdb->get_row($wpdb->prepare("SELECT group_marker FROM ".$wpdb->prefix."group_map where group_map_id=%d",$user_record->location_group_map));
	if(!empty($group_marker)) 
	{
		$image_src = $group_marker->group_marker;
	}
}
?>
<script type="text/javascript"> 
var geocoder;
var map;
function initialize() {		
	
geocoder = new google.maps.Geocoder();
  
var latlng = new google.maps.LatLng(-34.397, 150.644);
 
var imgurl= "<?php echo $image_src; ?>";
if(imgurl=="")
{
   var image = '<?php echo plugins_url('images/blue-dot.png', __FILE__ ); ?>';
}
else
{
	var image= imgurl;
}
	
  var mapOptions = {
    zoom: 8,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById('map'), mapOptions);
  
  marker = new google.maps.Marker({
                 position: latlng,
                 map: map,
				 
				 draggable:true,
                 icon: image,
    			 animation: google.maps.Animation.DROP,
             });
			 
		google.maps.event.addListener(marker, "dragend", function(event) {
			geocodePosition(marker.getPosition());
			
			map.panTo(marker.getPosition());
			
			jQuery('.google_latitude').val(event.latLng.lat());
            jQuery('.google_longitude').val(event.latLng.lng());
        });	 
        
        
		var input = document.getElementById('googlemap_address');
        
		 var autocomplete = new google.maps.places.Autocomplete(input, {
             types: ["geocode"]
         });
		 
         autocomplete.bindTo('bounds', map);
         var infowindow = new google.maps.InfoWindow();
         google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
             infowindow.close();
             var place = autocomplete.getPlace();
             if (place.geometry.viewport) {
                 map.fitBounds(place.geometry.viewport);
             } else {
                 map.setCenter(place.geometry.location);
                 map.setZoom(17);
             }
			 moveMarker(place.name, place.geometry.location);
             jQuery('.google_latitude').val(place.geometry.location.lat());
             jQuery('.google_longitude').val(place.geometry.location.lng());
         });
         function moveMarker(placeName, latlng)
		 {
             marker.setIcon(image);
             marker.setPosition(latlng);
         }
position_marker_geocodeaddress();
}

function geocodePosition(pos) {
	  
  	geocoder.geocode({
    	latLng: pos
  	},
	
	function(responses)
	{
    	if (responses && responses.length > 0)
		{
      		jQuery('#googlemap_address').val(responses[0].formatted_address);
    	}
		else
		{
      		alert('Cannot determine address at this location.');
    	}
  	});
}

function position_marker_geocodeaddress()
{
	var imgurl= '<?php echo $image_src; ?>';
	if(imgurl=='')
	{
	   var image = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
	}
	else
	{
		var image= imgurl;
	}

	var pos_lat = jQuery('.google_latitude').val();
    var pos_lng = jQuery('.google_longitude').val();
    var latlng = new google.maps.LatLng(pos_lat, pos_lng);
    map.setCenter(latlng);
    var marker = new google.maps.Marker({
      map: map, 
	  icon:image,
	  draggable:true,
      position: latlng
    });

    google.maps.event.addListener(marker, "dragend", function(event) {
		geocodePosition(marker.getPosition());	
		map.panTo(marker.getPosition());
		jQuery('.google_latitude').val(event.latLng.lat());
        jQuery('.google_longitude').val(event.latLng.lng());
    });
}

function geocodeaddress() {
	
	var imgurl= '<?php echo $image_src; ?>';
	if(imgurl=='')
	{
	   var image = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';
	}
	else
	{
		var image= imgurl;
	}
	
  	var address = jQuery('#googlemap_address').val(); 
  	geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      	map.setCenter(results[0].geometry.location);
      	var marker = new google.maps.Marker({
          	map: map,
		  	icon:image,
		  	draggable:true,
          	position: results[0].geometry.location
      	});
	  
	  	google.maps.event.addListener(marker, "dragend", function(event) {
			geocodePosition(marker.getPosition());			
			map.panTo(marker.getPosition());			
			jQuery('.google_latitude').val(event.latLng.lat());
            jQuery('.google_longitude').val(event.latLng.lng());
        });	
	  	
		jQuery('.google_latitude').val(results[0].geometry.location.lat());
		jQuery('.google_longitude').val(results[0].geometry.location.lng());
    }
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script type="text/javascript">
		jQuery(document).ready(function($) {
			// save the send_to_editor handler function
			window.send_to_editor_default = window.send_to_editor;
			$('#set-book-image').click(function(){
				// replace the default send_to_editor handler function with our own
				window.send_to_editor = window.attach_image;
				tb_show('', 'media-upload.php?post_id=<?php if(isset($post->ID)) echo $post->ID ?>&amp;type=image&amp;TB_iframe=true');
				return false;
			});
			
			$('#remove-book-image').click(function() {
				$('#upload_image_id').val('');
				$('img').attr('src', '');
				
				$('#upload_image_url').val('');
				
				$('#wpgmp_default_marker').val('');
				$(this).hide();
				return false;
			});
			
			window.attach_image = function(html) {
				// turn the returned image html into a hidden image element so we can easily pull the relevant attributes we need
				$('body').append('<div id="temp_image">' + html + '</div>');
					
				var img = $('#temp_image').find('img');
				imgurl   = img.attr('src');
				imgclass = img.attr('class');
				imgid    = parseInt(imgclass.replace(/\D/g, ''), 10);
				$('#remove-book-image').show();
				$('img#book_image').attr('src', imgurl);
				
				$('#upload_image_url').val(imgurl);
				
				$('#wpgmp_default_marker').val(imgurl);
				try{tb_remove();}catch(e){};
				$('#temp_image').remove();
				// restore the send_to_editor handler function
				window.send_to_editor = window.send_to_editor_default;
			}
		});	
</script>
<script type="text/javascript">
function mylayer(val)
{
if(val=='KmlLayer')
{
document.getElementById('kmldisplay').style.display = '';
document.getElementById('weatherlayer').style.display = 'none';
document.getElementById('fusiondisplay').style.display = 'none';		
}
else
{
document.getElementById('kmldisplay').style.display = 'none';			
}
if(val=='FusionTablesLayer')
{
document.getElementById('fusiondisplay').style.display = '';
document.getElementById('kmldisplay').style.display = 'none';
document.getElementById('weatherlayer').style.display = 'none';			
}
else
{
document.getElementById('fusiondisplay').style.display = 'none';	
}
if(val=='WeatherLayer')
{
	
document.getElementById('weatherlayer').style.display = '';
document.getElementById('fusiondisplay').style.display = 'none';
document.getElementById('kmldisplay').style.display = 'none';
}
else
{
document.getElementById('weatherlayer').style.display = 'none';	
}
}
</script>
<script type="text/javascript">
jQuery(document).ready(function ($) {
	
            $('.street_view_toggle').click(function () {                
                $('#disply_street_view').toggle();
            });
			
			$('.route_direction_toggle').click(function () {                
                $('#disply_route_direction').toggle();
            });
			
			$('.info_window_toggle').click(function () {                
                $('#disply_info_window').toggle();
            });
			
			$('.group_map_toggle').click(function () {                
                $('#disply_group_map').toggle();
            });
			
			$('.polygon_toggle').click(function () {                
                $('#disply_polygon').toggle();
            });
			
			$('.polyline_toggle').click(function () {                
                $('#disply_polyline').toggle();
            });
			
			$('.marker_cluster_toggle').click(function () {                
                $('#disply_marker_cluster').toggle();
            });
			
			$('.overlays_toggle').click(function () {                
                $('#disply_overlays').toggle();
            });
        });
		
</script>
<script type="text/javascript">
function send_icon_to_map(imagesrc){
 		
		jQuery('#upload_image_url').val(imagesrc);
		
		jQuery('#wpgmp_default_marker').val(imagesrc);
		
		jQuery('img#book_image').attr('src', imagesrc);
		
		jQuery('#remove-book-image').show();
		jQuery('#temp_image').remove();
 		
		tb_remove();
		
}
jQuery(document).ready(function ($) {
	
		$(".read_icons").click(function (){
		$(".read_icons").removeClass('active');
		
		$(this).addClass('active');
		
		});
	});
</script>
<?php
}
