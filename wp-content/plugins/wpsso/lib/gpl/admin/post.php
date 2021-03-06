<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2015 - Jean-Sebastien Morisset - http://surniaulula.com/
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoGplAdminPost' ) ) {

	class WpssoGplAdminPost {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->util->add_plugin_filters( $this, array( 
				'post_header_rows' => 3,
				'post_media_rows' => 3,
			) );
		}

		public function filter_post_header_rows( $rows, $form, $head_info ) {
			$post_status = get_post_status( $head_info['post_id'] );
			$post_type = get_post_type( $head_info['post_id'] );

			$rows[] = '<td colspan="2" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] = $this->p->util->get_th( 'Article Topic', 'medium', 'post-og_art_section', $head_info ).
			'<td class="blank">'.$this->p->options['og_art_section'].'</td>';

			if ( $post_status == 'auto-draft' )
				$rows[] = $this->p->util->get_th( 'Default Title', 'medium', 'meta-og_title', $head_info ). 
				'<td class="blank"><em>Save a draft version or publish the '.
					$head_info['ptn'].' to update and display this value.</em></td>';
			else
				$rows[] = $this->p->util->get_th( 'Default Title', 'medium', 'meta-og_title', $head_info ). 
				'<td class="blank">'.$this->p->webpage->get_title( $this->p->options['og_title_len'],
					'...', true, true, false, true, 'none' ).'</td>';	// $use_post = true, $md_idx = 'none'
		
			if ( $post_status == 'auto-draft' )
				$rows[] = $this->p->util->get_th( 'Default (Facebook / Open Graph, LinkedIn, 
					Pinterest Rich Pin) Description', 'medium', 'post-og_desc', $head_info ).
				'<td class="blank"><em>Save a draft version or publish the '.
					$head_info['ptn'].' to update and display this value.</em></td>';
			else
				$rows[] = $this->p->util->get_th( 'Default (Facebook / Open Graph, LinkedIn, 
					Pinterest Rich Pin) Description', 'medium', 'post-og_desc', $head_info ).
				'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['og_desc_len'],
					'...', true, true, true, true, 'none' ).'</td>';	// $use_post = true, $md_idx = 'none'
	
			if ( $post_status == 'auto-draft' )
				$rows[] = '<tr class="hide_in_basic">'.
				$this->p->util->get_th( 'Google+ / Schema Description', 'medium', 'meta-schema_desc', $head_info ).
				'<td class="blank"><em>Save a draft version or publish the '.
					$head_info['ptn'].' to update and display this value.</em></td>';
			else
				$rows[] = '<tr class="hide_in_basic">'.
				$this->p->util->get_th( 'Google+ / Schema Description', 'medium', 'meta-schema_desc', $head_info ).
				'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['schema_desc_len'], 
					'...', true ).'</td>';
	
			if ( $post_status == 'auto-draft' )
				$rows[] = $this->p->util->get_th( 'Google Search / SEO Description', 'medium', 'meta-seo_desc', $head_info ).
				'<td class="blank"><em>Save a draft version or publish the '.
					$head_info['ptn'].' to update and display this value.</em></td>';
			else
				$rows[] = $this->p->util->get_th( 'Google Search / SEO Description', 'medium', 'meta-seo_desc', $head_info ).
				'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['seo_desc_len'], 
					'...', true, true, false ).'</td>';			// $add_hashtags = false

			if ( $post_status == 'auto-draft' )
				$rows[] = $this->p->util->get_th( 'Twitter Card Description', 'medium', 'meta-tc_desc', $head_info ).
				'<td class="blank"><em>Save a draft version or publish the '.
					$head_info['ptn'].' to update and display this value.</em></td>';
			else
				$rows[] = $this->p->util->get_th( 'Twitter Card Description', 'medium', 'meta-tc_desc', $head_info ).
				'<td class="blank">'.$this->p->webpage->get_description( $this->p->options['tc_desc_len'],
					'...', true ).'</td>';

			if ( $post_status == 'publish' || $post_type == 'attachment' )
				$rows[] = '<tr class="hide_in_basic">'.
				$this->p->util->get_th( 'Sharing URL', 'medium', 'meta-sharing_url', $head_info ).
				'<td class="blank">'.$this->p->util->get_sharing_url( true ).'</td>';
			else
				$rows[] = '<tr class="hide_in_basic">'.
				$this->p->util->get_th( 'Sharing URL', 'medium', 'meta-sharing_url', $head_info ).
				'<td class="blank"><em>The Sharing URL permalink will be available when the '.
					$head_info['ptn'].' is published.</em></td>';

			return $rows;
		}

		public function filter_post_media_rows( $rows, $form, $head_info ) {

			$rows[] = '<td colspan="2" align="center">'.
				$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

			$rows[] = '<td colspan="2" class="subsection"><h4 
				style="margin-top:0;">All Social Websites / Open Graph</h4></td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( 'Image Dimensions', 'medium', 'og_img_dimensions' ).
			'<td class="blank">'.$form->get_image_dimensions_text( 'og_img', true ).'</td>';

			$rows[] = $this->p->util->get_th( 'Image ID', 'medium', 'meta-og_img_id', $head_info ).
			'<td class="blank">&nbsp;</td>';

			$rows[] = $this->p->util->get_th( 'or Image URL', 'medium', 'meta-og_img_url', $head_info ).
			'<td class="blank">&nbsp;</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( 'Maximum Images', 'medium', 'meta-og_img_max', $head_info ).
			'<td class="blank">'.$this->p->options['og_img_max'].'</td>';

			$rows[] = $this->p->util->get_th( 'Video Embed HTML', 'medium', 'meta-og_vid_embed', $head_info ).
			'<td class="blank">&nbsp;</td>';

			$rows[] = $this->p->util->get_th( 'or Video URL', 'medium', 'meta-og_vid_url', $head_info ).
			'<td class="blank">&nbsp;</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( 'Maximum Videos', 'medium', 'meta-og_vid_max', $head_info ).
			'<td class="blank">'.$this->p->options['og_vid_max'].'</td>';

			$rows[] = $this->p->util->get_th( 'Include Preview Image(s)', 'medium', 'meta-og_vid_prev_img', $head_info ).
			'<td class="blank">'.$form->get_no_checkbox( 'og_vid_prev_img' ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			'<td colspan="2" class="subsection"><h4>Pinterest (Rich Pin)</h4></td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( 'Image Dimensions', 'medium', 'rp_img_dimensions' ).
			'<td class="blank">'.$form->get_image_dimensions_text( 'rp_img', true ).'</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( 'Image ID', 'medium', 'meta-rp_img_id', $head_info ).
			'<td class="blank">&nbsp;</td>';

			$rows[] = '<tr class="hide_in_basic">'.
			$this->p->util->get_th( 'or Image URL', 'medium', 'meta-rp_img_url', $head_info ).
			'<td class="blank">&nbsp;</td>';

			return $rows;
		}
	}
}

?>
