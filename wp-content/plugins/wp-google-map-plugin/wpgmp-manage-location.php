<?php
/**
 * This class used to manage locations in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
class Wpgmp_Location_Table extends WP_List_Table {
    var $table_data;
    function __construct(){
    global $status, $page,$wpdb;
        parent::__construct( array(
            'singular'  => __( 'googlemap', 'wpgmp_google_map' ),    
            'plural'    => __( 'googlemaps', 'wpgmp_google_map' ),  
            'ajax'      => false       
    ) );
		if($_GET['page']=='wpgmp_manage_location' && isset($_POST['s']) && $_POST['s']!='')
		{
		$query = "SELECT * FROM ".$wpdb->prefix."map_locations WHERE location_title LIKE '%".$_POST['s']."%'";
		}
		else
		{
		$query = "SELECT * FROM ".$wpdb->prefix."map_locations ORDER BY location_id DESC";
		}
		
	 	$this->table_data = $wpdb->get_results($query,ARRAY_A );
    add_action( 'admin_head', array( &$this, 'admin_header' ) );            
    }
	
	function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'location' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-location_title  { width: 20%; }';
	 echo '.wp-list-table .column-location_address  { width: 20%;}';
	 echo '.wp-list-table .column-location_latitude  { width: 20%;}';
	 
	 echo '.wp-list-table .column-location_longitude  { width: 20%;}';
    echo '.wp-list-table .column-location_added  { width: 20%; }';
    echo '</style>';
  }
  
  function no_items() {
    _e( 'No Records for Map Locations.' ,'wpgmp_google_map');
  }
	
  function column_default( $item, $column_name ) {
    switch( $column_name ) {
	 case 'location_title': 
	 case 'location_address':
	  case 'location_latitude':
	  
	  case 'location_longitude':
      case 'location_added':
            return $this->custom_column_value($column_name,$item);
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function custom_column_value($column_name,$item)
{
	if($column_name=='post_title ')
	return "<a href='".get_permalink( $item[ 'post_id' ] )."'>".$item[ $column_name ]."</a>";
	elseif($column_name=='user_login')
	return "<a href='".get_author_posts_url($item[ 'user_id' ])."'>".$item[ $column_name ]."</a>";
	else
	return $item[ $column_name ];
}
function get_sortable_columns() {
  $sortable_columns = array(
  'location_title '   => array('location_title ',false),
  	'location_address '   => array('location_description ',false),
	'location_latitude '   => array('location_info_message ',false),
	
	'location_longitude '   => array('location_info_message ',false),
	'location_added '   => array('location_added ',false),
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
           	'cb'        => '<input type="checkbox" />',
			'location_title'      => __( 'Title', 'wpgmp_google_map' ),
			'location_address'      => __( 'Address', 'wpgmp_google_map' ),
			'location_latitude'      => __( 'Latitude', 'wpgmp_google_map' ),
			
			'location_longitude'      => __( 'Longitude', 'wpgmp_google_map' ),
			'location_added'      => __( 'When Added', 'wpgmp_google_map' ),
        );
         return $columns;
    }
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'location_id';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_location_title($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&location=%s">Edit</a>',$_REQUEST['page'],'edit',$item['location_id']),
            'delete'      => sprintf('<a href="?page=%s&action=%s&location=%s">Delete</a>',$_REQUEST['page'],'delete',$item['location_id'])
        );
  return sprintf('%1$s %2$s', $item['location_title'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete',
  );
  return $actions;
}
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="location[]" value="%s" />', $item['location_id']
        );
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  usort( $this->table_data, array( &$this, 'usort_reorder' ) );
  
  $per_page = 10;
  $current_page = $this->get_pagenum();
  $total_items = count( $this->table_data );
  // only ncessary because we have sample data
  $this->found_data = array_slice( $this->table_data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $this->found_data;
}
}
/**
 * This function used to edit location in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_manage_locations()
{
global $wpdb; 
if( isset($_GET['action']) && $_GET['action']=='delete' && $_GET['location']!='' )
{
	$id = (int)$_GET['location'];
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."map_locations WHERE location_id=%d",$id));
}
if( isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['location']) && $_POST['location']!='' )
{
	foreach($_POST['location'] as $id)
		{
			$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."map_locations WHERE location_id=%d",$id));
						
		}
}
if( isset($_POST['update_location']) && $_POST['update_location']=='Update Locations' )
{
	
			if( $_POST['googlemap_title']=="" )
			{
			   $error[]= __( 'Please enter title.', 'wpgmp_google_map' );
			}
			if( $_POST['googlemap_address']=="" )
			{
	
			   $error[]= __( 'Please enter Address.', 'wpgmp_google_map' );
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
$location_update_table=$wpdb->prefix."map_locations";
$wpdb->update( 
$location_update_table, 
array( 
	'location_title' => htmlspecialchars(stripslashes($_POST['googlemap_title'])),
	'location_address' => htmlspecialchars(stripslashes($_POST['googlemap_address'])),
	'location_draggable' => $_POST['googlemap_draggable'],	 
	'location_latitude' => $_POST['googlemap_latitude'],
	'location_longitude' => $_POST['googlemap_longitude'],
	'location_messages'=> $messages,
	'location_marker_image' => isset($_POST['upload_image_url']) ? htmlspecialchars(stripslashes($_POST['upload_image_url'])) :'',
	'location_group_map' => isset($_POST['location_group_map']) ? $_POST['location_group_map']:''
), 
array( 'location_id' => $_GET['location'] ) 
);
 $upload_image_id = isset($_POST['upload_image_id']) ? $_POST['upload_image_id'] : '';	
 update_post_meta( $_GET['location'], '_image_id',  $upload_image_id);
 }
}
?>
<?php
if( isset($_GET['action']) && $_GET['action']=='edit' && $_GET['location']!='' )
{
$user_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations WHERE location_id=%d",$_GET['location']));
$unmess = unserialize(base64_decode($user_record->location_messages));
?>
<div class="wpgmp-wrap"> <div class="col-md-11"> <div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Edit Location', 'wpgmp_google_map')?></h3>
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

$infowindow_settings= isset($user_record->location_settings) ? unserialize($user_record->location_settings) : '';
?>
<div>
  <div class="form-horizontal">
	  <div class="row">
		<div class="col-md-2">    <label for="Title"><?php _e('Location Title', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label></div>
		<div class="col-md-7"><input name="googlemap_title"   type="text" value="<?php echo stripslashes($user_record->location_title 	); ?>" size="50" class="code form-control" >
		<p class="description"><?php _e('Enter here the location title', 'wpgmp_google_map')?></p></div>
     </div>  
     
    <div class="row">    	
    <div class="col-md-2"><label for="Description"><?php _e('Address', 'wpgmp_google_map')?>&nbsp;</label></div>
    <div class="col-md-7">
     <div class="row"><div class="col-md-10"><input type="text" name="googlemap_address"   id="googlemap_address" size="50" class="code form-control" value="<?php echo stripslashes($user_record->location_address); ?>" /></div>
  <div class="col-md-2"> <input type="button" value="Geocode" onclick="geocodeaddress()" class="btn btn-sm btn-primary"></div>
  </div>
    
    <p class="description"><?php _e('Enter here the address. Google auto suggest helps you to choose one.', 'wpgmp_google_map')?></p>
    
   <div class="row"> <div class="col-md-6"> <input type="text" name="googlemap_latitude" id="googlemap_latitude" class="google_latitude form-control" placeholder="<?php _e('Latitude', 'wpgmp_google_map')?>" value="<?php echo $user_record->location_latitude; ?>" /> <p class="description"><?php _e('Enter here the latitude.', 'wpgmp_google_map')?></p></div>
  
  <div class="col-md-6"><input type="text" name="googlemap_longitude" id="googlemap_longitude" class="google_longitude form-control" placeholder="<?php _e('Longitude', 'wpgmp_google_map')?>" value="<?php echo $user_record->location_longitude; ?>" />
    
  <p class="description"> <?php _e('Enter here the longitude.', 'wpgmp_google_map')?></p></div>
  </div>
    
    <div id="map" style="width:100%; height: 300px;margin: 0.6em;"></div>
    </div>   
    
   </div>
   
   <div class="row">
   <div class="col-md-2"><label for="title"><?php _e('Message', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7"> <textarea rows="3" cols="70" name="infowindow_message[googlemap_infowindow_message_one]" id="googlemap_infomessage" size="45" class="form-control"/><?php echo stripslashes($unmess['googlemap_infowindow_message_one']); ?></textarea>
    <p class="description"><?php _e('Enter here the infoWindow message.', 'wpgmp_google_map')?></p>
     </div>
  </div>
  
   <div class="row">
   <div class="col-md-2">    <label for="title"><?php _e('Draggable', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">    <p class="description"><input type="checkbox" name="googlemap_draggable" value="true"<?php checked($user_record->location_draggable,'true') ?>/>
    <?php _e('Do you want to allow visitors to drag the marker?.', 'wpgmp_google_map')?></p></div>
   </div>
    
   <div class="row">
   <div class="col-md-2"><label for="Image"><?php _e('Choose Marker Image', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7"> 
    <div style=" margin-left:5px;  margin-bottom:10px;">     
    <?php
    
    global $wpdb;
    
    $group_results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."group_map");
    
    if( !empty($group_results) )
    {
    ?>
    <select name="location_group_map">
         
         <option value=""><?php _e('Select group', 'wpgmp_google_map')?></option>
    
    <?php
    for($i = 0; $i < count($group_results); $i++)
    {
    ?>
    
    <option value="<?php echo $group_results[$i]->group_map_id; ?>"<?php selected($group_results[$i]->group_map_id,$user_record->location_group_map); ?>><?php echo $group_results[$i]->group_map_title; ?></option>
    
    <?php
    
    }
    ?>
    </select>
    
    <?php
    }
    
    else
    {
    
    ?>	
    
    <?php _e('You don\'t have any marker group yet.', 'wpgmp_google_map')?>&nbsp;<a href="<?php echo admin_url('admin.php?page=wpgmp_google_wpgmp_create_group_map') ?>"><?php _e('Click here', 'wpgmp_google_map')?></a>&nbsp;<?php _e('to add a group marker now', 'wpgmp_google_map')?> 
    
    <?php
    
    }
    
    ?>
    
     
    </div>
    
    <p class="description"><?php _e('Assign a marker group to this location.', 'wpgmp_google_map')?></p>
    
    </div>
    
    </div>
    
     <div class="row">
    <div class="col-md-7 col-md-offset-2">
    <input type="submit" name="update_location" id="submit" class="btn btn-primary" value="<?php _e('Update Locations', 'wpgmp_google_map')?>">
    </div>
  </div> 
</div>
</form>
</div>
</div></div></div>
<?php
}
else
{
?>
<div class="wpgmp-wrap">
<div class="col-md-12">   
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Manage Locations', 'wpgmp_google_map')?></h3>
<?php
$location_list_table = new Wpgmp_Location_Table();
$location_list_table->prepare_items();
?>
<form method="post">
<?php
$location_list_table->display();
?> 
</form> 
</div></div> 
<?php
}
}
