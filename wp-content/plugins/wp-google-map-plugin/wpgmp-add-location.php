<?php
/**
 * This function used to add locations in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_add_locations()
{
  if( isset($_POST['googlemap_location']) && $_POST['googlemap_location']=="Save Location" )
  {
		if( $_POST['googlemap_title']=="" )
		{
		   $error[]= __( 'Please enter title.', 'wpgmp_google_map' );
		}
		if( $_POST['googlemap_address']=="" )
		{
		   $error[]= __( 'Please enter address.', 'wpgmp_google_map' );
		}
		if( $_POST['googlemap_latitude']=="" )
		{
		   $error[]= __( 'Please enter latitude.', 'wpgmp_google_map' );
		}
		if( $_POST['googlemap_longitude']=="" )
		{
		   $error[]= __( 'Please enter longitude.', 'wpgmp_google_map' );
		}

    if( isset($_POST['googlemap_draggable']) && !empty($_POST['googlemap_draggable']) )
    {
        $_POST['googlemap_draggable'] = $_POST['googlemap_draggable'];
    }
    else
    {
      $_POST['googlemap_draggable'] = 'false';
    }
		
		$messages = base64_encode(serialize($_POST['infowindow_message']));
		
		if( empty($error) )
		{
			global $wpdb,$post;
	
			
	
			$lat = $_POST['googlemap_latitude'];
	
			$long = $_POST['googlemap_longitude'];	
	
		
		$location_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations WHERE location_address = %s",$_POST['googlemap_address']));
	
		
	
			if( empty($location_record->location_address) )
	
			{
	
			$location_table=$wpdb->prefix."map_locations";
	
			$in_loc_data = array(
	
			'location_title' => htmlspecialchars(stripslashes($_POST['googlemap_title'])),
	
			'location_address' => htmlspecialchars(stripslashes($_POST['googlemap_address'])),
	
			'location_draggable' => $_POST['googlemap_draggable'],
	
			'location_latitude' => $lat,
	
			'location_longitude'=> $long,
			
			'location_messages'=> $messages,
			
			'location_marker_image' => isset($_POST['upload_image_url']) ? htmlspecialchars(stripslashes($_POST['upload_image_url'])) : '',
			
			'location_group_map' => isset($_POST['location_group_map']) ? $_POST['location_group_map'] : ''
				
			);
	
			$wpdb->insert($location_table,$in_loc_data);
	
			$success = __( 'Locations Added Successfully.', 'wpgmp_google_map' );
	
			$_POST = array();
	
			}
	
			else
	
			{
	
			$error[] = __( 'Address already exists.', 'wpgmp_google_map' );
	
			}
	
		}
	} 
?>
<div class="wpgmp-wrap"> 
<div class="col-md-11">  
 
 <div id="icon-options-general" class="icon32"><br/></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Add Location', 'wpgmp_google_map')?></h3> 
<div class="wpgmp-overview">
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
 <form method="post">
        <div class="form-horizontal">
          <div class="row">
          <div class="col-md-2">
            <label for="title">
              <?php _e('Location Title', 'wpgmp_google_map')?>
              &nbsp;<span style="color:#F00;">*</span></label>
          </div>
          <div class="col-md-9">
            <input type="text" class="form-control" name="googlemap_title" placeholder="Location Title"   value="<?php if(isset($_POST['googlemap_title'])) echo $_POST['googlemap_title']; ?>" />
            <p class="description">
              <?php _e('Enter here the location title', 'wpgmp_google_map')?>
            </p>
          </div>
          </div>
          <div class="row">
          <div class="col-md-2">
            <label for="title">
              <?php _e('Address', 'wpgmp_google_map')?>
              &nbsp;</label>
          </div>
          <div class="col-md-9">
            <div class="row">
              <div class="col-md-10">
                <input type="text" class="form-control" name="googlemap_address" id="googlemap_address"   value="<?php if(isset($_POST['googlemap_address']))  echo $_POST['googlemap_address']; ?>" />
              </div>
              <div class="col-md-2">
                <input type="button" value="<?php _e('Geocode', 'wpgmp_google_map')?>" onclick="geocodeaddress()" class="btn btn-sm btn-primary">
              </div>
            </div>
            <p class="description">
              <?php _e('Enter here the address. Google auto suggest helps you to choose one.', 'wpgmp_google_map')?>
            </p>
            <div class="row">
              <div class="col-md-6">
                <input type="text"  name="googlemap_latitude" id="googlemap_latitude" class="google_latitude form-control" placeholder="<?php _e('Latitude', 'wpgmp_google_map')?>"  value="<?php if(isset($_POST['googlemap_latitude']) ) echo $_POST['googlemap_latitude']; ?>" />
                <p class="description">
                  <?php _e('Enter here the latitude.', 'wpgmp_google_map')?>
                </p>
              </div>
              <div class="col-md-6">
                <input type="text" name="googlemap_longitude" id="googlemap_longitude" class="google_longitude form-control" placeholder="<?php _e('Longitude', 'wpgmp_google_map')?>"   value="<?php if(isset($_POST['googlemap_longitude']) ) echo $_POST['googlemap_longitude']; ?>" />
                <p class="description">
                  <?php _e('Enter here the longitude.', 'wpgmp_google_map')?>
                </p>
              </div>
            </div>
            <div id="map" style="width:100%; height: 300px;margin: 0.6em;"></div>
          </div>
         
         </div>
         <div class="row">
          
          <div class="col-md-2">
            <label for="title">
              <?php _e('Message', 'wpgmp_google_map')?>
            </label>
          </div>
          <div class="col-md-9">
            <textarea class="form-control" rows="3" cols="70" name="infowindow_message[googlemap_infowindow_message_one]" id="googlemap_infomessage" size="45" /><?php if(isset($_POST['googlemap_infomessage']) ) echo $_POST['googlemap_infomessage']; ?></textarea>
            <p class="description">
              <?php _e('Enter here the infoWindow message.', 'wpgmp_google_map')?>
            </p>
          </div>
          </div>
          <div class="row">
          <div class="col-md-2">
            <label for="title">
              <?php _e('Draggable', 'wpgmp_google_map')?>
            </label>
          </div>
          <div class="col-md-7">
            <p class="description">
              <input type="checkbox" name="googlemap_draggable" value="true"<?php checked((isset($_POST['googlemap_draggable']) ? $_POST['googlemap_draggable'] : false),true) ?>/>
              <?php _e('Do you want to allow visitors to drag the marker?.', 'wpgmp_google_map')?>
            </p>
          </div>
          </div>
          <div class="row">
          <div class="col-md-2">
            <label for="title">
              <?php _e('Choose Marker Image', 'wpgmp_google_map')?>
            </label>
          </div>
          <div class="col-md-7">
            <div>
              <?php
    
    global $wpdb;
    
    $group_results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."group_map");

    if(  !empty($group_results) )
    {
    ?>
              <select name="location_group_map">
                <option value="">Select group</option>
                <?php
        foreach($group_results as $group_result)
        {
            if( !empty($_POST['location_group_map']))
            {  
              ?>
                <option value="<?php echo $group_result->group_map_id; ?>"<?php selected($_POST['location_group_map'],$group_result->group_map_id); ?>><?php echo $group_result->group_map_title; ?></option>
              <?php
            }
            else
            {
                ?>
                <option value="<?php echo $group_result->group_map_id; ?>"><?php echo $group_result->group_map_title; ?></option>
              <?php
            }
        
        }
    ?>
              </select>
              <?php
    }
    
    else
    {
    
    ?>
              <?php _e('You don\'t have any marker group yet.', 'wpgmp_google_map')?>
              &nbsp;<a href="<?php echo admin_url('admin.php?page=wpgmp_google_wpgmp_create_group_map') ?>">
              <?php _e('Click here', 'wpgmp_google_map')?>
              </a>&nbsp;
              <?php _e('to add a group marker now', 'wpgmp_google_map')?>
              <?php
    
     }
    
     ?>
            </div>
            <p class="description">
              <?php _e('Assign a marker group to this location.', 'wpgmp_google_map')?>
            </p>
          </div>
          
          </div>
          
          <div class="row">
          <div class="col-md-7 col-md-offset-2">
            <input type="submit" name="googlemap_location" id="submit" class="btn btn-primary" value="<?php _e('Save Location', 'wpgmp_google_map')?>"/>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
}
