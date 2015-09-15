<?php
global $wpdb;
/** MySQL Database Table SQL **/
if(is_admin() && $_GET['activate']==true && $_GET['plugin_status']=='all')
{
	$tablename = My_Log_Entry::LOG_TABLE;
	$table_sql = "CREATE TABLE IF NOT EXISTS `$tablename` (
	  `id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `user_id` bigint(20) NOT NULL,
	  `component` varchar(75) NOT NULL,
	  `type` varchar(75) NOT NULL,
	  `action` text NOT NULL,
	  `content` longtext NOT NULL,
	  `primary_link` varchar(150) NOT NULL,
	  `item_id` varchar(75) NOT NULL,
	  `secondary_item_id` varchar(75) DEFAULT NULL,
	  `date_recorded` datetime NOT NULL,
	  `hide_sitewide` tinyint(1) DEFAULT '0',
	  `mptt_left` int(11) NOT NULL DEFAULT '0',
	  `mptt_right` int(11) NOT NULL DEFAULT '0',
	  `from_email` varchar(200) NOT NULL,
	  `to_email` varchar(200) NOT NULL,
	  PRIMARY KEY (`id`),
	  KEY `date_recorded` (`date_recorded`),
	  KEY `user_id` (`user_id`),
	  KEY `item_id` (`item_id`),
	  KEY `secondary_item_id` (`secondary_item_id`),
	  KEY `component` (`component`),
	  KEY `type` (`type`),
	  KEY `mptt_left` (`mptt_left`),
	  KEY `mptt_right` (`mptt_right`),
	  KEY `hide_sitewide` (`hide_sitewide`)
	)";
	$wpdb->query($table_sql);
}
//ALTER TABLE `ask_log` ADD `from_email` VARCHAR( 200 ) NOT NULL ,ADD `to_email` VARCHAR( 200 ) NOT NULL 

/** Main Function Filter **/
add_filter( 'wp_mail', 'aheadzen_wp_mail_filter' );
function aheadzen_wp_mail_filter( $args ) {
	global $wpdb;
	$debug_trace=debug_backtrace();
	if($debug_trace)
	{
		foreach($debug_trace as $key=>$val)
		{
			if(strstr($val['function'],'wp_mail'))
			{
				$type = $function = $debug_trace[$key+1]['function'];
				if(is_admin()){
					$file = $debug_trace[$key]['file'];
				}else{
					$file = $debug_trace[$key+1]['file'];
				}
				if(!$file){$file = $debug_trace[$key]['file'];}
				
				$file_arr = explode('\\',$file);
				if(count($file_arr)<=1)
				{
					$file_arr = explode('/',$file);
				}
				
				$content_key = array_search('wp-content', $file_arr);
				$includes_key = array_search('wp-includes', $file_arr);
				if($content_key){
					$component = $file_arr[$content_key+2]; //component name
				}elseif($includes_key)
				{
					$component = $file_arr[$includes_key+2]; //component name
					if(!$component){
						$file_arr = explode('\\', $val['file']);
						$content_key = array_search('wp-content', $file_arr);
						$component = $file_arr[$content_key+2]; break;
					}else{
						$component = $file_arr[$content_key+2]; //component name
					}
					
				}
				
				$headers = $args['headers'];
				$headers_arr1 = explode('From:',$headers);
				$from_data = trim($headers_arr1[1]);
				$str=strpos($from_data,'<')+1;
				$end=strpos($from_data,'>')-$str;
				$from_email = substr($from_data,$str,$end);
				$emails_arr = array();
				if($from_email){
					$user_email_ids = '"'.$from_email.'","'.$args['to'].'"';
					$res = $wpdb->get_results("select ID,user_email from $wpdb->users where user_email in ($user_email_ids)");
					foreach($res as $resobj){
						$emails_arr[$resobj->user_email]=$resobj->ID;
					}
				}
				$from_user_id = $emails_arr[$from_email];
				$to_user_id = $emails_arr[$args['to']];
				if(!$from_user_id){global $current_user; $from_user_id = $current_user->ID;}
				
				$args = array(
						'user_id' 	=> $from_user_id,
						'from_email'=> $from_email,
						'item_id' 	=> $to_user_id,
						'to_email' 	=> $args['to'],
						'component' => $component,
						'type'		=> $type,
						'action' 	=> $args['subject'],
						'content' 	=> $args['message']
					);
				$theid = my_log_add($args);
			}			
		}
	}
	return $args;
}