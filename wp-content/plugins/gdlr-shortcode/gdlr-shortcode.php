<?php
/*
Plugin Name: Goodlayers Shortcode
Plugin URI: 
Description: A Shortcode Plugin To Use With Goodlayers Theme ( This plugin functionality might not working properly on another theme )
Version: 1.0
Author: Goodlayers
Author URI: http://www.goodlayers.com
License: 
*/

include_once( 'gdlr-shortcode-elements.php');	

// add filter to include the goodlayers shortcode to tinymce
add_action('init', 'gdlr_add_tinymce_button');
if( !function_exists('gdlr_add_tinymce_button') ){
	function gdlr_add_tinymce_button() {
		add_filter('mce_external_plugins', 'gdlr_register_tinymce_button_script');
		add_filter('mce_buttons', 'gdlr_register_tinymce_button');
	}
}

// register the script to tinymce
if( !function_exists('gdlr_register_tinymce_button_script') ){ 
	function gdlr_register_tinymce_button_script($plugin_array) {
		$plugin_array['goodlayers'] =	plugins_url('gdlr-shortcode.js', __FILE__ );
	 
		return $plugin_array;
	}
}

// add the button to tinymce
if( !function_exists('gdlr_register_tinymce_button') ){ 
	function gdlr_register_tinymce_button($buttons) {
		array_push($buttons, 'goodlayers');
		return $buttons;
	}
}

if( !function_exists('gdlr_fix_shortcodes') ){ 
	add_filter('the_content', 'gdlr_fix_shortcodes', 7);
	add_filter('gdlr_the_content', 'gdlr_fix_shortcodes', 7);
	function gdlr_fix_shortcodes($content){   
		global $shortcode_tags;
	 
		// Backup current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		remove_all_shortcodes();

		add_shortcode('gdlr_text_align', 'gdlr_text_align_shortcode');		
		add_shortcode('gdlr_button', 'gdlr_button_shortcode');
		add_shortcode('gdlr_heading', 'gdlr_heading_shortcode');
		add_shortcode('gdlr_divider', 'gdlr_divider_shortcode');
		add_shortcode('gdlr_circle_progress', 'gdlr_circle_progress_shortcode');
		add_shortcode('gdlr_stunning_text', 'gdlr_stunning_text_shortcode');
		add_shortcode('gdlr_skill_bar', 'gdlr_skill_bar_shortcode');
		add_shortcode('gdlr_row', 'gdlr_row_shortcode');
		add_shortcode('gdlr_column', 'gdlr_column_shortcode');
		add_shortcode('gdlr_frame', 'gdlr_frame_shortcode');
		add_shortcode('gdlr_image_link', 'gdlr_image_link_shortcode');
		add_shortcode('gdlr_space', 'gdlr_space_shortcode');
		add_shortcode('gdlr_quote', 'gdlr_quote_shortcode');
		add_shortcode('gdlr_dropcap', 'gdlr_dropcap_shortcode');
		add_shortcode('gdlr_icon', 'gdlr_icon_shortcode');
		add_shortcode('gdlr_notification', 'gdlr_notification_shortcode');
		add_shortcode('gdlr_box_icon', 'gdlr_box_icon_shortcode');
		add_shortcode('gdlr_styled_box', 'gdlr_styled_box_shortcode');
		add_shortcode('gdlr_blog', 'gdlr_blog_shortcode');
		add_shortcode('gdlr_portfolio', 'gdlr_portfolio_shortcode');
		add_shortcode('gdlr_code', 'gdlr_code_shortcode');
		
		// Do the shortcode (only the one above is registered)
		$content = do_shortcode($content);
	 
		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;
 
	    return $content;
    }
} 

add_action('admin_print_scripts', 'gdlr_print_shortcodes_variable');
if( !function_exists('gdlr_print_shortcodes_variable') ){ 
	function gdlr_print_shortcodes_variable(){
		?>
<script type="text/javascript">
var gdlr_shortcodes = [
{	title: 'Accordion', 
	value: '[gdlr_accordion style="style-1" initial="1"]<br>\
			[gdlr_tab title="ACCORDION_TITLE_1"]ACCORDION_CONTENT_1[/gdlr_tab]<br>\
			[gdlr_tab title="ACCORDION_TITLE_2"]ACCORDION_CONTENT_2[/gdlr_tab]<br>\
			[gdlr_tab title="ACCORDION_TITLE_3"]ACCORDION_CONTENT_3[/gdlr_tab]<br>\
			[/gdlr_accordion]<br>' 
},
{	title: 'Blog', 
	value: '[gdlr_blog category="CATEGORY_SLUG" num_fetch="8" num_excerpt="20" blog_style="blog-1-3" thumbnail_size="post-thumbnail-size" orderby="date" order="asc" pagination="enable" ]'
}, 
{	title: 'Box With Icon', 
	value: '[gdlr_box_icon icon="icon-gears" icon_color="#4984d5" icon_position="top" title="BOX_WITH_ICON_TITLE" ]BOX_WITH_ICON_CONTENT[/gdlr_box_icon]<br>\
			[gdlr_box_icon icon="icon-gears" icon_type="circle" icon_color="#ffffff" icon_background="#91d549" icon_position="top" title="BOX_WITH_ICON_TITLE" ]BOX_WITH_ICON_CONTENT[/gdlr_box_icon]'
},
{	title: 'Button', 
	value: '[gdlr_button href="http://www.goodlayers.com" target="_self" size="medium" background="#000000" color="#ffffff"]Click Me[/gdlr_button]<br>\
			[gdlr_button href="http://www.goodlayers.com" target="_self" size="medium" background="#000000" color="#ffffff" border_color="#999999"]Click Me[/gdlr_button]'
},
{	title: 'Circle Progress', 
	value: '[gdlr_circle_progress percent="50" size="155" line_width="8" progress_background_color="#e9e9e9" progress_color="#a9e16e"]CONTENT[/gdlr_circle_progress]'
},
{	title: 'Code', 
	value: '[gdlr_code title="CODE_TITLE" active="no"]CODE_CONTENT[/gdlr_code]'
},
{	title: 'Column', 
	value: '[gdlr_row]<br>\
			[gdlr_column size="1/3"]FIRST_COLUMN_TEXT[/gdlr_column]<br>\
			[gdlr_column size="2/3"]SECOND_COLUMN_TEXT[/gdlr_column]<br>\
			[/gdlr_row]'
},
{	title: 'Divider', 
	value: '[gdlr_divider type="solid" size="50%" ]'
},
{	title: 'Drop Cap', 
	value: '[gdlr_dropcap type="circle" color="#ffffff" background="#555555"]S[/gdlr_dropcap]'
}, 
{	title: 'Frame', 
	value: '[gdlr_frame type="border" align="left" caption="YOUR_CAPTION_GOES_HERE"][gdlr_image_link type="image" image_url="" link_url="" alt=""][/gdlr_frame]'
},
{	title: 'Gallery', 
	value: '[gallery ids="0" columns="4" link="file" caption="yes" thumbnail_size="thumbnail" ]'
}, 
{	title: 'Heading Tag', 
	value: '[gdlr_heading tag="h2" icon="icon-heart" font_weight="bold" ]HEADER_TEXT[/gdlr_heading]<br>\
			[gdlr_heading tag="h2" size="40px" color="#ffffff" background="#91d549" ]HEADER_TEXT[/gdlr_heading]'
},
{	title: 'Icon', 
	value: '[gdlr_icon type="icon-adjust" color="#353535" size="15px" ]'
}, 
{	title: 'Image Link', 
	value: '[gdlr_image_link type="image" image_url="" link_url="" alt="" target="_blank"]'
},
{	title: 'Location', 
	value: '[gdlr_location phone="" email="" location="" title="" ]CONTENT_HERE[/gdlr_location]'
},
{	title: 'Notification', 
	value: '[gdlr_notification icon="icon-flag" type="color-background" background="#99d15e" color="#ffffff"]NOTIFICATION_TEXT[/gdlr_notification]<br>\
			[gdlr_notification icon="icon-flag" type="color-border" border="#99d15e" color="#000000"]NOTIFICATION_TEXT[/gdlr_notification]'
},
{	title: 'Personnel', 
	value: '[gdlr_personnel columns="3" type="static" style="box-style"]<br>\
			[gdlr_tab title="AUTHOR_NAME" position="POSITION" author_image="IMAGE_URL" ]TESTIMONIAL_CONTENT[/gdlr_tab]<br>\
			[gdlr_tab title="AUTHOR_NAME" position="POSITION" author_image="IMAGE_URL" ]TESTIMONIAL_CONTENT[/gdlr_tab]<br>\
			[/gdlr_personnel]'
},
{	title: 'Portfolio', 
	value: '[gdlr_portfolio category="CATEGORY_SLUG" num_fetch="8" num_excerpt="20" portfolio_style="classic-portfolio" portfolio_size="1/3" thumbnail_size="post-thumbnail-size" orderby="date" order="asc" pagination="enable" ]'
}, 
{	title: 'Page', 
	value: '[gdlr_page category="CATEGORY_SLUG" num_fetch="8" page_style="classic" item_size="1/3" thumbnail_size="post-thumbnail-size" pagination="enable" ]'
}, 
{	title: 'Post Slider', 
	value: '[gdlr_post_slider category="CATEGORY_SLUG" num_fetch="4" num_excerpt="25" caption_position="bottom" thumbnail_size="medium" orderby="date" order="asc" ]'
}, 
{	title: 'Price Table', 
	value: '[gdlr_price_table columns="3" ]<br>\
			[gdlr_tab title="PRICE_TITLE" price="PRICE" link="#" ]<ul><li>LIST 1</li><li>LIST 2</li><li>LIST 3</li></ul>[/gdlr_tab]<br>\
			[gdlr_tab title="PRICE_TITLE" price="PRICE" link="#" active="yes" ]<ul><li>LIST 1</li><li>LIST 2</li><li>LIST 3</li></ul>[/gdlr_tab]<br>\
			[gdlr_tab title="PRICE_TITLE" price="PRICE" link="#" ]<ul><li>LIST 1</li><li>LIST 2</li><li>LIST 3</li></ul>[/gdlr_tab]<br>\
			[/gdlr_price_table]'
},
{	title: 'Process', 
	value: '[gdlr_process min_height="200px" type="vertical" ]<br>\
			[gdlr_tab icon="icon-rss" title="PROCESS_TITLE" ]PROCESS_CONTENT[/gdlr_tab]<br>\
			[gdlr_tab icon="icon-rss" title="PROCESS_TITLE" ]PROCESS_CONTENT[/gdlr_tab]<br>\
			[gdlr_tab icon="icon-rss" title="PROCESS_TITLE" ]PROCESS_CONTENT[/gdlr_tab]<br>\
			[/gdlr_process]'
},
{	title: 'Quote', 
	value: '[gdlr_quote align="center" ]CONTENT_GOES_HERE[/gdlr_quote]'
}, 
{	title: 'Skill Bar', 
	value: '[gdlr_skill_bar percent="50" size="medium" text_color="#ffffff" background_color="#e9e9e9" progress_color="#a9e16e"]SKILL_DESCRIPTION[/gdlr_skill_bar]'
},
{	title: 'Slider', 
	value: '[gallery ids="0" columns="4" link="file" caption="yes" thumbnail_size="thumbnail" type="slider" caption_position="right" ]'
},
{	title: 'Space', 
	value: '[gdlr_space height="20px"]'
},
{	title: 'Stunning Text', 
	value: '[gdlr_stunning_text background_color="#f3f3f3" button="BUTTON_TEXT" button_link="#" button_background="#94d64f" button_text_color="#ffffff" button_border_color="#6fac2f" title="STUNNING_TEXT_TITLE" title_color="#94d64f" caption_color="#a0a0a0"]STUNNING_TEXT_CONTENT[/gdlr_stunning_text]'
},
{	title: 'Styled Box', 
	value: '[gdlr_styled_box content_color="#ffffff" background_color="#9ada55" corner_color="#3d6817" ]STYLE_BOX_CONTENT[/gdlr_styled_box]<br>\
			[gdlr_styled_box content_color="#000000" background_type="image" background_image="YOUR_IMAGE_URL" ]STYLE_BOX_CONTENT[/gdlr_styled_box]'
},
{	title: 'Table', 
	value: '<table class="style-1">\
			<tr><th>HEADER_1</th><th>HEADER_2</th><th>HEADER_3</th><th>HEADER_4</th></tr>\
			<tr><td>COLUMN_1</td><td>COLUMN_2</td><td>COLUMN_3</td><td>COLUMN_4</td></tr>\
			<tr><td>COLUMN_1</td><td>COLUMN_2</td><td>COLUMN_3</td><td>COLUMN_4</td></tr>\
			</table>'
},
{	title: 'Social Icon', 
	value: '[gdlr_social type="facebook" ]URL_HERE[/gdlr_social]'
},
{	title: 'Tabs', 
	value: '[gdlr_tabs style="horizontal" initial="1"]<br>\
			[gdlr_tab title="TABS_TITLE_1"]TABS_CONTENT_1[/gdlr_tab]<br>\
			[gdlr_tab title="TABS_TITLE_2"]TABS_CONTENT_2[/gdlr_tab]<br>\
			[gdlr_tab title="TABS_TITLE_3"]TABS_CONTENT_3[/gdlr_tab]<br>\
			[/gdlr_tabs]<br>'
},
{	title: 'Text Align', 
	value: '[gdlr_text_align class="center" ]CONTENT_HERE[/gdlr_text_align]'
},
{	title: 'Testimonial', 
	value: '[gdlr_testimonial columns="3" type="static" style="box-style"]<br>\
			[gdlr_tab title="AUTHOR_NAME" position="POSITION" author_image="IMAGE_URL" ]TESTIMONIAL_CONTENT[/gdlr_tab]<br>\
			[gdlr_tab title="AUTHOR_NAME" position="POSITION" author_image="IMAGE_URL" ]TESTIMONIAL_CONTENT[/gdlr_tab]<br>\
			[/gdlr_testimonial]'
},
{	title: 'Title', 
	value: '[gdlr_title align="left" style="divider" title="TITLE_HERE" ]CAPTION_HERE[/gdlr_title]'
},
{	title: 'Toggle Box', 
	value: '[gdlr_toggle_box style="style-1" ]<br>\
			[gdlr_tab title="TOGGLE_BOX_TITLE_1" active="yes"]TOGGLE_BOX_CONTENT_1[/gdlr_tab]<br>\
			[gdlr_tab title="TOGGLE_BOX_TITLE_2"]TOGGLE_BOX_CONTENT_2[/gdlr_tab]<br>\
			[gdlr_tab title="TOGGLE_BOX_TITLE_3"]TOGGLE_BOX_CONTENT_3[/gdlr_tab]<br>\
			[/gdlr_toggle_box]<br>'
},
{	title: 'Video', 
	value: '[gdlr_video url="YOUR_YOUTUBE/VIMEO_URL" ]'
},
{	title: 'Lightbox Button', 
	value: '[gdlr_lb_button title="Book Now" text_color="#ffffff" background="#333333" border="#000000" ]SHORTCODE/CONTENT GOES HERE[/gdlr_lb_button]'
}
];
</script>
		<?php
	}	
}
 
?>