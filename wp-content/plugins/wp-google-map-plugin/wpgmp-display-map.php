<?php
function wpgmp_display_map($atts, $content=null){
	
 ob_start();
 global $wpdb;
 	
 include_once dirname(__FILE__).'/class-google-map.php';
 $map = new Wpgmp_Google_Map();

 $marker_array = array();
 $address_array = array();
 foreach ($atts as $key => $value) {
    if ( strpos($key, 'marker') === 0 )
	{
        $marker_array[$key] = $value;
		$first_marker = current($marker_array);
 		$explode_marker = explode('|',$first_marker);
		$map->center_lat = $explode_marker[0];
    	$map->center_lng = $explode_marker[1];
    }
	if( strpos($key, 'address') === 0 )
	{
		$address_array[$key] = $value;
		$first_address = current($address_array);
		$rm_space_ads = str_replace(' ','+',$first_address);
		$geocode=wp_remote_get('http://maps.google.com/maps/api/geocode/json?address='.$rm_space_ads.'&sensor=false');
		$output= json_decode($geocode['body']);
		$map->center_lat = $output->results[0]->geometry->location->lat;
		$map->center_lng = $output->results[0]->geometry->location->lng;
	}
 }
 
 $map->map_width = $atts['width'];
 $map->map_height = $atts['height'];
 $map->zoom = $atts['zoom'];
 
 if(is_array($marker_array)) {
	 foreach($marker_array as $marker){
		 $explode_marker = explode('|',$marker);
		 
		 if($explode_marker[6]!='')
		 {
			$icon = $wpdb->get_row($wpdb->prepare('SELECT group_marker FROM '.$wpdb->prefix.'group_map WHERE group_map_id=%d',$explode_marker[6]));
			
			$icon = $icon->group_marker;
		 }
		 else
		 {
			 $icon = '';
		 }
		 
		 if(empty($explode_marker[4]))
		 {
			 $clickable = 'false';
		 }
		 else
		 {
			 $clickable = $explode_marker[4];
		 }
		 
		 if(empty($explode_marker[5]))
		 {
			 $draggable = 'false';
		 }
		 else
		 {
			 $draggable = $explode_marker[5];
		 }
		 
		$map->addMarker($explode_marker[0],$explode_marker[1],$clickable,$explode_marker[2],$explode_marker[3],$icon,'',$draggable,'',$group_id='');
	 }
 }

 if(is_array($address_array)) {
	  foreach($address_array as $address) {
		$explode_address = explode('|',$address); 
		  
		$rm_space_ads = str_replace(' ','+',$explode_address[0]);
		$geocode=wp_remote_get('http://maps.google.com/maps/api/geocode/json?address='.$rm_space_ads.'&sensor=false');
		$output= json_decode($geocode['body']);
		$lat = $output->results[0]->geometry->location->lat;
		$lng = $output->results[0]->geometry->location->lng; 
		   
		if($explode_address[5]!='')
		{
			$icon_image = $wpdb->get_row($wpdb->prepare('SELECT group_marker FROM '.$wpdb->prefix.'group_map WHERE group_map_id=%d',$explode_address[5]));
			
			$icon = $icon_image->group_marker;
		}
		else
		{
			 $icon = '';
		}
		 
		if(empty($explode_address[3]))
		{
			 $clickable = 'false';
		}
		else
		{
			 $clickable = $explode_address[3];
		}
		 
		if(empty($explode_address[4]))
		{
			 $draggable = 'false';
		}
		else
		{
			 $draggable = $explode_address[4];
		}   
		
		$map->addMarker($lat,$lng,$clickable,$explode_address[1],$explode_address[2],$icon,'',$draggable,'',$group_id='');
	 }
 }
 
 echo $map->showmap();
 $content =  ob_get_contents();
 ob_clean();
 return $content;
}