<?php 
	get_header(); 
	
	while( have_posts() ){ the_post();
?>
<div class="gdlr-content">

	<?php 
		global $gdlr_sidebar, $theme_option, $gdlr_post_option, $gdlr_is_ajax;

		$gdlr_sidebar = gdlr_get_sidebar_class(array(
			'type'=>'no-sidebar',
			'left-sidebar'=>'', 
			'right-sidebar'=>''
		));
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container gdlr-class-<?php echo $gdlr_sidebar['type']; ?>">
			<div class="with-sidebar-left <?php echo $gdlr_sidebar['outer']; ?> columns">
				<div class="with-sidebar-content <?php echo $gdlr_sidebar['center']; ?> columns">
					<div class="gdlr-item gdlr-item-start-content">
						<div id="ticket-<?php the_ID(); ?>" <?php post_class(); ?>>
							<h2><strong><?php _e('This post type has no single page, you can create it using page builder item.' ,'gdlr-conference') ?></strong></h2>
							<h6><?php _e('This page will showing up as 404 not found page to logged out user.' ,'gdlr-conference') ?></h6>
							<div class="clear" style="height: 35px; "></div>
						</div><!-- #ticket -->
					</div><!-- gdlr-item-start-content -->
				</div>
				<?php get_sidebar('left'); ?>
				<div class="clear"></div>
			</div>
			<?php get_sidebar('right'); ?>
			<div class="clear"></div>
		</div>				
	</div>				

</div><!-- gdlr-content -->
<?php
	}
	
	get_footer(); 
?>