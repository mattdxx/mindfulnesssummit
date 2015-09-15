<?php 
	global $theme_option;

	echo '<div class="gdlr-navigation-wrapper">';

	// navigation
	if( has_nav_menu('main_menu') ){
		if( class_exists('gdlr_menu_walker') ){
			echo '<nav class="gdlr-navigation" id="gdlr-main-navigation" role="navigation">';
			wp_nav_menu( array(
				'theme_location'=>'main_menu', 
				'container'=> '', 
				'menu_class'=> 'sf-menu gdlr-main-menu',
				'walker'=> new gdlr_menu_walker() 
			) );
		}else{
			echo '<nav class="gdlr-navigation" role="navigation">';
			wp_nav_menu( array('theme_location'=>'main_menu') );
		}
		//gdlr_get_woocommerce_nav();
		echo '</nav>'; // gdlr-navigation
	}
?>	
<span class="gdlr-menu-search-button-sep">|</span>
<i class="fa fa-search icon-search gdlr-menu-search-button" id="gdlr-menu-search-button" ></i>
<div class="gdlr-menu-search" id="gdlr-menu-search">
	<form method="get" id="searchform" action="<?php  echo home_url(); ?>/">
		<?php
			$search_val = get_search_query();
			if( empty($search_val) ){
				$search_val = __("Type Keywords" , "gdl_front_end");
			}
		?>
		<div class="search-text">
			<input type="text" value="<?php echo esc_attr($search_val); ?>" name="s" autocomplete="off" data-default="<?php echo esc_attr($search_val); ?>" />
		</div>
		<input type="submit" value="" />
		<div class="clear"></div>
	</form>	
</div>		
<?php	
	echo '<div class="gdlr-navigation-gimmick" id="gdlr-navigation-gimmick"></div>';
	echo '<div class="clear"></div>';
	echo '</div>'; // gdlr-navigation-wrapper
?>