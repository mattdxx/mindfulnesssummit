<?php

/**
 * My Log Classes
 *
 * @package BuddyPress
 * @subpackage ActivityClasses
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

Class My_Log_Entry {
	var $id;
	var $item_id;
	var $secondary_item_id;
	var $user_id;
	var $primary_link;
	var $component;
	var $type;
	var $action;
	var $content;
	var $date_recorded;
	var $hide_sitewide = false;
	var $mptt_left;
	var $mptt_right;
	var $from_email;
	var $to_email;
	const LOG_TABLE = 'ask_log';

	function My_Log_Entry( $id = false ) {
		$this->__construct( $id );
	}

	function __construct( $id = false ) {
		global $bp;

		if ( !empty( $id ) ) {
			$this->id = $id;
			$this->populate();
		}
	}

	function populate() {
		global $wpdb, $bp;
		if ( $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {My_Log_Entry::LOG_TABLE} WHERE id = %d", $this->id ) ) ) {
			$this->id                = $row->id;
			$this->item_id           = $row->item_id;
			$this->secondary_item_id = $row->secondary_item_id;
			$this->user_id           = $row->user_id;
			$this->primary_link      = $row->primary_link;
			$this->component         = $row->component;
			$this->type              = $row->type;
			$this->action            = $row->action;
			$this->content           = $row->content;
			$this->date_recorded     = $row->date_recorded;
			$this->hide_sitewide     = $row->hide_sitewide;
			
		}
	}

	function save() {
		global $wpdb, $bp, $current_user;
		
		if ( !$this->component || !$this->type )
			return false;
			
		if ( !$this->primary_link )
			$this->primary_link = $bp->loggedin_user->domain;
		
		// If we have an existing ID, update the activity item, otherwise insert it.
		if ( $this->id ){
			$q = $wpdb->prepare( "UPDATE " . My_Log_Entry::LOG_TABLE . " SET user_id = %d, component = %s, type = %s, action = %s, content = %s, primary_link = %s, date_recorded = %s, item_id = %s, secondary_item_id = %s, hide_sitewide = %d WHERE id = %d", $this->user_id, $this->component, $this->type, $this->action, $this->content, $this->primary_link, $this->date_recorded, $this->item_id, $this->secondary_item_id, $this->hide_sitewide, $this->id );
		}else{
			$q = $wpdb->prepare( "INSERT INTO " . My_Log_Entry::LOG_TABLE . " ( user_id, component, type, action, content, primary_link, date_recorded, item_id, secondary_item_id, hide_sitewide,from_email,to_email ) VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s )", $this->user_id, $this->component, $this->type, $this->action, $this->content, $this->primary_link, $this->date_recorded, $this->item_id, $this->secondary_item_id, $this->hide_sitewide,$this->from_email,$this->to_email );
		}
		
		if ( !$wpdb->query( $q ) )
			return false;

		if ( empty( $this->id ) )
			$this->id = $wpdb->insert_id;

		return true;
	}

	// Static Functions

	function get($arg) {
		global $wpdb, $bp;
		$max = $arg['max'] ? $arg['max'] : false;
		$page = $arg['page'] ? $arg['page'] : 1;
		$per_page = $arg['per_page'] ? $arg['per_page'] : 25;
		$sortby = $arg['sortby'] ? $arg['sortby'] : 'a.date_recorded';
		$sort = $arg['sort'] ? $arg['sort'] : 'DESC';
		$search_terms = $arg['search_terms'] ? $arg['search_terms'] : false;
		$filter = $arg['filter'] ? $arg['filter'] : false;
		$display_comments = $arg['display_comments'] ? $arg['display_comments'] : false;
		$show_hidden = $arg['show_hidden'] ? $arg['show_hidden'] : false;
		$exclude = $arg['exclude'] ? $arg['exclude'] : false;
		$in = $arg['in'] ? $arg['in'] : false;
		
		// Select conditions
		$select_sql = "SELECT a.*, u.user_email, u.user_nicename, u.user_login, u.display_name";

		$from_sql = " FROM " . My_Log_Entry::LOG_TABLE . " a LEFT JOIN {$wpdb->users} u ON a.user_id = u.ID";

		// Where conditions
		$where_conditions = array();

		// Searching
		if ( $search_terms ) {
			$search_terms = $wpdb->escape( $search_terms );
			$where_conditions['search_sql'] = "a.content LIKE '%%" . like_escape( $search_terms ) . "%%'";
		}

		// Filtering
		if ( $filter && $filter_sql = My_Log_Entry::get_filter_sql( $filter ) )
			$where_conditions['filter_sql'] = $filter_sql;

		// Sorting
		if(strtolower($sort)=='asc' || strtolower($sort)=='desc'){ }else{$sort = 'DESC';}

		// Hide Hidden Items?
		if ( !$show_hidden )
			$where_conditions['hidden_sql'] = "a.hide_sitewide = 0";

		// Exclude specified items
		if ( $exclude )
			$where_conditions['exclude'] = "a.id NOT IN ({$exclude})";

		// The specific ids to which you want to limit the query
		if ( !empty( $in ) ) {
			if ( is_array( $in ) ) {
				$in = implode ( ',', array_map( 'absint', $in ) );
			} else {
				$in = implode ( ',', array_map( 'absint', explode ( ',', $in ) ) );
			}

			$where_conditions['in'] = "a.id IN ({$in})";
		}

		// Alter the query based on whether we want to show activity item
		// comments in the stream like normal comments or threaded below
		// the activity.
		if ( false === $display_comments || 'threaded' === $display_comments )
			$where_conditions[] = "a.type != 'activity_comment'";
	
		$where_sql = 'WHERE ' . join( ' AND ', $where_conditions );
		if ( $per_page && $page ) {
			$pag_sql = $wpdb->prepare( "LIMIT %d, %d", intval( ( $page - 1 ) * $per_page ), intval( $per_page ) );
			//echo '<br /><br />'. "{$select_sql} {$from_sql} {$where_sql} ORDER BY {$sortby} {$sort} {$pag_sql}";
			$activities = $wpdb->get_results("{$select_sql} {$from_sql} {$where_sql} ORDER BY {$sortby} {$sort} {$pag_sql}");
		} else {
			$activities = $wpdb->get_results("{$select_sql} {$from_sql} {$where_sql} ORDER BY {$sortby} {$sort}") ;
		}

		$total_activities_sql = $wpdb->prepare( "SELECT count(a.id) FROM " . My_Log_Entry::LOG_TABLE . " a {$where_sql} ORDER BY a.date_recorded {$sort}" , OBJECT);

		$total_activities = $wpdb->get_var( $total_activities_sql );

		// Get the fullnames of users so we don't have to query in the loop
		if (function_exists('bp_is_active') && bp_is_active( 'xprofile' ) && $activities ) {
			foreach ( (array)$activities as $activity ) {
				if ( (int)$activity->user_id )
					$activity_user_ids[] = $activity->user_id;
			}

			$activity_user_ids = implode( ',', array_unique( (array)$activity_user_ids ) );
			if ( !empty( $activity_user_ids ) ) {
				if ( $names = $wpdb->get_results( $wpdb->prepare( "SELECT user_id, value AS user_fullname FROM {$bp->profile->table_name_data} WHERE field_id = 1 AND user_id IN ({$activity_user_ids})" ,'OBJECT') ) ) {
					foreach ( (array)$names as $name )
						$tmp_names[$name->user_id] = $name->user_fullname;

					foreach ( (array)$activities as $i => $activity ) {
						if ( !empty( $tmp_names[$activity->user_id] ) )
							$activities[$i]->user_fullname = $tmp_names[$activity->user_id];
					}

					unset( $names );
					unset( $tmp_names );
				}
			}
		}

		if ( $activities && $display_comments )
			$activities = My_Log_Entry::append_comments( $activities );

		// If $max is set, only return up to the max results
		if ( !empty( $max ) ) {
			if ( (int)$total_activities > (int)$max )
				$total_activities = $max;
		}

		return array( 'activities' => $activities, 'total' => (int)$total_activities );
	}

	/**
	 * In BuddyPress 1.2.x, this was used to retrieve specific activity stream items (for example, on an activity's permalink page).
	 * As of 1.5.x, use My_Log_Entry::get( ..., $in ) instead.
	 *
	 * @deprecated 1.5
	 * @deprecated Use My_Log_Entry::get( ..., $in ) instead.
	 * @param mixed $activity_ids Array or comma-separated string of activity IDs to retrieve
	 * @param int $max Maximum number of results to return. (Optional; default is no maximum)
	 * @param int $page The set of results that the user is viewing. Used in pagination. (Optional; default is 1)
	 * @param int $per_page Specifies how many results per page. Used in pagination. (Optional; default is 25)
	 * @param string MySQL column sort; ASC or DESC. (Optional; default is DESC)
	 * @param bool $display_comments Retrieve an activity item's associated comments or not. (Optional; default is false)
	 * @return array
	 * @since 1.2
	 */
	function get_specific( $activity_ids, $max = false, $page = 1, $per_page = 25, $sort = 'DESC', $display_comments = false ) {
		_deprecated_function( __FUNCTION__, '1.5', 'Use My_Log_Entry::get( ..., $in ) instead.' );
		return My_Log_Entry::get( $max, $page, $per_page, $sort, false, false, $display_comments, false, false, $activity_ids );
	}

	function get_id( $user_id, $component, $type, $item_id, $secondary_item_id, $action, $content, $date_recorded ) {
		global $bp, $wpdb;

		$where_args = false;

		if ( !empty( $user_id ) )
			$where_args[] = $wpdb->prepare( "user_id = %d", $user_id );

		if ( !empty( $component ) )
			$where_args[] = $wpdb->prepare( "component = %s", $component );

		if ( !empty( $type ) )
			$where_args[] = $wpdb->prepare( "type = %s", $type );

		if ( !empty( $item_id ) )
			$where_args[] = $wpdb->prepare( "item_id = %s", $item_id );

		if ( !empty( $secondary_item_id ) )
			$where_args[] = $wpdb->prepare( "secondary_item_id = %s", $secondary_item_id );

		if ( !empty( $action ) )
			$where_args[] = $wpdb->prepare( "action = %s", $action );

		if ( !empty( $content ) )
			$where_args[] = $wpdb->prepare( "content = %s", $content );

		if ( !empty( $date_recorded ) )
			$where_args[] = $wpdb->prepare( "date_recorded = %s", $date_recorded );

		if ( !empty( $where_args ) )
			$where_sql = 'WHERE ' . join( ' AND ', $where_args );
		else
			return false;

		return $wpdb->get_var( "SELECT id FROM " . My_Log_Entry::LOG_TABLE . " {$where_sql}" );
	}

	function delete( $args ) {
		global $wpdb, $bp;

		$defaults = array(
			'id'                => false,
			'action'            => false,
			'content'           => false,
			'component'         => false,
			'type'              => false,
			'primary_link'      => false,
			'user_id'           => false,
			'item_id'           => false,
			'secondary_item_id' => false,
			'date_recorded'     => false,
			'hide_sitewide'     => false
		);
		$params = wp_parse_args( $args, $defaults );
		extract( $params );

		$where_args = false;

		if ( !empty( $id ) )
			$where_args[] = $wpdb->prepare( "id = %d", $id );

		if ( !empty( $user_id ) )
			$where_args[] = $wpdb->prepare( "user_id = %d", $user_id );

		if ( !empty( $action ) )
			$where_args[] = $wpdb->prepare( "action = %s", $action );

		if ( !empty( $content ) )
			$where_args[] = $wpdb->prepare( "content = %s", $content );

		if ( !empty( $component ) )
			$where_args[] = $wpdb->prepare( "component = %s", $component );

		if ( !empty( $type ) )
			$where_args[] = $wpdb->prepare( "type = %s", $type );

		if ( !empty( $primary_link ) )
			$where_args[] = $wpdb->prepare( "primary_link = %s", $primary_link );

		if ( !empty( $item_id ) )
			$where_args[] = $wpdb->prepare( "item_id = %s", $item_id );

		if ( !empty( $secondary_item_id ) )
			$where_args[] = $wpdb->prepare( "secondary_item_id = %s", $secondary_item_id );

		if ( !empty( $date_recorded ) )
			$where_args[] = $wpdb->prepare( "date_recorded = %s", $date_recorded );

		if ( !empty( $hide_sitewide ) )
			$where_args[] = $wpdb->prepare( "hide_sitewide = %d", $hide_sitewide );

		if ( !empty( $where_args ) )
			$where_sql = 'WHERE ' . join( ' AND ', $where_args );
		else
			return false;

		// Fetch the activity IDs so we can delete any comments for this activity item
		$activity_ids = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM " . My_Log_Entry::LOG_TABLE . " {$where_sql}" ) );

		if ( !$wpdb->query( $wpdb->prepare( "DELETE FROM " . My_Log_Entry::LOG_TABLE . " {$where_sql}" ) ) )
			return false;

		if ( $activity_ids ) {
			My_Log_Entry::delete_activity_item_comments( $activity_ids );
			My_Log_Entry::delete_activity_meta_entries( $activity_ids );

			return $activity_ids;
		}

		return $activity_ids;
	}

	function delete_activity_item_comments( $activity_ids ) {
		global $bp, $wpdb;

		if ( is_array( $activity_ids ) )
			$activity_ids = implode ( ',', array_map( 'absint', $activity_ids ) );
		else
			$activity_ids = implode ( ',', array_map( 'absint', explode ( ',', $activity_ids ) ) );

		return $wpdb->query( $wpdb->prepare( "DELETE FROM " . My_Log_Entry::LOG_TABLE . " WHERE type = 'activity_comment' AND item_id IN ({$activity_ids})" ) );
	}

	function delete_activity_meta_entries( $activity_ids ) {
		global $bp, $wpdb;

		if ( is_array( $activity_ids ) )
			$activity_ids = implode ( ',', array_map( 'absint', $activity_ids ) );
		else
			$activity_ids = implode ( ',', array_map( 'absint', explode ( ',', $activity_ids ) ) );

		return $wpdb->query( $wpdb->prepare( "DELETE FROM {My_Log_Entry::LOG_TABLE_meta} WHERE activity_id IN ({$activity_ids})" ) );
	}

	function append_comments( $activities ) {
		global $bp, $wpdb;

		$activity_comments = array();

		/* Now fetch the activity comments and parse them into the correct position in the activities array. */
		foreach( (array)$activities as $activity ) {
			if ( 'activity_comment' != $activity->type && $activity->mptt_left && $activity->mptt_right )
				$activity_comments[$activity->id] = My_Log_Entry::get_activity_comments( $activity->id, $activity->mptt_left, $activity->mptt_right );
		}

		/* Merge the comments with the activity items */
		foreach( (array)$activities as $key => $activity )
			if ( isset( $activity_comments[$activity->id] ) )
				$activities[$key]->children = $activity_comments[$activity->id];

		return $activities;
	}

	function get_activity_comments( $activity_id, $left, $right ) {
		global $wpdb, $bp;

		if ( !$comments = wp_cache_get( 'my_log_comments_' . $activity_id ) ) {
			// Select the user's fullname with the query
			if (function_exists('bp_is_active') && bp_is_active( 'xprofile' ) ) {
				$fullname_select = ", pd.value as user_fullname";
				$fullname_from = ", {$bp->profile->table_name_data} pd ";
				$fullname_where = "AND pd.user_id = a.user_id AND pd.field_id = 1";

			// Prevent debug errors
			} else {
				$fullname_select = $fullname_from = $fullname_where = '';
			}

			// Retrieve all descendants of the $root node
			$descendants = $wpdb->get_results( $wpdb->prepare( "SELECT a.*, u.user_email, u.user_nicename, u.user_login, u.display_name{$fullname_select} FROM " . My_Log_Entry::LOG_TABLE . " a, {$wpdb->users} u{$fullname_from} WHERE u.ID = a.user_id {$fullname_where} AND a.type = 'activity_comment' AND a.item_id = %d AND a.mptt_left BETWEEN %d AND %d ORDER BY a.date_recorded ASC", $activity_id, $left, $right ) );

			// Loop descendants and build an assoc array
			foreach ( (array)$descendants as $d ) {
				$d->children = array();

				// If we have a reference on the parent
				if ( isset( $ref[ $d->secondary_item_id ] ) ) {
					$ref[ $d->secondary_item_id ]->children[ $d->id ] = $d;
					$ref[ $d->id ] =& $ref[ $d->secondary_item_id ]->children[ $d->id ];

				// If we don't have a reference on the parent, put in the root level
				} else {
					$comments[ $d->id ] = $d;
					$ref[ $d->id ] =& $comments[ $d->id ];
				}
			}
			wp_cache_set( 'my_log_comments_' . $activity_id, $comments, 'bp' );
		}

		return $comments;
	}

	function rebuild_activity_comment_tree( $parent_id, $left = 1 ) {
		global $wpdb, $bp;

		// The right value of this node is the left value + 1
		$right = $left + 1;

		// Get all descendants of this node
		$descendants = My_Log_Entry::get_child_comments( $parent_id );

		// Loop the descendants and recalculate the left and right values
		foreach ( (array)$descendants as $descendant )
			$right = My_Log_Entry::rebuild_activity_comment_tree( $descendant->id, $right );

		// We've got the left value, and now that we've processed the children
		// of this node we also know the right value
		if ( 1 == $left )
			$wpdb->query( $wpdb->prepare( "UPDATE " . My_Log_Entry::LOG_TABLE . " SET mptt_left = %d, mptt_right = %d WHERE id = %d", $left, $right, $parent_id ) );
		else
			$wpdb->query( $wpdb->prepare( "UPDATE " . My_Log_Entry::LOG_TABLE . " SET mptt_left = %d, mptt_right = %d WHERE type = 'activity_comment' AND id = %d", $left, $right, $parent_id ) );

		// Return the right value of this node + 1
		return $right + 1;
	}

	function get_child_comments( $parent_id ) {
		global $bp, $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT id FROM " . My_Log_Entry::LOG_TABLE . " WHERE type = 'activity_comment' AND secondary_item_id = %d", $parent_id ) );
	}

	function get_recorded_components() {
		global $wpdb, $bp;

		return $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT component FROM " . My_Log_Entry::LOG_TABLE . " ORDER BY component ASC" ) );
	}

	function get_sitewide_items_for_feed( $limit = 35 ) {
		global $wpdb, $bp;
		$activity_feed = array();
		if(function_exists('bp_activity_get_sitewide')){
			$activities = bp_activity_get_sitewide( array( 'max' => $limit ) );
			if($activities){
				for ( $i = 0, $count = count( $activities ); $i < $count; ++$i ) {
						$title = explode( '<span', $activities[$i]['content'] );

						$activity_feed[$i]['title'] = trim( strip_tags( $title[0] ) );
						$activity_feed[$i]['link'] = $activities[$i]['primary_link'];
						$activity_feed[$i]['description'] = @sprintf( $activities[$i]['content'], '' );
						$activity_feed[$i]['pubdate'] = $activities[$i]['date_recorded'];
				}
			}
		}

		return $activity_feed;
	}

	function get_in_operator_sql( $field, $items ) {
		global $wpdb;

		// split items at the comma
		$items_dirty = explode( ',', $items );

		// array of prepared integers or quoted strings
		$items_prepared = array();

		// clean up and format each item
		foreach ( $items_dirty as $item ) {
			// clean up the string
			$item = trim( $item );
			// pass everything through prepare for security and to safely quote strings
			$items_prepared[] = ( is_numeric( $item ) ) ? $wpdb->prepare( '%d', $item ) : $wpdb->prepare( '%s', $item );
		}

		// build IN operator sql syntax
		if ( count( $items_prepared ) )
			return sprintf( '%s IN ( %s )', trim( $field ), implode( ',', $items_prepared ) );
		else
			return false;
	}

	function get_filter_sql( $filter_array ) {
		global $wpdb;
		
		if ( !empty( $filter_array['user_id'] ) ) {
			$user_sql = My_Log_Entry::get_in_operator_sql( 'a.user_id', $filter_array['user_id'] );
			if ( !empty( $user_sql ) )
				$filter_sql[] = $user_sql;
		}

		if ( !empty( $filter_array['object'] ) ) {
			$object_sql = My_Log_Entry::get_in_operator_sql( 'a.component', $filter_array['object'] );
			if ( !empty( $object_sql ) )
				$filter_sql[] = $object_sql;
		}

		if ( !empty( $filter_array['action'] ) ) {
			$action_sql = My_Log_Entry::get_in_operator_sql( 'a.type', $filter_array['action'] );
			if ( !empty( $action_sql ) )
				$filter_sql[] = $action_sql;
		}

		if ( !empty( $filter_array['primary_id'] ) ) {
			$pid_sql = My_Log_Entry::get_in_operator_sql( 'a.item_id', $filter_array['primary_id'] );
			if ( !empty( $pid_sql ) )
				$filter_sql[] = $pid_sql;
		}
		
		if ( !empty( $filter_array['toemail'] ) ) {
			$to_sql  = My_Log_Entry::get_in_operator_sql( 'a.to_email', $filter_array['toemail'] );
			if ( !empty( $to_sql ) )
				$filter_sql[] = $to_sql;
		}
		
		if ( !empty( $filter_array['srch_dt'] ) ) {
			$srch_dt = $filter_array['srch_dt'];
			$year = substr($srch_dt,0,4);
			$month = substr($srch_dt,4,2);
			if(strlen($year)==4 && strlen($month)==2){
				$dt_sql = '(YEAR(a.date_recorded)='.$year.' AND MONTH(a.date_recorded)='.$month.')';
				$filter_sql[] = $dt_sql;
			}
			
		}

		if ( !empty( $filter_array['secondary_id'] ) ) {
			$sid_sql = My_Log_Entry::get_in_operator_sql( 'a.secondary_item_id', $filter_array['secondary_id'] );
			if ( !empty( $sid_sql ) )
				$filter_sql[] = $sid_sql;
		}
		
		if($filter_sql){$sql_return = join( ' AND ', $filter_sql );}
		
		if ( !empty( $filter_array['filtersql'] ) ) {
			$fsql=$filter_array['filtersql'];
			if ( !empty( $fsql ) )
				if($sql_return){$sql_return .=' AND ';}
				$sql_return .= $fsql;
		}
		
		if ( empty($sql_return) )return false;
			
		return $sql_return;
	}

	function get_last_updated() {
		global $bp, $wpdb;

		return $wpdb->get_var( $wpdb->prepare( "SELECT date_recorded FROM " . My_Log_Entry::LOG_TABLE . " ORDER BY date_recorded DESC LIMIT 1" ) );
	}

	function total_favorite_count( $user_id ) {
		if (function_exists('bp_get_user_meta') &&  !$favorite_activity_entries = bp_get_user_meta( $user_id, 'bp_favorite_activities', true ) )
			return 0;

		return count( maybe_unserialize( $favorite_activity_entries ) );
	}

	function check_exists_by_content( $content ) {
		global $wpdb, $bp;

		return $wpdb->get_var( $wpdb->prepare( "SELECT id FROM " . My_Log_Entry::LOG_TABLE . " WHERE content = %s", $content ) );
	}

	function hide_all_for_user( $user_id ) {
		global $wpdb, $bp;

		return $wpdb->get_var( $wpdb->prepare( "UPDATE " . My_Log_Entry::LOG_TABLE . " SET hide_sitewide = 1 WHERE user_id = %d", $user_id ) );
	}
}

function my_log_add( $args = '' ) {
	global $bp;

	$defaults = array(
		'id'                => false, // Pass an existing activity ID to update an existing entry.

		'action'            => '',    // The activity action - e.g. "Jon Doe posted an update"
		'content'           => '',    // Optional: The content of the activity item e.g. "BuddyPress is awesome guys!"

		'component'         => false, // The name/ID of the component e.g. groups, profile, mycomponent
		'type'              => false, // The activity type e.g. activity_update, profile_updated
		'primary_link'      => '',    // Optional: The primary URL for this item in RSS feeds (defaults to activity permalink)

		'user_id'           => $bp->loggedin_user->id, // Optional: The user to record the activity for, can be false if this activity is not for a user.
		'item_id'           => false, // Optional: The ID of the specific item being recorded, e.g. a blog_id
		'secondary_item_id' => false, // Optional: A second ID used to further filter e.g. a comment_id
		//'recorded_time'     => bp_core_current_time(), // The GMT time that this activity was recorded
		'hide_sitewide'     => false  // Should this be hidden on the sitewide activity stream?
	);
	if(function_exists('bp_core_current_time')){
		$defaults['recorded_time'] = bp_core_current_time(); // The GMT time that this activity was recorded
	}elseif(function_exists('current_time')){
		$defaults['recorded_time'] = current_time( true, 'mysql' );
	}
	$params = wp_parse_args( $args, $defaults );
	extract( $params, EXTR_SKIP );
	
	if ( empty( $type ) && !empty( $component_action ) )
		$type = $component_action;

		
	// Setup activity to be added
	$entry		              = new My_Log_Entry( $id );
	$entry->user_id           = $user_id;
	$entry->component         = $component;
	$entry->type              = $type;
	$entry->action            = $action;
	$entry->content           = $content;
	$entry->primary_link      = $primary_link;
	$entry->item_id           = $item_id;
	$entry->secondary_item_id = $secondary_item_id;
	$entry->date_recorded     = $recorded_time;
	
	if ( !$entry->save() )
		return false;

	// If this is an activity comment, rebuild the tree
	if ( 'activity_comment' == $entry->type )
		My_Log_Entry::rebuild_activity_comment_tree( $entry->item_id );

	return $entry->id;
}

function my_log_get( $args = '' ) {
	$defaults = array(
		'max'              => false,  // Maximum number of results to return
		'page'             => 1,      // page 1 without a per_page will result in no pagination.
		'per_page'         => false,  // results per page
		'sort'             => 'DESC', // sort ASC or DESC
		'display_comments' => false,  // false for no comments. 'stream' for within stream display, 'threaded' for below each activity item

		'search_terms'     => false,  // Pass search terms as a string
		'show_hidden'      => false,  // Show activity items that are hidden site-wide?
		'exclude'          => false,  // Comma-separated list of activity IDs to exclude
		'in'               => false,  // Comma-separated list or array of activity IDs to which you want to limit the query

		/**
		 * Pass filters as an array -- all filter items can be multiple values comma separated:
		 * array(
		 * 	'user_id'      => false, // user_id to filter on
		 *	'object'       => false, // object to filter on e.g. groups, profile, status, friends
		 *	'action'       => false, // action to filter on e.g. activity_update, profile_updated
		 *	'primary_id'   => false, // object ID to filter on e.g. a group_id or forum_id or blog_id etc.
		 *	'secondary_id' => false, // secondary object ID to filter on e.g. a post_id
		 * );
		 */
		'filter' => array()
	);
	$activity = My_Log_Entry::get($args);

	return $activity;
}

class My_Log_Entry_Template {
	var $id;
	public static function display_subject( $item, $col_type )
	{
		if ( $col_type == 'subject' )
		{
			$subject = '<a href="#TB_inline?height=300&amp;width=300&amp;inlineId=msgContentPopup'.$item->id.'" value="'.$item->action.'" class="thickbox">'.$item->action.'</a>';
			$subject .= '<div id="msgContentPopup'.$item->id.'" style="display:none"><h2>'.$item->action.'</h2><div style="width:100%;clear:both;display:inline-block;">'.$item->content.'</div>';
		}
		return $subject;
	}
				
	public static function display_user( $item, $col_type )
	{
		
		if ( $col_type == 'from' )
		{
			$user_id = $item->user_id;
			$user_fullname = $item->user_fullname;
			$user_email=$item->from_email;
		}
		else if ( $col_type == 'to' )
		{
			if($item->item_id){
				$user_id = $item->item_id;
				$profileuser = get_userdata($user_id);
				$user_fullname = $profileuser->data->user_nicename;
				$user_email=$item->to_email;
			}else{
				$user_fullname = $item->to_email;
				$item->user_email = $item->to_email;
			}			
		}

		if ( empty( $user_id ) ){
			$link = $item->primary_link;
		}elseif(function_exists('bp_core_get_user_domain')){
			$link = bp_core_get_user_domain( $user_id );
		}

		$defaults = array(
			'alt'     => __( 'Profile picture of %s', 'buddypress' ),
			'class'   => 'avatar',
			'email'   => $item->user_email,
			'type'    => 'thumb',
			'width'    => 20,
			'height'    => 20,
			'user_id' => $user_id,
			'item_id' => $user_id,
			'object' => 'user'
		);

		$r = wp_parse_args( $defaults );
		extract( $r, EXTR_SKIP );
		if(function_exists('bp_core_fetch_avatar')){
			$img = bp_core_fetch_avatar( array( 'item_id' => $item_id, 'object' => $object, 'type' => $type, 'alt' => $alt, 'class' => $class, 'width' => $width, 'height' => $height, 'email' => $email ) );
		}
		$user_item = '<a href="' . $link . '">' . $img . '</a><a href="' . $link . '">' . $user_fullname .'<br />'. $user_email. '</a>';

		return $user_item;
	}
}
?>