<?php
class Wpgmp_Google_Map
{

var $code='';  // Do not edit this.

var $zoom=10; // Zoop Level.

var $center_lat = '37.09024'; // google map center location

var $center_lng = '-95.712891'; // google map center location

var $center_address = '';

var $divID='map'; // The div id where you want to 	place your google map

var $marker=array(); // Array to store markers information. 

var $instance=1;

var $width="";

var $height="";

var $title = 'WP Google Map Plugin';

var $map_width = "";

var $map_height = "";

var $map_scrolling_wheel="true";

var $map_pan_control="true";

var $map_zoom_control="true";

var $map_type_control="true"; 

var $map_scale_control="true";

var $map_street_view_control="true";

var $map_overview_control="true";

var $map_language="en";

var $map_type="ROADMAP";

var $map_45="";

var $map_layers="";

var $visualrefresh = "false";

var $street_control = "";

var $street_view_close_button = "";

var $links_control = "";

var $street_view_pan_control = "";

var $enable_group_map = "";

var $group_data = "";

var $groups_markers = array();

var $infowindow = "infowindow";
	
function __construct()
{
	global $wpgmp_containers;	
}

// Intialized google map scripts.

private function start()
{

if( $this->center_address )
{ 
	$output = $this->getData($this->center_address);	

if( $output->status == 'OK' )
{
	$this->center_lat = $output->results[0]->geometry->location->lat;
	$this->center_lng = $output->results[0]->geometry->location->lng;
}

}

if( $this->map_width!='' && $this->map_height!='' )
{
  
	  $width = $this->map_width."px";
	  $height = $this->map_height."px";
   
}
elseif( $this->map_width=='' && $this->map_height!='' )
{
  
	  $width = "100%";
	  $height = $this->map_height."px";
   
}
elseif( $this->map_width=='' && $this->map_height=='' )
{
  
	  $width = "100%";
	  $height = "300px";
   
}
else
{
	  $width =  $this->map_width."px";
	  $height = "300px";
	  
}

$this->divID="wgmpmap";
$this->code='
<style>
#'.$this->divID.' img {
max-width: none;
}
</style>'.'
<div id='.$this->divID.' style="width:'.$width.'; height:'.$height.';"></div>';

$this->code.='

<script type="text/javascript">

var infoWindows = [];

';

$this->map_language = get_option('wpgmp_language');

if($this->map_language=='')
$this->map_language = 'en';


$this->code.='google.load("maps", "3.7", {"other_params" : "sensor=false&libraries=places,weather,panoramio&language='.$this->map_language.'"});

google.setOnLoadCallback(initialize);';	
	

$this->code.='function initialize() {';
	
			  
$this->code.='var latlng = new google.maps.LatLng('.$this->center_lat.','.$this->center_lng.');';

if( $this->street_control!='true' )
{		

	$this->code.='var mapOptions = {';
		
if( empty($this->map_45) )
{

	$this->code.='zoom: '.$this->zoom.',';

}
else
{

	$this->code.='zoom: 18,';	

}
		
$this->code.='scrollwheel: '.$this->map_scrolling_wheel.',
		
		panControl: '.$this->map_pan_control.',
		
		zoomControl: '.$this->map_zoom_control.',
		
		mapTypeControl: '.$this->map_type_control.',
		
		scaleControl: '.$this->map_scale_control.',
		
		streetViewControl: '.$this->map_street_view_control.',
		
		overviewMapControl: '.$this->map_overview_control.',
		
		overviewMapControlOptions: {

	            opened: '.$this->map_overview_control.'
	    },

		center: latlng,

		mapTypeId: google.maps.MapTypeId.'.$this->map_type.'

		};

		'.$this->divID.' = new google.maps.Map(document.getElementById("'.$this->divID.'"), mapOptions);';
}
else
{		
		$this->code.='var panoOptions = {
    			position: latlng,
    			addressControlOptions: {
      			position: google.maps.ControlPosition.BOTTOM_CENTER
    		},
    			linksControl: '.$this->links_control.',
    			panControl: '.$this->street_view_pan_control.',
    			zoomControlOptions: {
      			style: google.maps.ZoomControlStyle.SMALL
    		},
    			enableCloseButton: '.$this->street_view_close_button.'
  		};

  		var panorama = new google.maps.StreetViewPanorama(document.getElementById("'.$this->divID.'"), panoOptions);
		';
}
		 		
if( !empty($this->map_45) )
{

	$this->code.=''.$this->divID.'.setTilt('.$this->map_45.');';

}


if( $this->map_layers=="TrafficLayer" )
{
	$this->code.='
	
	var trafficLayer = new google.maps.'.$this->map_layers.'();
	
	trafficLayer.setMap('.$this->divID.');';
}

if( $this->map_layers=="TransitLayer" )
{
	$this->code.='

	var transitLayer = new google.maps.'.$this->map_layers.'();

	transitLayer.setMap('.$this->divID.');';
}


if( $this->map_layers=="BicyclingLayer" )
{
	$this->code.='

	var bikeLayer = new google.maps.'.$this->map_layers.'();

	bikeLayer.setMap('.$this->divID.');';

}

		
for($i=0; $i < count($this->marker); $i++)
{
  if( empty($this->marker[$i]['draggable']) )
	 $this->marker[$i]['draggable']='false';

	 $this->code.='marker'.$i.$this->divID.'=new google.maps.Marker({
		map: '.$this->divID.',
		draggable:'.$this->marker[$i]['draggable'].',';
		$this->code.='position: new google.maps.LatLng('.$this->marker[$i]['lat'].', '.$this->marker[$i]['lng'].'), 
		title: "'.$this->marker[$i]['title'].'",
		clickable: '.$this->marker[$i]['click'].',
		icon: "'.$this->marker[$i]['icon'].'"
	  });';
  
 if( $this->enable_group_map=='true' )
 {
	  if($this->marker[$i]['group_id'])
	  {
	   $group_id = $this->marker[$i]['group_id'];
	  
	  $this->code .= "\n".'if(typeof groups.group'.$group_id.' == "undefined")
					  groups.group'.$group_id.' = [];
	  ';	  
		  
	   $this->code .= "\n".'groups.group'.$group_id.'.push(marker'.$i.$this->divID.');';	  
	 }
 }
 
// Creating an InfoWindow object

if( $this->marker[$i]['info']!='' )
{
	$infos = $this->marker[$i]['info'];
	
	if( is_array($infos) )
	{
		$message = nl2br($infos['first']['message']);
		$infos = str_replace(array("\r\n"),'"+"',$message);
		$infos_mess_one = do_shortcode($infos);
		
				$this->code.='
				'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
					content: "'.$infos_mess_one.'"
				});';

	}
	elseif( $infos!='' )
	{
		$infos = str_replace(array("\r","\n"),'"+"',$infos);
			
		$this->code.='
		'.$this->infowindow.''.$i.$this->divID.' =  new google.maps.InfoWindow({
			content: "'.$infos.'"
		});
		';
	}
	


	$this->code.="google.maps.event.addListener(marker".$i.$this->divID.", 'click', function() { ";
	
	$this->code.="wgmp_closeAllInfoWindows();";

	$this->code.=" infoWindows.push(".$this->infowindow.''.$i.$this->divID.");"; 											

	$this->code.="
				".$this->infowindow."".$i.$this->divID.".open(".$this->divID.",marker".$i.$this->divID.");
			google.maps.event.addListener(".$this->divID.", 'click', function() {
			".$this->infowindow."".$i.$this->divID.".close();
		});";
		

	$this->code.="});"; 
}

}

	
$this->code.='}


function wgmp_closeAllInfoWindows() {
  for (var i=0;i<infoWindows.length;i++) {
     infoWindows[i].close();
  }
  infoWindows = [];
}

';

$this->code.='</script>';

/* remove tabs, spaces, newlines, etc. */
$this->code = str_replace(array("\r\n","\r","\t","\n",'  ','    ','     '), '', $this->code);
/* remove other spaces before/after ) */
$this->code = preg_replace(array('(( )+\))','(\)( )+)'), ')', $this->code);
/* remove some more spaces. */
$this->code = str_replace(array('), ','", ',' = ',': {',", '",': "',', function',': true',': false'), array('),','",','=',':{',",'",':"',',function',':true',':false'), $this->code);


}

public function addMarker($lat,$lng,$click='false',$title='My WorkPlace',$info='Hello World',$icon='',$map='map',$draggable='')
{
	$count=count($this->marker);	
	
	$this->marker[$count]['lat']=$lat;
	
	$this->marker[$count]['lng']=$lng;
	
	$this->marker[$count]['click']=$click;
	
	$this->marker[$count]['title']=$title;
	
	$this->marker[$count]['info']=$info;
	
	$this->marker[$count]['icon']=$icon;
	
	$this->marker[$count]['map']=$map;
	
	$this->marker[$count]['draggable']=$draggable;
}


public function showmap()
{
	$this->start();

	$this->instance++;

	return $this->code;
}

}
