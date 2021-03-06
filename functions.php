<?php
if ( ! function_exists( 'blank_slate_get_templates' ) ) {

	/**
	 * Get all registered templates.
	 *
	 * @return array
	 */
	function blank_slate_get_templates() {
		return (array) apply_filters( 'blank_slate_templates', array() );
	}
}

if ( ! function_exists( 'blank_slate_get_template' ) ) {

	/**
	 * Get a registered template.
	 *
	 * @param string $file Template file/path
	 *
	 * @return string|null
	 */
	function blank_slate_get_template( $file ) {
		$templates = blank_slate_get_templates();

		return isset( $templates[ $file ] ) ? $templates[ $file ] : null;
	}
}

if ( ! function_exists( 'blank_slate_add_template' ) ) {

	/**
	 * Register a new template.
	 *
	 * @param string $file  Template file/path
	 * @param string $label Label for the template
	 */
	function blank_slate_add_template( $file, $label ) {
		add_filter(
			'blank_slate_templates',
			function ( array $templates ) use ( $file, $label ) {
				$templates[ $file ] = $label;

				return $templates;
			}
		);
	}
}

if ( ! function_exists( 'blank_slate_register_admin_page' ) ) {

	/**
	 * Register the admin page.
	 */
	function blank_slate_register_admin_page() {
		add_menu_page(
			esc_html__( 'simple upload products', 'blank-slate' ),
			esc_html__( 'simple upload products', 'blank-slate' ),
			'edit_posts',
			'blank-slate',
			function () {
				require __DIR__ . '/pages/admin.php';
			},
			'dashicons-media-default'
		);
	}
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Add wp_body_open() template tag if it doesn't exist (WP versions less than 5.2).
	 */
	function wp_body_open() {
		/**
		 * Triggered after the opening body tag.
		 *
		 * @since 5.2.0
		 */
		do_action( 'wp_body_open' );
	}
}

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'wp_enqueue_scripts', function() {
  $ns_plugin_prefix = 'apf';
  wp_enqueue_script( 'ns-'.$ns_plugin_prefix.'ajax-save-simple-product', plugins_url( '/inc/frontend/save-simple-product.js', __FILE__ ), array('jquery') );
  wp_localize_script( 'ns-'.$ns_plugin_prefix.'ajax-save-simple-product', 'savesimpleproduct', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce( 'ns-apf-special-string' )));

  wp_enqueue_script( 'ns-'.$ns_plugin_prefix.'ajax-product-attributes', plugins_url( '/inc/frontend/product-attributes.js', __FILE__ ), array('jquery') );
  wp_localize_script( 'ns-'.$ns_plugin_prefix.'ajax-product-attributes', 'productattributes', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce( 'ns-apf-special-string' )));

});



if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key' => 'group_6181ca47ae001',
    'title' => 'Custom Data Products',
    'fields' => array(
      array(
        'key' => 'field_6181ca7328963',
        'label' => 'Select Category',
        'name' => 'select_category',
        'type' => 'taxonomy',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'taxonomy' => 'product_cat',
        'field_type' => 'multi_select',
        'allow_null' => 0,
        'add_term' => 0,
        'save_terms' => 0,
        'load_terms' => 0,
        'return_format' => 'id',
        'multiple' => 0,
      ),
      array(
        'key' => 'field_61885e707c1d4',
        'label' => 'attributes',
        'name' => 'attributes_acf',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'collapsed' => '',
        'min' => 0,
        'max' => 0,
        'layout' => 'row',
        'button_label' => 'add attributes',
        'sub_fields' => array(
          array(
            'key' => 'field_61885fc12312307c1d5',
            'label' => 'Name attributes',
            'name' => 'name_attributes',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '50',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
          ),
          array(
            'key' => 'field_61885fc07c1d5',
            'label' => 'slug attributes',
            'name' => 'slug_attributes',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '50',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
          ),
        ),
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'blank-slate-template.php',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'acf_after_title',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));
  
  endif;		



