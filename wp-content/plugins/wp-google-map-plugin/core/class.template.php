<?php
/**
 * Generate Bootstrap Form and it's Elements.
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package Maps
 */

if ( ! class_exists( 'Responsive_Markup' ) ) {

	/**
	 * Generate Bootstrap Form and it's Elements.
	 * @author Flipper Code <hello@flippercode.com>
	 * @version 3.0.0
	 * @package Maps
	 */
	class Responsive_Markup {
		/**
		 * Form Title
		 * @var String
		 */
		protected $form_title = null;
		/**
		 * Form Name
		 * @var String
		 */
		public $form_name = null;
		/**
		 * Form ID
		 * @var String
		 */
		public $form_id = null;
		/**
		 * Form Action
		 * @var String
		 */
		public $form_action = '';
		/**
		 * Form Orientation - Vertical or Horizontal
		 * @var String
		 */
		public $form_type = 'form-horizontal';
		/**
		 * Call to Action Slug
		 * @var String
		 */
		protected $manage_pagename = null;
		/**
		 * Call to Action Title
		 * @var String
		 */
		protected $manage_pagetitle = null;
		/**
		 * Success or Failure Form Response
		 * @var Array
		 */
		protected $form_response = null;
		/**
		 * Form Method - POST or GET
		 * @var string
		 */
		protected $form_method = 'post';
		/**
		 * Bootstrap Elements Supported
		 * @var Array
		 */
		private $form_elements = array( 'text','checkbox','radio','submit','button','select', 'hidden', 'wp_editor', 'html', 'datalist','textarea' , 'file' , 'div' , 'blockquote','html' , 'image','group','table', 'message', 'anchor', 'image_picker' );
		/**
		 * Attributes Allowed
		 * @var Array
		 */
		private $allowed_attributes;
		/**
		 * Hidden Fields
		 * @var Array
		 */
		private $form_hiddens = array();
		/**
		 * Form nonce key.
		 * @var string
		 */
		private $nonce_key = 'wpgmp-nonce';
		/**
		 * Array of Bootstrap Elements
		 * @var Array
		 */
		protected $elements = array();
		/**
		 * Array of Previously Stored Elements
		 * @var Array
		 */
		protected $backup_elements = array();
		/**
		 * Array of Rendered Elements
		 * @var Array
		 */
		protected $partially_rendered = false;
		/**
		 * Number of bootstrap columns
		 * @var Int
		 */
		/**
		 * Whether setting api enabled or not.
		 * @var boolean
		 */
		public $setting_api = false;
		/**
		 * Columns in row.
		 * @var integer
		 */
		protected $columns = 1;
		/**
		 * Intialize form properties.
		 */
		public function __construct( ) {

			$this->allowed_attributes = array_fill_keys( array( 'lable', 'id', 'class', 'required', 'default_value', 'value', 'options', 'desc', 'before', 'after', 'radio-val-label', 'onclick', 'placeholder', 'textarea_rows', 'textarea_name', 'html', 'current', 'width', 'height', 'src', 'alt', 'heading', 'data', 'show' ) , '' );
			$this->allowed_attributes['style'] = array();
			$this->allowed_attributes['required'] = false;

		}
		/**
		 * Set Form's header
		 * @param String $form_title       Form Title.
		 * @param String $response         Success or Failure Message.
		 * @param string $manage_pagetitle Call to Action Title.
		 * @param string $manage_pagename  Call to Action Page Slug.
		 */
		public function set_header( $form_title, $response, $manage_pagetitle = '', $manage_pagename = '' ) {
			if ( isset( $form_title ) && ! empty( $form_title ) ) {
				$this->form_title = $form_title; }
			if ( isset( $response ) && ! empty( $response ) ) {
				$this->form_response = $response; }
			$this->manage_pagename = $manage_pagename;
			$this->manage_pagetitle = $manage_pagetitle;
		}

		/**
		 * Form Method
		 * @param string $method Form Method.
		 */
		public function set_form_method($method) {
			$this->form_method = $method;
		}
		/**
		 * Title Setter
		 * @param string $title Form Title.
		 */
		public function set_title($title) {
			$this->form_title = $title;
		}
		/**
		 * Action Setter
		 * @param String $action Form Action.
		 */
		public function set_form_action($action) {
			$this->form_action = $action;
		}
		/**
		 * Title Getter
		 * @return String Get Form Title.
		 */
		public function get_title() {
			if ( isset( $this->form_title ) && ! empty( $this->form_title ) ) {
				return $this->form_title; }
		}
		/**
		 * Call to Action Button
		 */
		public function get_manage_url() {
			return "&nbsp&nbsp ( <a href='http://codecanyon.net/item/advanced-google-maps-plugin-for-wordpress/5211638'>Download Pro Version</a> )";
		}
		/**
		 * Get form success or error message.
		 * @return HTML Success or error message html.
		 */
		public function get_form_messages() {

			if ( empty( $this->form_response ) && ! is_array( $this->form_response ) ) {
				return; }
			$response = $this->form_response;
			$output = '';
			if ( $response['error'] ) {

				$output .= '<div class="col-md-11 alert alert-danger fade in">';
				$output .= ''.$response['error'].'</div>';
			} else {

				$output .= '<div class="col-md-11 alert alert-success fade in">';
				$output .= ''.$response['success'].'</div>';
			}
			return $output;
		}
		/**
		 * Form header getter.
		 * @return HTML  Generate form header html.
		 */
		public function get_header() {

			$output = '<div class="container">
						<div class="row">
						<div class="col-md-12">
						<h4 class="alert alert-info">'.$this->get_title().$this->get_manage_url().'</h4>
						<div class="wpgmp-overview">'.
						$this->get_form_messages();
			return apply_filters( 'wpgmp_form_header_html', $output );
		}
		/**
		 * Form footer getter.
		 * @return HTML Generate form footer html.
		 */
		public function get_footer( ) {
			$output = '</div>
						</div>
						</div>
						<a href="http://www.flippercode.com/forums" target="_blank" title="Ask Question" class="helpdask-bootom">Helpdesk ?</a>
						</div>';
			return apply_filters( 'wpgmp_form_footer_html', $output );
		}
		/**
		 * Bootstrap columns setter.
		 * @param int $column Set columns occupied by element.
		 */
		public function set_col($column) {
			if ( $this->elements ) {
				$last_index = key( array_reverse( $this->elements ) );
				$this->elements[ $last_index ]['col_after'] = $column;
				return;
			}
			$this->columns = $column ? absint( $column ) : 2;
		}
		/**
		 * Bootstrap columns getter.
		 */
		public function get_col() {
			return $this->columns;
		}
		/**
		 * Add element in queue.
		 * @param string $type Element type.
		 * @param string $name Element name.
		 * @param array  $args Element Properties.
		 */
		public function add_element( $type, $name, $args = array() ) {
			if ( ! in_array( $type, $this->form_elements ) ) {
				return; }

			$this->elements[ $name ] = shortcode_atts( $this->allowed_attributes, $args );
			$this->elements[ $name ]['type'] = $type;

		}
		/**
		 * Display bootstrap elements.
		 * @param  boolean $echo Echo or not.
		 * @return HTML    Return form's element.
		 */
		public function print_elements($echo = true) {

			if ( empty( $this->backup_elements ) ) {

				$form_output = $this->get_header();
				$form_output .= $this->get_form_header();
				$this->partially_rendered = true;
			}

			$element_html = $this->get_combined_markup();
			$form_output .= $element_html;
			$this->backup_elements[] = $this->elements;
			unset( $this->elements );
			if ( $echo ) {
				esc_html_e( balanceTags( $form_output ) );
			} else { 			return balanceTags( esc_html( $form_output ) ); }
		}
		/**
		 * Concat form elements together.
		 * @return html  Combined HTML of each elements.
		 */
		public function get_combined_markup() {

			$element_html = '';
			if ( $this->elements ) {
				$elements  = $this->elements;
				$before = apply_filters( 'wpgmp_element_before_start_row', '<div class="form-group {modifier}">' );
				$after = apply_filters( 'wpgmp_element_after_end_row', '</div>' );
				$num = 0;
				while ( $num < count( $elements ) ) {
					$col = $this->get_col();
					$elem_content = '';

					foreach ( array_slice( $elements, $num, $col ) as $name => $atts ) {
						$row_extra = false;
						$temp = $before;
						if ( ! isset( $atts['type'] ) || ! is_string( $name ) ) {
							continue; }

						if ( 'hidden' == $atts['type'] ) {

							$elem_content .= call_user_func( 'Responsive_Markup::field_'.$atts['type'], $name, $atts );
							continue;
						}

						$elem_content .= $this->get_element_html( $name, $atts['type'], $atts );

						if ( isset( $atts['col_after'] ) ) {
							$this->columns = $atts['col_after']; }
						if ( isset( $atts['show'] ) and  'false' == $atts['show'] ) {
							$row_extra = true; }
					}
					if ( true == $row_extra ) {
						$temp = str_replace( '{modifier}', 'hiderow', $temp );
					} else {
						$temp = str_replace( '{modifier}', '', $temp );
					}
					if ( ! empty( $elem_content ) ) {
						$element_html .= $temp.$elem_content.$after; }
					$num = $num + $col;
				}
			}
			return $element_html;

		}
		/**
		 * Form header getter.
		 * @return html Generate form header html.
		 */
		public function get_form_header() {

			$form_header = '<form enctype="multipart/form-data" method="'.$this->form_method.'" action="'.$this->form_action.'"';
			if ( isset( $this->form_name ) && ! empty( $this->form_name ) ) {
				$form_header .= ' name="'.$this->form_name.'" '; }
			if ( isset( $this->form_id ) && ! empty( $this->form_id ) ) {
				$form_header .= ' id="'.$this->form_id.'" '; }
			$form_header .= '>';
			$form_header .= '<div class="'.$this->form_type.'">';
			return $form_header;

		}
		/**
		 * Form nonce key setter.
		 * @param string $nonce_key Form nonce key.
		 */
		public function set_form_nonce($nonce_key) {
			$this->nonce_key = $nonce_key;
		}
		/**
		 * Form footer getter.
		 * @return html Generate form footer html.
		 */
		public function get_form_footer() {

			$form_footer = '</div>';
			$form_footer .= wp_nonce_field( $this->nonce_key,'_wpnonce',true,false );
			$form_footer .= '</form>';
			return $form_footer;
		}
		/**
		 * Echo or return html elements.
		 * @param  boolean $echo  True to display.
		 * @return html    html Generate form html
		 */
		public function render( $echo = true) {

			if ( ! $this->elements || ! is_array( $this->elements ) and $this->partially_rendered == false ) {
				echo '<div id="message" class="error"><p>Please add form element first.</p></div>';
				return;
			}

			$form_output = '';
			if ( empty( $this->backup_elements ) ) {
				$form_output = $this->get_header();
				$form_output .= $this->get_form_header();
			}

			$element_html = $this->get_combined_markup();
			$form_output .= $element_html;

			$form_output .= $this->get_form_footer();
			$form_output .= $this->get_footer();

			if ( $echo ) {
				echo balanceTags( $form_output );
			} else { 		  return $form_output; }
		}
		/**
		 * Element's html creater.
		 * @param  string $name Element Name.
		 * @param  string $type Element Type.
		 * @param  array  $atts  Element Options.
		 * @return html       Element's Html.
		 */
		public static function get_element_html( $name, $type, $atts ) {

			$element_output = '';
			if ( 'hidden' == $type ) {

				$element_output = call_user_func( 'Responsive_Markup::field_'.$type, $name, $atts );
				return $element_output;

			} else {

				if ( ! empty( $atts['lable'] ) ) {
					$element_output .= apply_filters( 'wpgmp_input_label_'.$name, '<div class="col-md-3"><label for="'.$name.'">'.$atts['lable'].'</label>'.self::element_mandatory( @$atts['required'] ).'</div>' ); }
				$element_output .= @$atts['before'] ? @$atts['before'] : '<div class="col-md-8">';
				$element_output .= call_user_func( 'Responsive_Markup::field_'.$type, $name, $atts );
				$element_output .= @$atts['after'] ? @$atts['after'] : '</div>';
				return $element_output;
			}

		}
		/**
		 * Display mandatory indicator on element.
		 * @param  boolean $required Whether field is required or not.
		 * @return html            Mandatory indicator.
		 */
		public static function element_mandatory( $required = false ) {

			if ( true == $required ) {
				return '<span style="color:#F00;">*</span>'; }
		}
		/**
		 * Attributes Generator for the element.
		 * @param  array $atts Attributes keys and values.
		 * @return string      Attributes section of the element.
		 */
		protected static function get_element_attributes($atts) {
			if ( ! is_array( $atts ) ) {
				return null; }

			$attributes = array();
			if ( isset( $atts['id'] ) && ! empty( $atts['id'] ) ) {
				$attributes[0] = 'id="'.$atts['id'].'"'; }
			$classes = ( ! empty( $atts['class'] )) ? $atts['class'] : 'form-control';
			$attributes[1] = 'class="'.$classes.'"';
			if ( isset( $atts['style'] ) && ! empty( $atts['style'] ) ) {
				$attributes[2] = 'style="';
				foreach ( $atts['style'] as $key => $value ) {
					$attributes[2] .= $key.':'.$value.';'; }
				$attributes[2] .= '"';
			}
			if ( isset( $atts['placeholder'] ) && ! empty( $atts['placeholder'] ) ) {
				$attributes[3] = 'placeholder="'.esc_attr( $atts['placeholder'] ).'"';
			}

			if ( isset( $atts['data'] ) && ! empty( $atts['data'] ) ) {
				foreach ( $atts['data'] as $key => $value ) {
					$attributes[3] = 'data-'.$key.'="'.esc_attr( $value ).'"'; }
			}

			if ( ! $attributes ) {
				return null; }

			return implode( ' ', $attributes );

		}
		/**
		 * Image picker element.
		 * @param  string $name  No use.
		 * @param  array  $atts  Attributes for custom html.
		 * @return html       Image Picker.
		 */
		public static function field_image_picker($name, $atts) {

			$html = Responsive_Markup::field_image('selected_image', array(
				'lable' => 'Choose marker Image',
				'src' => $atts['src'],
				'width' => '32',
				'class' => 'noclass selected_image col-md-1',
				'height' => '37',
				'required' => $atts['required'],
			));

			$html .= Responsive_Markup::field_anchor('choose_image', array(
				'value' => __( 'Choose Marker Image', WPGMP_TEXT_DOMAIN ),
				'href' => 'javascript:void(0);',
				'class' => 'btn btn-info choose_image col-md-3 ',
				'data' => array( 'target' => $name ),
			));

			$html .= Responsive_Markup::field_anchor('remove_image', array(
				'value' => __( 'Remove Marker Image', WPGMP_TEXT_DOMAIN ),
				'before' => '<div class="col-md-3">',
				'after' => '</div>',
				'href' => 'javascript:void(0);',
				'class' => 'btn btn-danger remove_image col-md-3 col-md-offset-1',
				'data' => array( 'target' => $name ),

			));

			$html .= Responsive_Markup::field_hidden('group_marker', array(
				'value' => $atts['src'],
				'id' => $name,
				'name' => $name,
			));

			return $html;
		}

		/**
		 * Custom HTML to display.
		 * @param  string $name  No use.
		 * @param  array  $atts  Attributes for custom html.
		 * @return html       Body of custom html.
		 */
		public static function field_html($name, $atts) {
			extract( $atts );
			return $html;
		}
		/**
		 * Hidden Field
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_hidden( $name, $atts ) {

			extract( $atts );
			$value = $value ? $value : $default_value;
			return '<input type="hidden" name="'.$name.'" id="'.$id.'" value="'.$value.'" />';
		}
		/**
		 * Group Heading
		 * @param  string $name Group title.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_group( $name, $atts ) {

			extract( $atts );
			$value = $value ? $value : $default_value;
			return '<h4 class="alert alert-info">'.$value.'</h4>';
		}
		/**
		 * DIV node
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_div( $name, $atts ) {

			extract( $atts );
			$value = $value ? $value : $default_value;
			return '<div name="'.$name.'" '.self::get_element_attributes( $atts ).'>'.$value.'</div>';
		}
		/**
		 * Blockquote node
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_blockquote( $name, $atts ) {

			extract( $atts );
			$value = $value ? $value : $default_value;
			return '<blockquote>'.$atts['value'].'</blockquote>';

		}
		/**
		 * Text Input element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_text( $name, $atts ) {

			$elem_value = @$atts['value'] ? @$atts['value'] : $atts['default_value'];
			if ( strstr( @$atts['class'], 'color' ) !== false ) {
				$elem_value = str_replace( '#','',$elem_value );
				$elem_value = '#'.$elem_value;
			}
			$element  = '<input type="text" name="'.$name.'" value="'.esc_attr( stripcslashes( $elem_value ) ).'"'.self::get_element_attributes( $atts ).' />';
			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {
				$element .= '<p class="help-block">'.$atts['desc'].'</p>'; }
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );
		}
		/**
		 * Display Information message in <p> tag.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_infoarea($name, $atts) {
			return '<p>'.$atts['desc'].'</p>'; }

		/**
		 * Image tag.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_image( $name, $atts ) {

			$element  = '<img src="'.$atts['src'].'" alt="'.$atts['alt'].'" height="'.$atts['height'].'" width="'.$atts['width'].'" '.self::get_element_attributes( $atts ).' >';
			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {
				$element .= '<p class="help-block">'.$atts['desc'].'</p>'; }
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );
		}
		/**
		 * Generate output using wp_editor.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_wp_editor( $name, $atts ) {

			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];
			$args = array( 'textarea_rows' => $atts['textarea_rows'], 'textarea_name' => $atts['textarea_name'], 'editor_class' => $atts['class'] );
			$output = '';
			ob_start();
			wp_editor( esc_textarea( $value ) , $name, $args );
			$output .= ob_get_contents();
			ob_clean();
			$output .= '<p class="help-block">'.$atts['desc'].'</p>';
			return $output;

		}

		/**
		 * Textarea element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_textarea( $name, $atts ) {

			$elem_value = $atts['value'] ? $atts['value'] : $atts['default_value'];
			$element  = '<textarea  rows="5" name="'.$name.'" '.self::get_element_attributes( $atts ).' >'.esc_textarea( wp_unslash( $elem_value ) ).'</textarea>';
			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {
				$element .= '<p class="help-block">'.$atts['desc'].'</p>'; }
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );
		}
		/**
		 * File Input element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_file( $name, $atts ) {

			$elem_value = $atts['value'] ? $atts['value'] : $atts['default_value'];
			$element  = '<input type="file" name="'.$name.'" '.self::get_element_attributes( $atts ).' />';
			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {
				$element .= '<p class="help-block">'.$atts['desc'].'</p>'; }
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );
		}
		/**
		 * Select Input element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_select( $name, $atts ) {

			if ( ! isset( $atts['options'] ) || empty( $atts['options'] ) ) {
				return; }

			$options = '';
			$elem_value = $atts['current'] ? $atts['current'] : $atts['default_value'];

			foreach ( $atts['options'] as $key => $value ) {
				$options .= '<option value='.$key.' '.selected( $elem_value,$key,false ).'>'.$value.'</option>';
			}

			$element  = '<select name="'.$name.'" '.self::get_element_attributes( $atts ).'>'.$options.'</select>';
			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {
				$element .= '<p class="help-block">'.$atts['desc'].'</p>'; }
			return apply_filters( 'wpgmp_select_field_'.$name, $element, $name, $atts );
		}

		/**
		 * Submit button element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_submit( $name, $atts ) {

			$element = '<div class="row">
						<div class="col-md-12">
						<input type="submit"  name="'.$name.'" class="btn btn-primary" value="'.$atts['value'].'"/>
						</div>
						</div>';

			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );
		}
		/**
		 * Button element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_button( $name, $atts ) {

			$eventstr = '';
			if ( isset( $atts['onclick'] ) and ! empty( $atts['onclick'] ) ) {

				$eventstr .= 'onclick ='.stripcslashes( $atts['onclick'] );

			}
			$element  = '<button type="button"  name="'.$name.'" '.self::get_element_attributes( $atts ).' />'.$atts['value'].'</button>';
			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {
				$element .= '<p class="help-block">'.$atts['desc'].'</p>'; }
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );
		}
		/**
		 * Checkbox input element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_checkbox( $name, $atts ) {

			$id = ( ! empty( $atts['id'] )) ? $atts['id'] : $name;
			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];
			$element  = '<div class="checkbox"><label><input type="checkbox"  id="'.$atts['id'].'" name="'.$name.'" value="'.esc_attr( stripcslashes( $value ) ).'"'.self::get_element_attributes( $atts ).' '.checked( $value, $atts['current'], false ).'/>'.$atts['desc'].'</label></div> ';
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );

		}
		/**
		 * Anchor tag element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_anchor( $name, $atts ) {

			$id = ( ! empty( $atts['id'] )) ? $atts['id'] : $name;
			$value = $atts['value'] ? $atts['value'] : $atts['default_value'];
			$element  = '<a id="'.$atts['id'].'" name="'.$name.'" '.self::get_element_attributes( $atts ).'/>'.$value.'</a>';
			if ( isset( $atts['desc'] ) && ! empty( $atts['desc'] ) ) {
				$element .= '<p class="help-block">'.$atts['desc'].'</p>'; }
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );

		}
		/**
		 * Radio input element.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public  static function field_radio( $name, $atts ) {

			$elem_value = $atts['current'] ? $atts['current'] : $atts['default_value'];
			$element = '';
			$radio_options = $atts['radio-val-label'];
			if ( is_array( $atts['radio-val-label'] ) ) {

				foreach ( $radio_options as $radio_val => $radio_label ) {
					$element .= '<label class="radio-inline"><input type="radio" name="'.$name.'" value="'.esc_attr( stripcslashes( $radio_val ) ).'"'.self::get_element_attributes( $atts ).' '.checked( $radio_val, $elem_value, false ).'>&nbsp;&nbsp;'.$radio_label.'</label>';
				}
			}
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );

		}
		/**
		 * Message boxes.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public  static function field_message( $name, $atts ) {
			$type = $atts['class'];
			$id = $atts['id'];
			$element = '<div '.self::get_element_attributes( $atts ).'>'.$atts['value'].'</div>';
			return apply_filters( 'wpgmp_input_field_'.$name, $element, $name, $atts );
		}
		/**
		 *  Sub heading
		 * @param  string $heading heading.
		 * @return html   blockquote html wrapper.
		 */
		public static function sub_heading($heading) {

			return '<div class="col-md-12">
					<blockquote>
					'.$heading.'
					</blockquote>
					</div>';
		}
		/**
		 * Table generator.
		 * @param  string $name Element name.
		 * @param  array  $atts Attributes.
		 * @return html       Element Html.
		 */
		public static function field_table($name, $atts) {
			$heads = $atts['heading'];
			$data  = $atts['data'];
			$current = $atts['current'];
			$id = (isset( $atts['id'] )) ? $atts['id'] : $name;
			if ( ! isset( $atts['class'] ) or '' == $atts['class'] ) {
				$atts['class'] = 'dataTable';
			}
			$output = '<table '.self::get_element_attributes( $atts ).' id="'.$id.'"><thead><tr>';
			if ( is_array( $heads ) ) {

				foreach ( $heads as $head ) {
					$output .= '<td><strong>'.__( $head, WPGMP_TEXT_DOMAIN ).'</strong></td>';
				}
			}

			$output .= '</tr></thead><tbody>';
			if ( ! empty( $data ) ) {
				foreach ( $data as $row => $columns ) {
					$output .= '<tr>';
					foreach ( $columns as $key => $col ) {
						$output .= '<td>'.($col).'</td>'; }
					$output .= '</tr>';
				}
			}

			$output .= '</tbody></table>';

			return apply_filters( 'wpgmp_input_field_'.$name, $output, $name, $atts );

		}
		/**
		 * Show success or error message.
		 * @param  array $response Success or Error message.
		 * @return html          Success or error message wrapper.
		 */
		public static function show_message($response) {

			if ( empty( $response ) ) {
				return; }

			$output = '';
			$output .= '<div id="message" class="'.$response['type'].'">';
			$output .= '<p><strong>'.$response['message'].'</strong></p></div>';

			return $output;
		}
		/**
		 * Button Wrapper
		 * @param  string $title Button title.
		 * @param  url    $link  Link url.
		 * @return html       Button wrapper.
		 */
		public static function button($title, $link) {

			return '<span class="glyphicon glyphicon-add wpgmp_new_add button action"><a href="'.esc_html( $link ).'">'.__( $title,WPGMP_TEXT_DOMAIN ).'</a></span>';
		}

	}
}
