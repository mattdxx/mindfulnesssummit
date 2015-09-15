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
						<div id="session-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="gdlr-session-info-wrapper">
								<?php 
									$gdlr_speakers = gdlr_get_session_speaker_list($gdlr_post_option['session-speaker']);
									foreach($gdlr_speakers as $gdlr_speaker){
										echo gdlr_get_speaker_thumbnail('full', $gdlr_speaker->ID, array(), true, false); 
									}
									
									echo gdlr_get_session_info(array('date', 'time', 'location', 'speaker', 'document'), $gdlr_post_option, $gdlr_speakers); 
								?>							
							</div>								
							<div class="gdlr-session-content">
								<?php 
									echo gdlr_get_session_thumbnail('full'); 
									
									echo '<h4 class="gdlr-session-title">' . get_the_title() . '</h4>';
									
									the_content(); 
									wp_link_pages( array( 
										'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'gdlr-conference' ) . '</span>', 
										'after' => '</div>', 
										'link_before' => '<span>', 
										'link_after' => '</span>' ));
								?>
							</div>			
							<div class="clear"></div>
						</div><!-- #speaker -->
						
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