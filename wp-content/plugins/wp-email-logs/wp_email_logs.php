<?php
/*
Plugin Name: WP Email Logs
Plugin URI: http://aheadzen.com/
Description: Display the list of all email logs send via wordpress site using wp_mail() function only
Version: 1.0.1
Author: Aheadzen Team | <a href="admin.php?page=my_email_log" target="_blank">Email Logs</a>
Author URI: http://aheadzen.com/
License: GPL2
*/
/**********LOAD THE BASE CLASS**********************
* The WP_List_Table class isn't automatically available to plugins, so we need
* to check if it's available and load it if necessary.
*/
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once( 'my-log-classes.php' );
require_once( 'wp_mail_code.php' );

/************************** CREATE A PACKAGE CLASS *****************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 * 
 * Our theme for this list table is going to be movies.
 */
class My_Email_List_Table extends WP_List_Table {
    
    /** ************************************************************************
     * Normally we would be querying data from a database and manipulating that
     * for use in your list table. For this example, we're going to simplify it
     * slightly and create a pre-built array. Think of this as the data that might
     * be returned by $wpdb->query().
     * 
     * @var array 
     **************************************************************************/
    var $example_data = array(
            array(
                'from'        => 1,
                'to'     => '300',
                'type'    => 'R',
                'subject'  => 'Zach Snyder',
                'sent'  => 'Zach Snyder'
            ),
            array(
                'ID'        => 2,
                'title'     => 'Eyes Wide Shut',
                'rating'    => 'R',
                'director'  => 'Stanley Kubrick'
            ),
            array(
                'ID'        => 3,
                'title'     => 'Moulin Rouge!',
                'rating'    => 'PG-13',
                'director'  => 'Baz Luhrman'
            ),
            array(
                'ID'        => 4,
                'title'     => 'Snow White',
                'rating'    => 'G',
                'director'  => 'Walt Disney'
            ),
            array(
                'ID'        => 5,
                'title'     => 'Super 8',
                'rating'    => 'PG-13',
                'director'  => 'JJ Abrams'
            ),
            array(
                'ID'        => 6,
                'title'     => 'The Fountain',
                'rating'    => 'PG-13',
                'director'  => 'Darren Aronofsky'
            ),
            array(
                'ID'        => 7,
                'title'     => 'Watchmen',
                'rating'    => 'R',
                'director'  => 'Zach Snyder'
            )
        );
    
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    
    
    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'to':
                return My_Log_Entry_Template::display_user( $item, $column_name );
            case 'from':
                return My_Log_Entry_Template::display_user( $item, $column_name );
            case 'subject':
                return My_Log_Entry_Template::display_subject( $item, $column_name );
            case 'type':
                return $item->type;
            case 'sent':
                return $item->date_recorded;
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    
        
    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_title($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&movie=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    
    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->id                //The value of the checkbox should be the record's id
        );
    }
    
    
    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'from'     => 'From',
            'to'    => 'To',
            'subject'  => 'Subject',
            'type'  => 'Type',
            'sent'  => 'Sent'
        );
        return $columns;
    }
    
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'from'     	=> array('from',true),     //true means its already sorted
            'to'    	=> array('to',true),
            'subject'  	=> array('subject',true),
			'type'    	=> array('type',true),
			'sent'    	=> array('sent',true),
        );
        return $sortable_columns;
    }
    
    function extra_tablenav( $which ) {
		global $wp_locale;
		if ( $which == "top" ){
			global $wpdb;
			$tablename = My_Log_Entry::LOG_TABLE;
			$srch_component = $_GET['srch_component'];
			$srch_type = $_GET['srch_type'];
			$srch_dt = $_GET['srch_dt'];
			echo '<form id="movies-filter" action="" method="get">';
			echo ' <input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';
			if($_REQUEST['srch_kw']){
				echo ' <input type="hidden" name="srch_kw" value="'.$_REQUEST['srch_kw'].'" />';
				echo ' <input type="hidden" name="srch_kwin" value="'.$_REQUEST['srch_kwin'].'" />';
			}
			
			$res = $wpdb->get_results("SELECT DISTINCT YEAR(date_recorded) AS yyear, MONTH( date_recorded ) AS mmonth FROM $tablename WHERE hide_sitewide=0 order by date_recorded");
			if($res){
				echo '<select name="srch_dt" style="width:140px;">';
				echo '<option value="">'.__('All Dates','aheadzen').'</option>';
				foreach($res as $resobj)
				{
					$is_dt_selected='';
					$srchdt = $resobj->yyear . $resobj->mmonth;
					if($srchdt==$srch_dt){$is_dt_selected = 'selected';}
					echo '<option value="'.$srchdt.'" '.$is_dt_selected.'>'.esc_html($wp_locale->get_month($resobj->mmonth)." $resobj->yyear").'</option>';
				}
				echo '</select>';
			}
			
			$res = $wpdb->get_col("select distinct(component) from $tablename order by component asc");
			if($res){
				$srch_arr = array('_');
				$repl_arr = array(' ');
				echo '<select name="srch_component" style="width:140px;">';
				echo '<option value="">'.__('All Components','aheadzen').'</option>';
				for($c=0;$c<count($res);$c++)
				{
					$is_components_selected='';
					if($res[$c]==$srch_component){$is_components_selected = 'selected';}
					echo '<option value="'.$res[$c].'" '.$is_components_selected.'>'.str_replace($srch_arr,$repl_arr,$res[$c]).'</option>';
				}
				echo '</select>';
			}
			
			$res = $wpdb->get_col("select distinct(type) from $tablename order by type asc");
			if($res){
				$srch_arr = array('_');
				$repl_arr = array(' ');
				echo '<select name="srch_type" style="width:140px;">';
				echo '<option value="">'.__('All Types','aheadzen').'</option>';
				for($c=0;$c<count($res);$c++)
				{
					$is_type_selected='';
					if($res[$c]==$srch_type){$is_type_selected = 'selected';}
					echo '<option value="'.$res[$c].'" '.$is_type_selected.'>'.str_replace($srch_arr,$repl_arr,$res[$c]).'</option>';
				}
				echo '</select>';
			}
			echo ' <input type="submit" value="'.__('Filter','aheadzen').'" />';
			echo '</form>';
		}
		/*
		if ( $which == "bottom" ){
			//The code that goes after the table is there
			echo"Hi, I'm after the table";
		}*/
	}
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    
    
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }
    
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
		 
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        $data = $this->example_data;
                
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
		$defaults = array(
			'display_comments' => 'threaded',   // false for none, stream/threaded - show comments in the stream or threaded under items
			'sort'             => 'DESC',       // sort DESC or ASC
			'page'             => 1,            // which page to load
			'per_page'         => 20,           // number of items per page
			'max'              => false,        // max number to return
			// Filtering
			'user_id'          => $user_id,     // user_id to filter on
			// Searching
			'search_terms'     => false         // specify terms to search on
		);

		$r = wp_parse_args( $defaults );
		extract( $r );
		if($_GET['orderby']=='from'){$orderby='a.from_email';}
		elseif($_GET['orderby']=='to'){$orderby='a.to_email';}
		elseif($_GET['orderby']=='subject'){$orderby='a.action';}
		elseif($_GET['orderby']=='type'){$orderby='a.type';}
		elseif($_GET['orderby']=='sent'){$orderby='a.date_recorded';}
		
		$filter_array = array();
		if($_GET['srch_component']){$filter_array['object'] = $_GET['srch_component'];}
		if($_GET['srch_type']){$filter_array['action'] = $_GET['srch_type'];}
		if($_GET['srch_toemail']){$filter_array['toemail'] = $_GET['srch_toemail'];}
		if($_GET['srch_dt']){$filter_array['srch_dt'] = $_GET['srch_dt'];}
		if($_GET['srch_kw'] && $_GET['srch_kwin'])
		{
			$srch_kw = $_GET['srch_kw'];
			$srch_kwin = $_GET['srch_kwin'];
			$filtersql = '';
			$filtersql_arr = array();
			if($srch_kwin=='all'||$srch_kwin=='email_from'){$filtersql_arr[] = "a.from_email like \"%$srch_kw%\"";}
			if($srch_kwin=='all'||$srch_kwin=='email_to'){$filtersql_arr[] = "a.to_email like \"%$srch_kw%\"";}
			if($srch_kwin=='all'||$srch_kwin=='username'){$filtersql_arr[] = "u.user_login like \"%$srch_kw%\"";}
			if($srch_kwin=='all'||$srch_kwin=='subject'){$filtersql_arr[] = "a.action like \"%$srch_kw%\"";}
			if($srch_kwin=='all'||$srch_kwin=='content'){$filtersql_arr[] = "a.content like \"%$srch_kw%\"";}			
			if($filtersql_arr){
				$filtersql = '('.implode(' OR ',$filtersql_arr).')';
				$filter_array['filtersql'] = $filtersql;
			}
		}
		$args = array(
			'display_comments' => $display_comments, 
			'max' => $max, 
			'per_page' => $per_page, 
			'page' => $page, 
			'sortby' => $orderby,
			'sort' => $_GET['order'], 
			'search_terms' => $search_terms, 
			'filter' => $filter_array, 
			'show_hidden' => $show_hidden, 
			'exclude' => $exclude, 
			'in' => $in 
		);

		$data = my_log_get( $args );
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = $data['total'];
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data['activities'];
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
    
}


/** ************************ REGISTER THE TEST PAGE ****************************
 *******************************************************************************
 * Now we just need to define an admin page. For this example, we'll add a top-level
 * menu item to the bottom of the admin menus.
 */
function my_add_menu_items(){
    add_menu_page('Email Logs', 'Email Logs', 'activate_plugins', 'my_email_log', 'my_render_email_log_page');
} add_action('admin_menu', 'my_add_menu_items');


/***************************** RENDER TEST PAGE ********************************
 *******************************************************************************
 * This function renders the admin page and the example list table. Although it's
 * possible to call prepare_items() and display() from the constructor, there
 * are often times where you may need to include logic here between those steps,
 * so we've instead called those methods explicitly. It keeps things flexible, and
 * it's the way the list tables are used in the WordPress core.
 */
function my_render_email_log_page(){
    
    //Create an instance of our package class...
    $testListTable = new My_Email_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
	if ( $usersearch )
	printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;','aheadzen') . '</span>', esc_html( $usersearch ) );

    $testListTable->search_box( __( 'Search Users','aheadzen' ), 'user' );
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2><?php _e('Email Logs','aheadzen');?></h2>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" action="" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
			<div style="float:right;">
			<?php _e('search','aheadzen');?> : <input type="text" name="srch_kw" value="<?php echo $_GET['srch_kw'];?>" > <?php _e('in','aheadzen');?>
			<select name="srch_kwin">
			<option value="all"><?php _e('All','aheadzen');?></option>
			<option value="email_from" <?php if($_GET['srch_kwin']=='email_from'){echo 'selected';}?>><?php _e('From Email','aheadzen');?></option>
			<option value="email_to" <?php if($_GET['srch_kwin']=='email_to'){echo 'selected';}?>><?php _e('To Email','aheadzen');?></option>
			<option value="username" <?php if($_GET['srch_kwin']=='username'){echo 'selected';}?>><?php _e('User Name','aheadzen');?></option>
			<option value="subject" <?php if($_GET['srch_kwin']=='subject'){echo 'selected';}?>><?php _e('Subject','aheadzen');?></option>
			<option value="content" <?php if($_GET['srch_kwin']=='content'){echo 'selected';}?>><?php _e('Content','aheadzen');?></option>
			</select>
			<input type="submit" value="Search">
			</div>
		</form>
			<?php $testListTable->display() ?>
        
        
    </div>
    <?php
}