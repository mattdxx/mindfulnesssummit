<?php
/**
 * This function used to create a group new map in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_create_group_map()
{
if(isset( $_POST['create_group_map_location']) && $_POST['create_group_map_location']=="Save Group Marker" )
{
	
if( $_POST['group_map_title']=="" )
{
   $error[]= __( 'Please enter group title.', 'wpgmp_google_map' );
}
if( $_POST['upload_image_url']=="" )
{
   $error[]= __( 'Please upload marker image.', 'wpgmp_google_map' );
}
if( empty($error) )
{
global $wpdb;

$group_map_table=$wpdb->prefix.'group_map';

$create_group_map_data = array(
						'group_map_title' => htmlspecialchars(stripslashes($_POST['group_map_title'])),
						'group_marker' => htmlspecialchars(stripslashes($_POST['upload_image_url']))
						);
	
$wpdb->insert($group_map_table,$create_group_map_data);

$success= __( 'Group Created Successfully.', 'wpgmp_google_map' );

$_POST = array();
}
}
?>
<div class="wpgmp-wrap">  
<div class="col-md-11">  
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Create Marker Groups', 'wpgmp_google_map')?></h3>
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
<fieldset>
    <legend><?php _e('General Settings', 'wpgmp_google_map')?></legend>
   <div class="form-horizontal">
    <div class="col-md-4 left"> <label for="title"><?php _e('Group Title', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label></div>
   <div class="col-md-7">
	<input type="text"   name="group_map_title" value="<?php if(isset($_POST["group_map_title"])) echo $_POST["group_map_title"]; ?>" class="create_map form-control" />
	<p class="description"><?php _e('Enter here the group title.', 'wpgmp_google_map')?></p></div>
    
   <div class="col-md-4 left">    <label for="title"><?php _e('Choose Marker Image', 'wpgmp_google_map')?><span style="color:#F00;">*</span></label></div>
   <div class="col-md-7">
    <img id="book_image" src="<?php if(isset($image_src)) echo $image_src ?>" style="float:left;" />
 
	<input type="hidden" name="upload_image_url" id="upload_image_url" value="<?php if(isset($image_src)) echo $image_src ?>" />
           
     <div style="margin-left:5px;">     
            	   <a title="<?php esc_attr_e( 'Upload Marker Image', 'wpgmp_google_map' ) ?>" href="#" id="set-book-image"><?php _e( 'Upload Marker Image', 'wpgmp_google_map' ) ?></a><br />
            	<a title="<?php esc_attr_e( 'Remove Marker Image', 'wpgmp_google_map' ) ?>" href="#" id="remove-book-image" style="<?php if(isset($image_src) && $image_src) echo "display:block;"; else  "display:none;";  ?>"><?php _e( 'Remove Marker Image', 'wpgmp_google_map' ) ?></a><br />
   </div><br />
   <p class="description"><?php _e('Upload marker image.', 'wpgmp_google_map')?></p> </div>
</fieldset>
 <div class="col-md-4 left">  </div><div class="col-md-7"> <input type="submit" name="create_group_map_location" id="submit" class="btn btn-lg btn-primary" value="<?php _e('Save Group Marker', 'wpgmp_google_map')?>" ></div>
</div>
</form>
</div></div>
<?php	
}
