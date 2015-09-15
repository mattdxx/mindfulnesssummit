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
						<div id="speaker-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="gdlr-speaker-info-wrapper">
								<?php  echo gdlr_get_speaker_thumbnail('full'); ?>
								
								<div class="gdlr-speaker-info-inner">
									<h4 class="gdlr-speaker-name"><?php echo get_the_title(); ?></h4>
									<div class="gdlr-speaker-position"><?php echo gdlr_text_filter($gdlr_post_option['page-caption']); ?></div>
									
									<?php
										if( !empty($gdlr_post_option['speaker-social']) ){
											echo '<div class="gdlr-speaker-social" >';
											echo do_shortcode($gdlr_post_option['speaker-social']);
											echo '</div>';
										}
									
										echo gdlr_get_speaker_info(array('telephone', 'email', 'website'), $gdlr_post_option); 
									?>
								</div>								
							</div>								
							<div class="gdlr-speaker-content-wrapper">
								<h4 class="gdlr-speaker-biography-title"><?php echo __('Biography', 'gdlr-conference'); ?></h4>
								<div class="gdlr-speaker-content">
								<?php 
									the_content(); 
									wp_link_pages( array( 
										'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'gdlr-conference' ) . '</span>', 
										'after' => '</div>', 
										'link_before' => '<span>', 
										'link_after' => '</span>' ));
								?>
								</div>
								
								<?php 
									global $post;
									
									$args = array('post_type' => 'session', 'suppress_filters' => false);
									$args['posts_per_page'] = '9999';
									$args['meta_key'] = 'session-speaker';
									$args['orderby'] = 'meta_value';
									$args['order'] = 'asc';
									$args['meta_query'] = array(array(
										'key' => 'session-speaker',
										'value' => $post->post_name,
										'compare' => 'LIKE'
									));
									$query = new WP_Query( $args );		
									
									if( $query->have_posts() ){
										$count = 0;
									
										echo '<h4 class="gdlr-speaker-session-title">';
										echo sprintf(__('All session by %s', 'gdlr-conference'), get_the_title());
										echo '</h4>';
										
										echo '<div class="gdlr-speaker-session-wrapper" >';
										while($query->have_posts()){ $query->the_post(); $count++;
											echo ($count % 2 == 1 && $count != 1)? '<div class="clear"></div>': '';
										
											$gdlr_post_option = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
											$gdlr_post_option = json_decode($gdlr_post_option, true);
										
											echo '<div class="six columns">';
											echo '<div class="gdlr-speaker-session-item gdlr-item" >';
											echo '<h4 class="speaker-session-item-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
											echo gdlr_get_session_info(array('time', 'location'), $gdlr_post_option); 
											echo '</div>';
											echo '</div>';
										}
										echo '<div class="clear"></div>';
										echo '</div>';
										wp_reset_postdata();
									}
									//foreach($query);
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