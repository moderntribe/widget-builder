<?php
/**
 * Widget Builder
 *
 * This file build
 *
 * @author Timothy Wood @codearachnid
 * @copyright Modern Tribe, Inc.
 * @package Tribe_Widget_Builder
 **/

// Block direct requests
if ( !defined( 'ABSPATH' ) )
	die();

if ( !class_exists( 'Tribe_Widget_Builder' ) ) {

	/**
	 * Widget Builder
	 *
	 * @package Tribe_Widget_Builder
	 * @author Timothy Wood
	 */
	class Tribe_Widget_Builder {

		var $token;
		private $base_path;
		
		/**
		 *
		 * Tribe_Widget_Builder Constructor
		 *
		 */
		public function __construct() {
			$this->token = 'tribe_widget_builder';

			$this->load_plugin_text_domain();

			// setup the base path for includes in this plugin
			$this->base_path = rtrim( plugin_dir_path(__FILE__), '/classes');

			add_action( 'init', array( &$this, 'register_post_type' ), 20 );

			if ( is_admin() ) {

				// remove publish box
				add_action( 'admin_menu', array( &$this, 'remove_publish_box') );

				// setup meta boxes for custom fields
				add_action( 'add_meta_boxes', array( &$this, 'meta_box_setup' ) );
				add_action( 'save_post', array( &$this, 'meta_box_save') );

				// change the post status messages when saving, publishing or updating
				add_filter( 'post_updated_messages', array( &$this, 'widet_status_message') );

			}
		}

		/**
		 * widget_status_message function.
		 * 
		 * @access public
		 * @return $messages
		 */
		function widet_status_message( $messages ) {
			if( $this->token == get_post_type() ) {
				$messages["post"][1] = __( 'Widget content has been updated.', 'widget-builder' );
				$messages["post"][2] = '';
				$messages["post"][3] = $messages["post"][2];
				$messages["post"][4] = $messages["post"][1];
				$messages["post"][6] = __( 'Widget has been created.', 'widget-builder' );
				$messages["post"][8] = __( 'Widget has been created.', 'widget-builder' );
			}
			return $messages;
		}

		
		/**
		 * register_post_type function.
		 * 
		 * @access public
		 * @return void
		 */
		public function register_post_type () {			
			$page = 'themes.php';

			$menu = __( 'Widget Builder', 'widget-builder' );
			$singular = __( 'Widget', 'widget-builder' );
			$plural = __( 'Widgets', 'widget-builder' );
			$rewrite = array( 'slug' => '' );
			$supports = array( 'title','editor','thumbnail' );
			
			if ( $rewrite == '' ) { $rewrite = $this->token; }
			
			$labels = array(
				'name' => $menu,
				'singular_name' => $singular,
				'add_new' => sprintf( __( 'Add New %s', 'widget-builder' ), $singular ),
				'add_new_item' => sprintf( __( 'Add New %s', 'widget-builder' ), $singular ),
				'edit_item' => sprintf( __( 'Edit %s', 'widget-builder' ), $singular ),
				'new_item' => sprintf( __( 'New %s', 'widget-builder' ), $singular ),
				'all_items' => $menu,
				'view_item' => sprintf( __( 'View %s', 'widget-builder' ), $singular ),
				'search_items' => sprintf( __( 'Search %s', 'widget-builder' ), $plural ),
				'not_found' =>  sprintf( __( 'No %s Found', 'widget-builder' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', $this->token ), $plural ),
				'parent_item_colon' => '',
				'menu_name' => $menu
		
			);
			$args = array(
				'labels' => $labels,
				'public' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_nav_menus' => false, 
				'show_in_admin_bar' => false, 
				'show_in_menu' => $page,
				'query_var' => true,
				'rewrite' => $rewrite,
				'capability_type' => 'post',
				'has_archive' => $rewrite,
				'hierarchical' => false,
				'menu_position' => null,
				'supports' => $supports
			);
			register_post_type( $this->token, $args );
		} // End register_post_type()

		/**
		 * register_custom_widgets function.
		 * 
		 * @access public
		 * @return void
		 */
		public function register_custom_widgets () {
			register_widget( 'Tribe_Widget_Builder_Widget' );
		}

		/**
		 * remove_publish_box function.
		 * 
		 * @access public
		 * @return void
		 */
		function remove_publish_box() {
			remove_meta_box( 'submitdiv', $this->token, 'side' );
		}

		/**
		 * meta_box_setup function.
		 * 
		 * @access public
		 * @return void
		 */
		public function meta_box_setup() {

			// add custom publish box
	        add_meta_box( 
	            $this->token . '_publish',
	            __('Publish', 'widget-builder' ),
	            array( &$this, 'meta_box_publish' ),
	            $this->token,
	            'side',
	            'high'
	        );

			// add link details
	        add_meta_box( 
	            $this->token . '_link_details',
	            __('Widget Link Details', 'widget-builder' ),
	            array( &$this, 'meta_box_content' ),
	            $this->token,
	            'side',
	            'default'
	        );

	        // add internal widget details
	        add_meta_box( 
	            $this->token . '_widet_details',
	            __('Widget Admin Details', 'widget-builder' ),
	            array( &$this, 'meta_box_widget' ),
	            $this->token,
	            'normal',
	            'low'
	        );

	        // cleanup excerpt in case it's showing
	    	remove_meta_box( 'postexcerpt', $this->token, 'normal' );


		}

		/**
		 * meta_box_publish function.
		 * 
		 * @access public
		 * @return void
		 */
		public function meta_box_publish() {

			global $action;

			$post_type = $this->token;
			$post_type_object = get_post_type_object($post_type);
			$can_publish = current_user_can($post_type_object->cap->publish_posts);

			// get template hierarchy
			include( $this->get_template_hierarchy( 'metabox_pub' ) );

	    }

		/**
		 * meta_box_content function.
		 * 
		 * @access public
		 * @return void
		 */
		public function meta_box_content() {

			global $post_id;

			// setup view fields
			$fields = array(
				$this->token . '_link_text' => __( 'Link Text', 'widget-builder' ),
				$this->token . '_link_url' => __( 'Link URL', 'widget-builder' )
			);
			$nonce = wp_create_nonce( plugin_basename(__FILE__) );
			// get template hierarchy
			include( $this->get_template_hierarchy( 'metabox_link' ) );

	    }

		/**
		 * meta_box_widget function.
		 * 
		 * @access public
		 * @return void
		 */
		public function meta_box_widget() {

			global $post_id;

			// setup view fields
			$field = $this->token . '_widget_description';

			// get template hierarchy
			include( $this->get_template_hierarchy( 'metabox_admin' ) );

	    }

		/**
		 * meta_box_save function.
		 * 
		 * @access public
		 * @param int $post_id
		 * @return void
		 */
	    public function meta_box_save( $post_id ) {
	    
	        // Verify Autosave routine. Ignore action on Autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			// Verify save source of save to prevent outside access
			if ( ( get_post_type() != $this->token ) || ! wp_verify_nonce( $_POST[$this->token . '_nonce'], plugin_basename(__FILE__) ) ) {
				return $post_id;
			}

			// Check user permissions
			if ( 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} else {
				if ( !current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}

			// Authenticated
			$fields = array( $this->token . '_link_text', $this->token . '_link_url', $this->token . '_widget_description' );

			// Parse fields for add, update, delete
			foreach ( $fields as $f ) {
			
				${$f} = strip_tags(trim($_POST[$f]));
				
				if ( get_post_meta( $post_id, '_' . $f ) == '' ) { 
					add_post_meta( $post_id, '_' . $f, ${$f}, true ); 
				} elseif( ${$f} != get_post_meta( $post_id, '_' . $f, true ) ) { 
					update_post_meta( $post_id, '_' . $f, ${$f} );
				} elseif ( ${$f} == '' ) { 
					delete_post_meta( $post_id, '_' . $f, get_post_meta( $post_id, '_' . $f, true ) );
				}	
			}

	    }

		/**
		 * Loads theme files in appropriate hierarchy: 1) child theme,
		 * 2) parent template, 3) plugin resources. will look in the tribe_widget_builder/
		 * directory in a theme and the views/ directory in the plugin
		 *
		 * @param string $template template file to search for
		 * @param string $class pass through class filters
		 * @return template path
		 * @author Modern Tribe, Inc. (Matt Wiebe)
		 **/

		function get_template_hierarchy($template, $class = null) {
			// whether or not .php was added
			$template = rtrim($template, '.php');

			if ( $theme_file = locate_template( array($this->token . '/' . $template) ) ) {
				$file = $theme_file;
			} else if ( $theme_file = locate_template(array($this->token . '/' . $template . '_' . $class)) ) {
				$file = $theme_file;
			} else {
				$file = $this->base_path . '/views/' . $template;
			}

			// ensure we have the proper extension
			$file = $file . '.php';
			
			return apply_filters( $this->token . '_' . $template, $file, $class);
		}

		/**
		 * load_plugin_text_domain function.
		 * 
		 * @access public
		 * @return void
		 */
		function load_plugin_text_domain() {
			load_plugin_textdomain( 'widget-builder', false, trailingslashit(basename(dirname(__FILE__))) . 'lang/');
		}

		/**
		 * Instance of this class for use as singleton
		 */
		private static $instance;
		
		/**
		 * Create the instance of the class
		 *
		 * @static
		 * @return void
		 */
		public static function init() {
			self::$instance = self::get_instance();
		}

		/**
		 * Get (and instantiate, if necessary) the instance of the class
		 *
		 * @static
		 * @return Tribe_Widget_Builder
		 */
		public static function get_instance() {
			if ( !is_a( self::$instance, __CLASS__ ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

	}
	
}