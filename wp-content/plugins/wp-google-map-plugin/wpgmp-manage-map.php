<?php
/**
 * This class used to display list of added maps in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
class Wpgmp_Maps_Table extends WP_List_Table {
var $table_data;
function __construct(){
global $status, $page,$wpdb;
parent::__construct( array(
  'singular'  => __( 'googlemap', 'wpgmp_google_map' ),    
  'plural'    => __( 'googlemaps', 'wpgmp_google_map' ),  
  'ajax'      => false       
    ) );
if( $_GET['page']=='wpgmp_google_wpgmp_manage_map' && isset($_POST['s']) && $_POST['s']!='' )
{
$query = "SELECT * FROM ".$wpdb->prefix."create_map WHERE map_title LIKE '%".$_POST['s']."%' OR  map_type LIKE '%".$_POST['s']."%' OR map_width LIKE '%".$_POST['s']."%' OR map_height LIKE '%".$_POST['s']."%' ";
}
else
{
$query = "SELECT * FROM ".$wpdb->prefix."create_map ORDER BY map_id DESC";
}
$this->table_data = $wpdb->get_results($query,ARRAY_A );
add_action( 'admin_head', array( &$this, 'admin_header' ) );            
}
function admin_header()
{
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'location' != $page )
    return;
    echo '<style type="text/css">';
	echo '.wp-list-table .column-map_title  { width: 20%; }';
	echo '.wp-list-table .column-map_width { width: 20%;}';
	echo '.wp-list-table .column-map_height { width: 20%; }';
	echo '.wp-list-table .column-map_zoom_level { width: 20%; }';
	echo '.wp-list-table .column-map_type  { width: 20%;}';
	echo '.wp-list-table .column-shortcodes  { width: 20%;}';
	
    echo '</style>';
}
function no_items()
{
    _e( 'No Records for Maps.' ,'wpgmp_google_map');
}
function column_default( $item, $column_name )
{
switch( $column_name )
{
case 'map_title': 
case 'map_width':
case 'map_height':
case 'map_zoom_level': 
case 'map_type':
case 'shortcodes':
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
function get_sortable_columns()
{
  $sortable_columns = array(
    'map_title'   => array('map_title',false),
	'map_width'   => array('map_width',false),
	'map_height'   => array('map_height',false),
	'map_zoom_level'   => array('map_zoom_level',false),
	'map_type'   => array('map_type',false),
	'shortcodes'   => array('shortcodes',false),
	
  );
  return $sortable_columns;
}
function get_columns()
{
	$columns = array(
	
	'cb'        => '<input type="checkbox" />',
	
	'map_title'      => __( 'Title', 'wpgmp_google_map' ),
					
	'map_width'      => __( 'Map Width', 'wpgmp_google_map' ),
	
	'map_height'      => __( 'Map Height', 'wpgmp_google_map' ),
	
	'map_zoom_level'      => __( 'Map Zoom Level', 'wpgmp_google_map' ),
	
	'map_type'      => __( 'Map Type', 'wpgmp_google_map' ),
	
	'shortcodes'      => __( 'Shortcodes', 'wpgmp_google_map' ),
	
	);
         return $columns;
}
function usort_reorder( $a, $b )
{
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'map_id';
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  $result = strcmp( $a[$orderby], $b[$orderby] );
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_map_title($item)
{
$actions = array(
		'edit'      => sprintf('<a href="?page=%s&action=%s&map=%s">Edit</a>',$_REQUEST['page'],'edit',$item['map_id']),
		'delete'      => sprintf('<a href="?page=%s&action=%s&map=%s">Delete</a>',$_REQUEST['page'],'delete',$item['map_id'])

	);
  return sprintf('%1$s %2$s', $item['map_title'], $this->row_actions($actions) );
}
function get_bulk_actions()
{
$actions = array(
'delete'    => 'Delete',
);
return $actions;
}
function column_cb($item)
{
	return sprintf(
		'<input type="checkbox" name="map[]" value="%s" />', $item['map_id']
	);
}
function column_shortcodes($item)
{
	return sprintf(
		'[put_wpgm id='.$item['map_id'].']'
	);
}
function prepare_items()
{
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  usort( $this->table_data, array( &$this, 'usort_reorder' ) );
  $per_page = 10;
  $current_page = $this->get_pagenum();
  $total_items = count( $this->table_data );
  $this->found_data = array_slice( $this->table_data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $this->found_data;
}
}
/**
 * This function used to edit map using manage maps page.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_manage_map()
{
global $wpdb; 
if( isset($_GET['action']) && $_GET['action']=='delete' && $_GET['map']!='')
{
	$id = (int)$_GET['map'];
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."create_map WHERE map_id=%d",$id));
	$success= __( 'Selected Records Deleted Successfully.', 'wpgmp_google_map' );
}
if( isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['map']) &&  $_POST['map']!='' )
{
	foreach($_POST['map'] as $id)
		{
			$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."create_map WHERE map_id=%d",$id));
		}
	$success= __( 'Selected Records Deleted Successfully.', 'wpgmp_google_map' );
}
if( isset($_POST['update_map']) && $_POST['update_map']=='Update Map' )
{
	
global $wpdb;
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

if( !isset($_POST['locations']) && $_POST['locations']=="" )
{
   $error[]= __( 'Please check any one location.', 'wpgmp_google_map' );
}


if( isset($_POST['direction_servics']['route_direction']) && !empty( $_POST['direction_servics']['route_direction'] ))
{
	if(count($_POST['locations'])<2)
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
if( isset( $_POST['street_view_control']['street_view_close_button']) && !empty($_POST['street_view_control']['street_view_close_button']) )
{
   	$_POST['street_view_control']['street_view_close_button'] = $_POST['street_view_control']['street_view_close_button'];
}
else
{
	$_POST['street_view_control']['street_view_close_button'] = 'false';

}
if( isset($_POST['street_view_control']['links_control']) && !empty($_POST['street_view_control']['links_control']))
{
   $_POST['street_view_control']['links_control'] = $_POST['street_view_control']['links_control'];
}
else
{
   $_POST['street_view_control']['links_control'] = 'true';
}

if( isset($_POST['street_view_control']['street_view_pan_control']) && !empty($_POST['street_view_control']['street_view_pan_control']))
{
  $_POST['street_view_control']['street_view_pan_control'] = $_POST['street_view_control']['street_view_pan_control'];
}
else
{
   $_POST['street_view_control']['street_view_pan_control'] = 'true';

}
if( isset($_POST['control']['pan_control']) && !empty($_POST['control']['pan_control']) )
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
if( isset($_POST['control']['overview_map_control']) && !empty($_POST['control']['overview_map_control']))
{
	$_POST['control']['overview_map_control'] = $_POST['control']['overview_map_control'];
}
else
{
  $_POST['control']['overview_map_control'] = 'true';
}
if( isset($_POST['info_window_setting']['info_window']) && !empty($_POST['info_window_setting']['info_window'])=="" )
{
	$_POST['info_window_setting']['info_window'] = $_POST['info_window_setting']['info_window'];
}
else
{
   $_POST['info_window_setting']['info_window'] = 'true';

}
if( isset($_POST['info_window_setting']['enable_info_window_setting']) && !empty($_POST['info_window_setting']['enable_info_window_setting']))
{
   $_POST['info_window_setting']['enable_info_window_setting'] = $_POST['info_window_setting']['enable_info_window_setting'];
   
}
else
{
  $_POST['info_window_setting']['enable_info_window_setting'] = 'false';
   $_POST['info_window_setting']['info_window_width'] = 300;
   $_POST['info_window_setting']['info_window_height'] = '';
   $_POST['info_window_setting']['info_window_shadow_style'] = 0;
   $_POST['info_window_setting']['info_window_border_radious'] = 10;
   $_POST['info_window_setting']['info_window_border_width'] = 1;
   $_POST['info_window_setting']['info_window_border_color'] = "#CCCCCC";
   $_POST['info_window_setting']['info_window_background_color'] = "#FFFFFF";
   $_POST['info_window_setting']['info_window_arrow_size'] = 20;
   $_POST['info_window_setting']['info_window_arrow_position'] = 50;
   $_POST['info_window_setting']['info_window_arrow_style'] = 0;
}
if( isset($_POST['locations']) && $_POST['locations']=="" )
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
if( $_POST['layer_setting']['choose_layer']=="KmlLayer" && $_POST['layer_setting']['map_links']=="" )
{
	$error[]= __( 'Please insert KML link.', 'wpgmp_google_map' );
}
if( $_POST['layer_setting']['choose_layer']=="FusionTablesLayer" && $_POST['layer_setting']['fusion_select']=="" )
{
	$error[]= __( 'Please insert Fusion Select.', 'wpgmp_google_map' );
}
if( $_POST['layer_setting']['choose_layer']=="FusionTablesLayer" && $_POST['layer_setting']['fusion_from']=="" )
{
	$error[]= __( 'Please insert Fusion From.', 'wpgmp_google_map' );
}
if( $_POST['layer_setting']['choose_layer']=="FusionTablesLayer" && $_POST['layer_setting']['heat_map']=="" )
{
	$_POST['layer_setting']['heat_map'] = 'false';
}

if( empty($error) )
{
$map_update_table=$wpdb->prefix."create_map";
$wpdb->update( 
$map_update_table, 
array( 
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
	'map_layer_setting' => isset($_POST['layer_setting']) ? serialize($_POST['layer_setting']) : ''

), 
array( 'map_id' => $_GET['map'] ) 
);	
$success= __( 'Map Updated Successfully.', 'wpgmp_google_map' );
}
}
?>
<style type="text/css">
.success{
	background-color:#CF9 !important;
	border:1px solid #903 !important;
}
</style>
<div class="wrap">  
<?php
if( isset($_GET['action']) &&  $_GET['action']=='edit' && $_GET['map']!='' )
{
$map_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$_GET['map']));
$unserialize_map_street_view_setting = unserialize($map_record->map_street_view_setting);
$unserialize_map_route_direction_setting = unserialize($map_record->map_route_direction_setting);
$unserialize_map_control_setting = unserialize($map_record->map_all_control);
$unserialize_map_info_window_setting = unserialize($map_record->map_info_window_setting);
$unserialize_map_layer_setting = unserialize($map_record->map_layer_setting);
$unserialize_google_map_style = unserialize($map_record->style_google_map);
$unserialize_map_polygon_setting = unserialize($map_record->map_polygon_setting);
$unserialize_map_polyline_setting = unserialize($map_record->map_polyline_setting);
$unserialize_map_cluster_setting = unserialize($map_record->map_cluster_setting);
$unserialize_map_overlay_setting = unserialize($map_record->map_overlay_setting);
?>
<div class="wpgmp-wrap">
 <div class="col-md-11">  
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Edit Map', 'wpgmp_google_map')?></h3>
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
<div> <div class="form-horizontal">
<fieldset>
    <legend><?php _e('General Setting', 'wpgmp_google_map')?></legend>
    <div class="row">
     <div class="col-md-2">  <label for="title"><?php _e('Map Title', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label></div>
   <div class="col-md-7">
	<input type="text" name="map_title" value="<?php echo stripslashes($map_record->map_title); ?>" class="create_map form-control" />
	<p class="description"><?php _e('Enter here the title', 'wpgmp_google_map')?></p></div>
 </div>   
 <div class="row">
	  <div class="col-md-2"> <label for="title"><?php _e('Map Width', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<input type="text" name="map_width" value="<?php echo $map_record->map_width; ?>" class="create_map form-control" />
	<p class="description"><?php _e('Enter here the map width in pixel. Leave it blank for 100% width.', 'wpgmp_google_map')?></p></div>
   </div>
   <div class="row">  
	  <div class="col-md-2">	<label for="title"><?php _e('Map Height', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label></div>
   <div class="col-md-7">
	<input type="text" name="map_height" value="<?php echo $map_record->map_height; ?>" class="create_map form-control" />
	<p class="description"><?php _e('Enter here the map height in px.', 'wpgmp_google_map')?></p></div>
   </div>
    <div class="row"> 
	  <div class="col-md-2">	<label for="title"><?php _e('Map Zoom Level', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
    <select name="zoom_level">
        <?php for($i=1;$i<20;$i++)
        {
			?>
        <option value="<?php echo $i; ?>"<?php selected($map_record->map_zoom_level,$i) ?>><?php echo $i; ?></option>
		<?php } ?>
    </select>
    <p class="description"><?php _e('(Available options - 1,2,3,4,5,6,8,9,10,11,12,13,14,15,16,17,18,19).', 'wpgmp_google_map')?></p>
	</div>
    </div>
     <div class="row">
	  <div class="col-md-2">  <label for="title"><?php _e('Choose Map Type', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
    <select name="choose_map">
        <option value="ROADMAP"<?php selected($map_record->map_type,'ROADMAP') ?>><?php _e('ROADMAP', 'wpgmp_google_map')?></option>
        <option value="SATELLITE"<?php selected($map_record->map_type,'SATELLITE') ?>><?php _e('SATELLITE', 'wpgmp_google_map')?></option>
        <option value="HYBRID"<?php selected($map_record->map_type,'HYBRID') ?>><?php _e('HYBRID', 'wpgmp_google_map')?></option>
        <option value="TERRAIN"<?php selected($map_record->map_type,'TERRAIN') ?>><?php _e('TERRAIN', 'wpgmp_google_map')?></option>
    </select>
	<p class="description"><?php _e('(Available options - ROADMAP,SATELLITE,HYBRID,TERRAIN {Default is roadmap type}).', 'wpgmp_google_map')?></p>
	
    </div>
    </div>
     <div class="row">
	  <div class="col-md-2 "> <label for="title"><?php _e('Turn Off Scrolling Wheel', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="scrolling_wheel" value="false"<?php checked($map_record->map_scrolling_wheel,'false') ?>/>
	<?php _e('Please check to disable scroll wheel zooms.', 'wpgmp_google_map')?></p>
    </div>
    </div>
     <div class="row">
	  <div class="col-md-2">
	<label for="title"><?php _e('Enable Visual Refresh', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="visual_refresh" value="true"<?php checked($map_record->map_visual_refresh,'true') ?>/>
	<?php _e('Please check to enable visual refresh.', 'wpgmp_google_map')?></p></div>
    </div>
     <div class="row">
	  <div class="col-md-2">	
	<label for="title"><?php _e('45&deg; Imagery', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="45imagery" value="45"<?php checked($map_record->map_45imagery,'45') ?> />
	<?php _e('Apply 45&deg; Imagery ? (only available for map type SATELLITE and HYBRID).', 'wpgmp_google_map')?></p>   </div>
</div>
</fieldset>
<fieldset>
    <legend><?php _e('Choose Locations', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></legend>
    <ul>
    <?php
    global $wpdb;
    $un_maploc = unserialize($map_record->map_locations);
    $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."map_locations");
    for($i = 0; $i < count($results); $i++)
	{
    ?>
    	<li>    
        	<?php if( in_array($results[$i]->location_id,$un_maploc) )
			{
			?>
        	<input type="checkbox" name="locations[]" value="<?php echo $results[$i]->location_id; ?>" checked="checked"/>&nbsp;&nbsp;<?php 
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
			?>
    	</li>    
     <?php    
      }
      ?>
      </ul>
</fieldset>
<fieldset>
    <legend><?php _e('Layers', 'wpgmp_google_map')?></legend>
    
   <div class="col-md-4 left">  <label for="title"><?php _e('Select Layers', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
    <select name="layer_setting[choose_layer]" onchange="mylayer(this.value)">
        <option value=""><?php _e('Select Layers', 'wpgmp_google_map')?></option>
       <option value="TrafficLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'TrafficLayer') ?>><?php _e('Traffic Layers', 'wpgmp_google_map')?></option>
        <option value="TransitLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'TransitLayer') ?>><?php _e('Transit Layers', 'wpgmp_google_map')?></option>
        <option value="BicyclingLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'BicyclingLayer') ?>><?php _e('Bicycling Layers', 'wpgmp_google_map')?></option>
        <option value="PanoramioLayer"<?php selected($unserialize_map_layer_setting['choose_layer'],'PanoramioLayer') ?>><?php _e('Panoramio Layers', 'wpgmp_google_map')?></option>
    </select>
	<p class="description"><?php _e('(Available options - Traffic Layers,Transit Layers,Bicycling Layers,Panoramio Layers).', 'wpgmp_google_map')?></p>
    </div>

	
</fieldset>
<fieldset>
    <legend><?php _e('Control Setting', 'wpgmp_google_map')?></legend>
    
   
     <div class="col-md-4 left">    <label for="title"><?php _e('Turn Off Pan Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[pan_control]" value="false"<?php checked($unserialize_map_control_setting['pan_control'],'false') ?>/>
	<?php _e('Please check to disable pan control.', 'wpgmp_google_map')?></p>
  </div>
     <div class="col-md-4 left">    
    <label for="title"><?php _e('Turn Off Zoom Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[zoom_control]" value="false"<?php checked($unserialize_map_control_setting['zoom_control'],'false') ?>/>
	<?php _e('Please check to disable zoom control.', 'wpgmp_google_map')?></p>
    </div>
     <div class="col-md-4 left">  
    <label for="title"><?php _e('Turn Off Map Type Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[map_type_control]" value="false"<?php checked($unserialize_map_control_setting['map_type_control'],'false') ?>/>
	<?php _e('Please check to disable map type control.', 'wpgmp_google_map')?></p>
    </div>
     <div class="col-md-4 left">  
    <label for="title"><?php _e('Turn Off Scale Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[scale_control]" value="false"<?php checked($unserialize_map_control_setting['scale_control'],'false') ?>/>
	<?php _e('Please check to disable scale control.', 'wpgmp_google_map')?></p>
    </div>
     <div class="col-md-4 left"> 
    <label for="title"><?php _e('Turn Off Street View Control', 'wpgmp_google_map')?></label> </div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[street_view_control]" value="false"<?php checked($unserialize_map_control_setting['street_view_control'],'false') ?>/>
	<?php _e('Please check to disable street view control.', 'wpgmp_google_map')?></p>
    </div>
     <div class="col-md-4 left">  
    <label for="title"><?php _e('Turn Off Overview Map Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="control[overview_map_control]" value="false"<?php checked($unserialize_map_control_setting['overview_map_control'],'false') ?>/>
	<?php _e('Please check to disable overview map control.', 'wpgmp_google_map')?></p>
    </div>
</fieldset>



<fieldset>
    <legend><?php _e('Street View Setting', 'wpgmp_google_map')?></legend>
    
   <div class="col-md-4 left">  <label for="title"><?php _e('Turn On Street View', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_control]"  class="street_view_toggle" value="true"<?php checked($unserialize_map_street_view_setting['street_control'],'true') ?>/>
	<?php _e('Please check to enable Street View control.', 'wpgmp_google_map')?></p>
 </div>
<?php
if( $unserialize_map_street_view_setting['street_control']=='true' )
{
?>   
<div id="disply_street_view">
  
     <div class="col-md-4 left">    <label for="title"><?php _e('Turn On Close Button', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_view_close_button]" value="true"<?php checked($unserialize_map_street_view_setting['street_view_close_button'],'true') ?>/>
	<?php _e('Please check to enable Close button.', 'wpgmp_google_map')?></p>
    </div>
     <div class="col-md-4 left">
    <label for="title"><?php _e('Turn Off links Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[links_control]" value="false"<?php checked($unserialize_map_street_view_setting['links_control'],'false') ?>/>
	<?php _e('Please check to disable links control.', 'wpgmp_google_map')?></p>
     </div>
     <div class="col-md-4 left">
    <label for="title"><?php _e('Turn Off Street View Pan Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_view_pan_control]" value="false"<?php checked($unserialize_map_street_view_setting['street_view_pan_control'],'false') ?>/>
	<?php _e('Please check to disable Street View Pan control.', 'wpgmp_google_map')?></p>
   </div> 
   </div>
 <?php
}
else
{
?>
<div id="disply_street_view" style="display:none;">
  
      <div class="col-md-4 left">   <label for="title"><?php _e('Turn On Close Button', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_view_close_button]" value="true"<?php checked($unserialize_map_street_view_setting['street_view_close_button'],'true') ?>/>
	<?php _e('Please check to enable Close button.', 'wpgmp_google_map')?></p>
    </div>
     <div class="col-md-4 left">
    <label for="title"><?php _e('Turn Off links Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[links_control]" value="false"<?php checked($unserialize_map_street_view_setting['links_control'],'false') ?>/>
	<?php _e('Please check to disable links control.', 'wpgmp_google_map')?></p>
    </div>
     <div class="col-md-4 left">
    <label for="title"><?php _e('Turn Off Street View Pan Control', 'wpgmp_google_map')?></label></div>
   <div class="col-md-7">
	<p class="description"><input type="checkbox" name="street_view_control[street_view_pan_control]" value="false"<?php checked($unserialize_map_street_view_setting['street_view_pan_control'],'false') ?>/>
	<?php _e('Please check to disable Street View Pan control.', 'wpgmp_google_map')?></p>
   </div>
   </div>
<?php
}
?>
    
</fieldset>

<fieldset>
    <legend><?php _e('Map Style Settings', 'wpgmp_google_map')?></legend>
	 	<p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p>
</fieldset>

<fieldset>
    <legend><?php _e('Polygon Settings', 'wpgmp_google_map')?></legend>  
 	<p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p>
</fieldset>
<fieldset>
    <legend><?php _e('Polyline Settings', 'wpgmp_google_map')?></legend>  
 	<p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p>

</fieldset>
<fieldset>
    <legend><?php _e('Marker Cluster Settings', 'wpgmp_google_map')?></legend>
	 	<p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p>

</fieldset>
<fieldset>
    <legend><?php _e('Overlay Settings', 'wpgmp_google_map')?></legend>
	 <p class="description"><?php _e('Available in Pro Version. <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps/5211638">Buy Now</a>', 'wpgmp_google_map')?></p>

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

	<p class="submit">
	<input type="submit" name="update_map" id="submit" class="btn btn-primary" value="<?php _e('Update Map', 'wpgmp_google_map')?>">
	
    </p>
</div>
</form>
</div>
</div>
</div></div>
<?php } else {  ?>
<div class="wpgmp-wrap">  
<div class="col-md-12">
 
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Manage Maps', 'wpgmp_google_map')?></h3>
<?php
$location_list_table = new Wpgmp_Maps_Table();
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
