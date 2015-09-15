<?php
/**
 * This class used to manage locations in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
class Wpgmp_Manage_Group_Table extends WP_List_Table {
    var $group_data;
    function __construct(){
    global $status, $page,$wpdb;
        parent::__construct( array(
            'singular'  => __( 'googlemap', 'wpgmp_google_map' ),    
            'plural'    => __( 'googlemaps', 'wpgmp_google_map' ),  
            'ajax'      => false       
    ) );
		if($_GET['page']=='wpgmp_google_wpgmp_manage_group_map' && isset($_POST['s']) && $_POST['s']!='')
		{
			$query = "SELECT * FROM ".$wpdb->prefix."group_map WHERE group_map_title LIKE '%".$_POST['s']."%'";
		}
		else
		{
			$query = "SELECT * FROM ".$wpdb->prefix."group_map ORDER BY group_map_id DESC";
		}
		
	 	$this->group_data = $wpdb->get_results($query,ARRAY_A);
    add_action( 'admin_head', array( &$this, 'admin_header' ) );            
    }
	
	function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'location' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-group_map_title  { width: 20%; }';
	 echo '.wp-list-table .column-group_marker  { width: 20%;}';
    echo '.wp-list-table .column-group_added  { width: 20%; }';
    echo '</style>';
  }
  
  function no_items() {
    _e( 'No Records for Group Maps.' ,'wpgmp_google_map');
  }
	
  function column_default( $item, $column_name ) {
    switch( $column_name ) {
	 case 'group_map_title': 
	 case 'group_marker':
	  case 'group_added':
            return $this->custom_column_value($column_name,$item);
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function custom_column_value($column_name,$item)
{
	if($column_name=='post_title')
	return "<a href='".get_permalink( $item[ 'post_id' ] )."'>".$item[ $column_name ]."</a>";
	elseif($column_name=='user_login')
	return "<a href='".get_author_posts_url($item[ 'user_id' ])."'>".$item[ $column_name ]."</a>";
	else
	return $item[ $column_name ];
}
function get_sortable_columns() {
  $sortable_columns = array(
  'group_map_title'   => array('group_map_title',false),
  	'group_marker'   => array('group_marker',false),
	'group_added'   => array('group_added',false),
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
       'cb'        => '<input type="checkbox" />',
			'group_map_title'      => __( 'Group Title', 'wpgmp_google_map' ),
			'group_marker'      => __( 'Group Marker', 'wpgmp_google_map' ),
			'group_added'      => __( 'Group Added', 'wpgmp_google_map' ),
        );
         return $columns;
    }

function usort_reorder( $a, $b ) {
  
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'group_map_title';
  
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  
  $result = strcmp( $a[$orderby], $b[$orderby] );
  
  return ( $order === 'asc' ) ? $result : -$result;
}

function column_group_map_title($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&group_map=%s">Edit</a>',$_REQUEST['page'],'edit',$item['group_map_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&group_map=%s">Delete</a>',$_REQUEST['page'],'delete',$item['group_map_id']),
        );
  return sprintf('%1$s %2$s', $item['group_map_title'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete',
  );
  return $actions;
}
function column_group_marker($item) {
        return sprintf(
            '<img src="'.$item['group_marker'].'" name="group_image[]" value="%s" />', $item['group_map_id']
        );
    }
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="group_map[]" value="%s" />', $item['group_map_id']
        );
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  usort( $this->group_data, array( &$this, 'usort_reorder' ) );
  
  $per_page = 10;
  $current_page = $this->get_pagenum();
  $total_items = count( $this->group_data );
  // only ncessary because we have sample data
  $this->found_data = array_slice( $this->group_data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $this->found_data;
}
}
/**
 * This function used to edit group map in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_manage_group_map()
{
	
global $wpdb; 
if( isset($_GET['action']) && $_GET['action']=='delete' && isset($_GET['group_map']) && $_GET['group_map']!='' )
{
	$id = (int)$_GET['group_map'];
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."group_map WHERE group_map_id=%d",$id));
	$success= __( 'Selected Record Deleted Successfully.', 'wpgmp_google_map' );	
}
if( isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['group_map']) && $_POST['group_map']!='' )
{
	foreach($_POST['group_map'] as $id)
		{
			$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."group_map WHERE group_map_id=%d",$id));
						
		}
$success= __( 'Selected Records Deleted Successfully.', 'wpgmp_google_map' );
}
if( isset($_POST['update_group_map']) && $_POST['update_group_map']=='Update Group Marker' )
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
$update_group_map = $wpdb->prefix."group_map";
$wpdb->update( 
$update_group_map, 
array( 
	'group_map_title' => htmlspecialchars(stripslashes($_POST['group_map_title'])),
	'group_marker' => htmlspecialchars(stripslashes($_POST['upload_image_url']))
	
), 
array( 'group_map_id' => $_GET['group_map'] ) 
);
$success= __( 'Group Map Updated Successfully.', 'wpgmp_google_map' );
	}
}
?>
<?php
if( isset($_GET['action']) && $_GET['action']=='edit' && isset($_GET['group_map']) && $_GET['group_map']!='' )
{
$group_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."group_map WHERE group_map_id=%d",$_GET['group_map']));
?>
<div class="wpgmp-wrap">  
<div class="col-md-11">  
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Edit Marker Groups', 'wpgmp_google_map')?></h3>
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
    <legend><?php _e('General Settings', 'wpgmp_google_map')?></legend>
    
   <div class="form-horizontal">
    <div class="col-md-4 left">   <label for="title"><?php _e('Group Title', 'wpgmp_google_map')?>&nbsp;<span style="color:#F00;">*</span></label>
    </div>
   <div class="col-md-7">
    <input type="text" name="group_map_title" value="<?php echo stripslashes($group_data->group_map_title); ?>" class="create_map form-control" />
    
    <p class="description"><?php _e('Enter here the group title.', 'wpgmp_google_map')?></p></div>
    
   <div class="col-md-4 left"> 
    <label for="title"><?php _e('Choose Marker Image', 'wpgmp_google_map')?><span style="color:#F00;">*</span></label>
    </div>
   <div class="col-md-7">
    <img id="book_image" src="<?php echo $group_data->group_marker; ?>" style="float:left;" />
    
    <input type="hidden" name="upload_image_url" id="upload_image_url" value="<?php echo $group_data->group_marker; ?>" />
    
    <div style="margin-left:5px;">     
    
        <a title="<?php esc_attr_e( 'Upload Marker Image', 'wpgmp_google_map' ) ?>" href="#" id="set-book-image"><?php _e( 'Upload Marker Image', 'wpgmp_google_map' ) ?></a><br />
    
        <a title="<?php esc_attr_e( 'Remove Marker Image', 'wpgmp_google_map' ) ?>" href="#" id="remove-book-image" style="<?php echo ( ! $group_data->group_marker ? 'display:none;' : '' ); ?>"><?php _e( 'Remove Marker Image', 'wpgmp_google_map' ) ?></a><br />
    
    </div><br />
    
    <p class="description"><?php _e('Upload marker image.', 'wpgmp_google_map')?></p></div>
</fieldset>
 <div class="col-md-4 left">  </div><div class="col-md-7"> 
<input type="submit" name="update_group_map" id="submit" class="btn btn-lg btn-primary" value="<?php _e('Update Group Marker', 'wpgmp_google_map')?>"></div>
 
</div>
</form>
</div></div><?php
}
else
{
?>
<div class="wpgmp-wrap">  
<div class="col-md-12">
<div id="icon-options-general" class="icon32"><br></div><h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Manage Marker Group', 'wpgmp_google_map')?></h3>
<?php
$group_list_table = new Wpgmp_Manage_Group_Table();
$group_list_table->prepare_items();
?>
<form method="post">
<?php
$group_list_table->search_box( 'search', 'search_id' );
$group_list_table->display();
?> 
</form> 
</div></div>
<?php
}
}
