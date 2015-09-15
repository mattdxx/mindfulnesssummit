<?php
/**
 * This function used to create a new map in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_create_map()
{
if( isset($_POST['create_map_location']) && $_POST['create_map_location']=="Save Map" )
{
	
if( $_POST['map_title']=="" )
{
   $error[]= __( 'Please enter title.', 'wpgmp_google_map' );
}
if( !intval($_POST['map_width']) && $_POST['map_width']!='' )
{
	$error[]= __( 'Please enter Integer value in map width.', 'wpgmp_google_map' );
}
if( $_POST['map_height']=='' )
{
	$error[]= __( 'Please enter map height.', 'wpgmp_google_map' );
}
else if( !intval($_POST['map_height']) )
{
	$error[]= __( 'Please enter Integer value in map height.', 'wpgmp_google_map' );
}
if( isset($_POST['direction_servics']['route_direction']) && !empty($_POST['direction_servics']['route_direction']))
{
	
	if( isset($_POST['locations']) && count($_POST['locations'])<2 )
	{
		$error[]= __( 'please add two locations for route directions.', 'wpgmp_google_map' );
	}
	else
	{
		$_POST['direction_servics']['route_direction'] = $_POST['direction_servics']['route_direction'];
	}
	
}
else
{
	$_POST['direction_servics']['route_direction'] = 'false';
	$_POST['direction_servics']['route_direction_stroke_color'] = "#0000FF";
	$_POST['direction_servics']['route_direction_stroke_opacity'] = 1.0;
	$_POST['direction_servics']['route_direction_stroke_weight'] = 2;
	
}
if( isset($_POST['scrolling_wheel']) && !empty($_POST['scrolling_wheel']) )
{
	$_POST['scrolling_wheel'] = $_POST['scrolling_wheel'];
}
else
{
	$_POST['scrolling_wheel'] = 'true';
}
if( isset($_POST['visual_refresh']) && !empty($_POST['visual_refresh']) )
{
    $_POST['visual_refresh'] = $_POST['visual_refresh'];
}
else
{
	$_POST['visual_refresh'] = 'false';
}
if( isset($_POST['street_view_control']['street_control']) && !empty($_POST['street_view_control']['street_control']) )
{
  	$_POST['street_view_control']['street_control'] = $_POST['street_view_control']['street_control'];
}
else
{
     $_POST['street_view_control']['street_control'] = 'false';

}
if( isset($_POST['street_view_control']['street_view_close_button']) && !empty($_POST['street_view_control']['street_view_close_button']) )
{
   	$_POST['street_view_control']['street_view_close_button'] = $_POST['street_view_control']['street_view_close_button'];

}
else
{
	 $_POST['street_view_control']['street_view_close_button'] = 'false';
}

if( isset($_POST['street_view_control']['links_control']) && !empty($_POST['street_view_control']['links_control']) )
{
  $_POST['street_view_control']['links_control'] = $_POST['street_view_control']['links_control'];
}
else
{
	 $_POST['street_view_control']['links_control'] = 'true';
}

if( isset($_POST['street_view_control']['street_view_pan_control']) && !empty($_POST['street_view_control']['street_view_pan_control']) )
{
   	$_POST['street_view_control']['street_view_pan_control'] = $_POST['street_view_control']['street_view_pan_control'];

}
else
{
	 $_POST['street_view_control']['street_view_pan_control'] = 'true';
}
if( isset($_POST['control']['pan_control']) && !empty($_POST['control']['pan_control']))
{
 	$_POST['control']['pan_control'] = $_POST['control']['pan_control'];
}
else
{
    $_POST['control']['pan_control'] = 'true';	 
}

if( isset($_POST['control']['zoom_control']) && !empty($_POST['control']['zoom_control']) )
{
  $_POST['control']['zoom_control'] = $_POST['control']['zoom_control'];
}
else
{
   $_POST['control']['zoom_control'] = 'true';	
}
if( isset($_POST['control']['map_type_control']) && !empty($_POST['control']['map_type_control']) )
{
   $_POST['control']['map_type_control'] = $_POST['control']['map_type_control'];
}
else
{
  $_POST['control']['map_type_control'] = 'true';	
}
if( isset($_POST['control']['scale_control']) && !empty($_POST['control']['scale_control']) )
{
   $_POST['control']['scale_control'] = $_POST['control']['scale_control'];
}
else
{
	$_POST['control']['scale_control'] = 'true';
}
if( isset($_POST['control']['street_view_control']) && !empty($_POST['control']['street_view_control']) )
{
   $_POST['control']['street_view_control'] = $_POST['control']['street_view_control'];
}
else
{
	$_POST['control']['street_view_control'] = 'true';
}
if( isset($_POST['control']['overview_map_control']) && !empty($_POST['control']['overview_map_control']) )
{
  $_POST['control']['overview_map_control'] = $_POST['control']['overview_map_control'];
}
else
{
	 $_POST['control']['overview_map_control'] = 'true';
}
if( isset( $_POST['info_window_setting']['info_window']) && !empty($_POST['info_window_setting']['info_window']) )
{
   $_POST['info_window_setting']['info_window'] = $_POST['info_window_setting']['info_window'];
}
else
{
	$_POST['info_window_setting']['info_window'] = 'true';
}
if( !isset($_POST['locations']) or $_POST['locations']=="" )
{
   $error[]= __( 'Please check any one location.', 'wpgmp_google_map' );
}
if( isset($_POST['group_map_setting']['enable_group_map']) && $_POST['group_map_setting']['enable_group_map']=='true' )
{
	if( $_POST['group_map_setting']['select_group_map']=="" )
	{
		$error[]= __( 'Please check at least one group map.', 'wpgmp_google_map' );
	}
}


if( empty($error) )
{
global $wpdb;
$map_table=$wpdb->prefix.'create_map';
$create_map_data = array(
	'map_title' => htmlspecialchars(stripslashes($_POST['map_title'])),
	'map_width' => $_POST['map_width'],
	'map_height' => $_POST['map_height'],
	'map_zoom_level' => $_POST['zoom_level'],
	'map_type' => $_POST['choose_map'],
	'map_scrolling_wheel' => $_POST['scrolling_wheel'],
	'map_visual_refresh' => $_POST['visual_refresh'],
	'map_street_view_setting' => serialize($_POST['street_view_control']),
	'map_all_control' => serialize($_POST['control']),
	'map_info_window_setting' => serialize($_POST['info_window_setting']),
	'style_google_map' => isset($_POST['style_array_type']) ? serialize($_POST['style_array_type']) : '',
	'map_locations' => isset($_POST['locations']) ? serialize($_POST['locations']) : '',
	'map_layer_setting' => serialize($_POST['layer_setting'])
	);
$wpdb->insert($map_table,$create_map_data);
$success= __( 'Maps created Successfully.', 'wpgmp_google_map' );
//$_POST = '';
}
}
?>
<div class="wpgmp-wrap">
<div class="col-md-11">  
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Create a Map', 'wpgmp_google_map')?></h3>
<div class="wpgmp-overview">
<form method="post">
<?php
if( !empty($error) )
{
	$error_msg=implode('<br>',$error);
	
	wpgmp_showMessage($error_msg,true);
}
if( !empty($success) )
{
    wpgmp_showMessage($success);
}
?>
<div> 
<div class="form-horizontal">
<fieldset>
    <legend><?php _e('General Settings', 'wpgmp_google_map')?></legend>
    <div class="row">
    <div class="col-md-2"><label for="title"><?php _e('Map Title', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label></div>
    <div class="col-md-9">
	<input type="text" name="map_title" value="<?php if(isset($_POST['map_title'])) echo $_POST['map_title']; ?>" class="create_map form-control" />
	<p class="description"><?php _e('Enter here the title', 'wpgmp_google_map')?></p>
    </div>
    </div>
    <div class="row">
    <div class="col-md-2">    <label for="title"><?php _e('Map Width', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<input type="text" name="map_width" value="<?php if(isset($_POST['map_width'])) echo $_POST['map_width']; ?>" class="create_map form-control" />
	<p class="description"><?php _e('Enter here the map width in pixel. Leave it blank for 100% width', 'wpgmp_google_map')?></p></div>
   </div>
   
   <div class="row">
    <div class="col-md-2">    <label for="title"><?php _e('Map Height', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label></div>
   <div class="col-md-7">
	<input type="text" name="map_height" value="<?php if(isset($_POST['map_height'])) echo $_POST['map_height']; ?>" class="create_map form-control" />
	<p class="description"><?php _e('Enter here the map height in pixel.', 'wpgmp_google_map')?></p></div>
    </div>
    
    <div class='row'>
    <div class="col-md-2">
		<label for="title"><?php _e('Map Zoom Level', 'wpgmp_google_map')?></label></div>
       <div class="col-md-7">
	<select name="zoom_level">
        <?php for($i=1;$i<20;$i++)
        {
			?>
        <option value="<?php echo $i; ?>"<?php selected( ( isset($map_record->map_zoom_level) ? $map_record->map_zoom_level : false),$i) ?>><?php echo $i; ?></option>
		<?php } ?>
    </select>
	<p class="description"><?php _e('(Available options - 1,2,3,4,5,6,8,9,10,11,12,13,14,15,16,17,18,19).', 'wpgmp_google_map')?></p></div>
   
   </div>
   
   <div class='row'>
    <div class="col-md-2">    <label for="title"><?php _e('Choose Map Type', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
    <select name="choose_map">
        <option value="ROADMAP"<?php selected((isset($_POST['choose_map']) ? $_POST['choose_map'] : false),'ROADMAP') ?>><?php _e('ROADMAP', 'wpgmp_google_map')?></option>
        <option value="SATELLITE"<?php selected( (isset($_POST['choose_map']) ? $_POST['choose_map'] : false),'SATELLITE') ?>><?php _e('SATELLITE', 'wpgmp_google_map')?></option>
        <option value="HYBRID"<?php selected((isset($_POST['choose_map']) ? $_POST['choose_map'] : false),'HYBRID') ?>><?php _e('HYBRID', 'wpgmp_google_map')?></option>
        <option value="TERRAIN"<?php selected((isset($_POST['choose_map']) ? $_POST['choose_map'] : false),'TERRAIN') ?>><?php _e('TERRAIN', 'wpgmp_google_map')?></option>
    </select>
	<p class="description"><?php _e('Available options - ROADMAP,SATELLITE,HYBRID,TERRAIN. Default is roadmap type.', 'wpgmp_google_map')?></p></div>
    
    </div>
    
    <div class="row">
    <div class="col-md-2">    <label for="title"><?php _e('Turn Off Scrolling Wheel', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<input type="checkbox" name="scrolling_wheel" value="false"<?php checked( (isset($_POST['scrolling_wheel']) ? $_POST['scrolling_wheel'] : false),'false') ?>/>
	<p class="description"><?php _e('Please check to disable scroll wheel zooms.', 'wpgmp_google_map')?></p></div>
  
  </div>
  
    <div class="row">
    <div class="col-md-2">    <label for="title"><?php _e('Enable Visual Refresh', 'wpgmp_google_map')?></label></div>
    <div class="col-md-7">

	<input type="checkbox" name="visual_refresh" value="true"<?php checked((isset($_POST['visual_refresh']) ? $_POST['visual_refresh'] : false),'true') ?>/>
	<p class="description"><?php _e('Please check to enable visual refresh.', 'wpgmp_google_map')?></p></div>
	
	</div>
	
	<div class="row">
    <div class="col-md-2">    <label for="title"><?php _e('45&deg; Imagery', 'wpgmp_google_map')?></label></div>
    <div class="col-md-7">
	
	<input type="checkbox" name="45imagery" value="45"<?php checked( (isset($_POST['45imagery']) ? $_POST['45imagery'] : false),'45') ?> />
	<p class="description"><?php _e('Apply 45&deg; Imagery ? (only available for map type SATELLITE and HYBRID).', 'wpgmp_google_map')?></p>  </div> 
	</div>
</fieldset>
   
<fieldset>
    <legend><?php _e('Choose Locations', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></legend>
	
    <ul>
		<?php
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."map_locations");
		
		if( !empty($results) )
		{
        for($i = 0; $i < count($results); $i++)
		{
        ?>
            <li> <div class="checkbox">
    <label>
            <?php
            if( !empty($_POST['locations']) )
            { 
            if( in_array($results[$i]->location_id, $_POST['locations']) )
            {
            ?>
           
      <input type="checkbox" name="locations[]" checked="checked" value="<?php echo $results[$i]->location_id; ?>"/> 
    
            <?php 
            if($results[$i]->location_address!='')
            echo $results[$i]->location_address;
            elseif($results[$i]->location_title!='')
            {
			echo $results[$i]->location_title;
			
            }
            
             ?>
            <?php 
            }
            else
            {
            ?>
            <input type="checkbox" name="locations[]" value="<?php echo $results[$i]->location_id; ?>"/>&nbsp;&nbsp;<?php 
            if($results[$i]->location_address!='')
            echo $results[$i]->location_address;
            elseif($results[$i]->location_title!='')
            {
			echo $results[$i]->location_title;
			
            }
            
             ?>
            <?php			
            }
            }
            else
            {
            ?>
            <input type="checkbox" name="locations[]" value="<?php echo $results[$i]->location_id; ?>"/>&nbsp;&nbsp;<?php 
            if($results[$i]->location_address!='')
            echo $results[$i]->location_address;
            elseif($results[$i]->location_title!='')
            {
			echo $results[$i]->location_title;
			
            }
            
             ?>
            <?php 
            }
            ?>
            </label>
  </div>
            </li>
        <?php
         }
		 }
		 else
		 {
        ?>
        <?php _e('Seems you don\'t have any location right now.', 'wpgmp_google_map')?>&nbsp;<a href="<?php echo admin_url('admin.php?page=wpgmp_add_location') ?>"><?php _e('Click here', 'wpgmp_google_map')?></a>&nbsp;<?php _e('to add a location now', 'wpgmp_google_map')?> 
        <?php
		 }
		 ?>
   </ul>
   
</fieldset>

<fieldset>
    <legend><?php _e('Layers', 'wpgmp_google_map')?></legend>
   
   <div class="row"> 
   <div class="col-md-2">    <label for="title"><?php _e('Select Layers', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<select name="layer_setting[choose_layer]" class="form-control" onchange="mylayer(this.value)">
        <option value=""><?php _e('Select Layers', 'wpgmp_google_map')?></option>
        <option value="TrafficLayer"<?php selected((isset($_POST['layer_setting']['choose_layer']) ? $_POST['layer_setting']['choose_layer'] : false),'TrafficLayer') ?>><?php _e('Traffic Layers', 'wpgmp_google_map')?></option>
        <option value="TransitLayer"<?php selected((isset($_POST['layer_setting']['choose_layer']) ? $_POST['layer_setting']['choose_layer'] : false),'TransitLayer') ?>><?php _e('Transit Layers', 'wpgmp_google_map')?></option>
        <option value="BicyclingLayer"<?php selected((isset($_POST['layer_setting']['choose_layer']) ? $_POST['layer_setting']['choose_layer'] : false),'BicyclingLayer') ?>><?php _e('Bicycling Layers', 'wpgmp_google_map')?></option>
        <option value="PanoramioLayer"<?php selected((isset($_POST['layer_setting']['choose_layer']) ? $_POST['layer_setting']['choose_layer'] : false),'PanoramioLayer') ?>><?php _e('Panoramio Layers', 'wpgmp_google_map')?></option>
	</select>
	<p class="description"><?php _e('Available options - Traffic Layers,Transit Layers,Bicycling Layers,Panoramio Layers.', 'wpgmp_google_map')?></p></div>
		
</fieldset>
<fieldset>
    <legend><?php _e('Control Setting', 'wpgmp_google_map')?></legend>
   
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Pan Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[pan_control]" value="false"<?php checked( (isset($_POST['control']['pan_control']) ? $_POST['control']['pan_control'] : false),'false') ?>/>
	<?php _e('Please check to disable pan control.', 'wpgmp_google_map')?></p></div>
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Zoom Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[zoom_control]" value="false"<?php checked( (isset($_POST['control']['zoom_control']) ? $_POST['control']['zoom_control'] : false),'false') ?>/>
	<?php _e('Please check to disable zoom control.', 'wpgmp_google_map')?></p></div>
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Map Type Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
<p class="description">	<input type="checkbox" name="control[map_type_control]" value="false"<?php checked( (isset( $_POST['control']['map_type_control']) ?  $_POST['control']['map_type_control'] : false),'false') ?>/>
	<?php _e('Please check to disable map type control.', 'wpgmp_google_map')?></p></div>
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Scale Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[scale_control]" value="false"<?php checked( (isset($_POST['control']['scale_control']) ? $_POST['control']['scale_control'] : false),'false') ?>/>
	<?php _e('Please check to disable scale control.', 'wpgmp_google_map')?></p></div>
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Street View Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[street_view_control]" value="false"<?php checked( (isset($_POST['control']['street_view_control']) ? $_POST['control']['street_view_control'] : false),'false') ?>/>
	<?php _e('Please check to disable street view control.', 'wpgmp_google_map')?></p></div>
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Overview Map Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[overview_map_control]" value="false"<?php checked( (isset($_POST['control']['overview_map_control']) ? $_POST['control']['overview_map_control'] : false),'false') ?>/>
	<?php _e('Please check to disable overview map control.', 'wpgmp_google_map')?></p>
    </div>
</fieldset>

<fieldset>
    <legend><?php _e('Street View Setting', 'wpgmp_google_map')?></legend>
     <div class="col-md-4 left">    <label for="title"><?php _e('Turn On Street View', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_control]" class="street_view_toggle" value="true"<?php checked( (isset($_POST['street_view_control']['street_control']) ? $_POST['street_view_control']['street_control'] : false),'true') ?>/>
	<?php _e('Please check to enable Street View control.', 'wpgmp_google_map')?></p></div>
    
   <div id="disply_street_view" style="display:none;">
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn On Close Button', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_view_close_button]" value="true"<?php checked( (isset($_POST['street_view_control']['street_view_close_button']) ? $_POST['street_view_control']['street_view_close_button'] : false),'true') ?>/>
	<?php _e('Please check to enable Close button.', 'wpgmp_google_map')?></p></div>
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off links Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[links_control]" value="false"<?php checked( (isset($_POST['street_view_control']['links_control']) ? $_POST['street_view_control']['links_control'] : false),'false') ?>/>
	<?php _e('Please check to disable links control.', 'wpgmp_google_map')?></p></div>
    <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Street View Pan Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_view_pan_control]" value="false"<?php checked( (isset($_POST['street_view_control']['street_view_pan_control']) ? $_POST['street_view_control']['street_view_pan_control'] : false),'false') ?>/>
	<?php _e('Please check to disable Street View Pan control.', 'wpgmp_google_map')?></p>
    </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php _e('Map Style Settings', 'wpgmp_google_map')?></legend>
	 	
        <div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>

</fieldset>
    
<fieldset>
    <legend><?php _e('Polygon Settings', 'wpgmp_google_map')?></legend>  
 	<div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>
</fieldset>
<fieldset>
    <legend><?php _e('Polyline Settings', 'wpgmp_google_map')?></legend>  
 	<div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>

</fieldset>
<fieldset>
    <legend><?php _e('Marker Cluster Settings', 'wpgmp_google_map')?></legend>
	 	<div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>

</fieldset>
<fieldset>
    <legend><?php _e('Overlay Settings', 'wpgmp_google_map')?></legend>
	 <div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>

</fieldset>
<fieldset>
            <legend>
            <?php _e('Limit Panning and Zoom', 'wpgmp_google_map')?>
            </legend>
             <div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>
</fieldset>   

  <fieldset>
            <legend>
            <?php _e('Category/Directions/Nearby Module', 'wpgmp_google_map')?>
            </legend>
                <div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>
</fieldset>   

 <fieldset><legend>Listing Module</legend>
 <div class="col-md-7"><p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p></div>
</fieldset>

	<div class="col-md-4 left">  </div><div class="col-md-7">
	<input type="submit" name="create_map_location" id="submit" class="btn btn-primary" value="<?php _e('Save Map', 'wpgmp_google_map')?>" >
	</div> 
</div></div>
</form>
</div></div></div>
<?php	
}
