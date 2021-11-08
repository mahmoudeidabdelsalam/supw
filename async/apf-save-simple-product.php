<?php
// Saving async simple product
add_action( 'wp_ajax_save_simple_product', 'save_simple_product' );
add_action( 'wp_ajax_nopriv_save_simple_product', 'save_simple_product' );
function save_simple_product() {
    check_ajax_referer( 'ns-apf-special-string', 'security' );
    
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $values = array();
    foreach ($_POST['formdata'] as $form_elem) {
        if($form_elem['value']) {
            $values[$form_elem['name']] = $form_elem['value'];
        }
    }

    $product           = new WC_Product();

    if(trim($values['name']) == '') {
        $error = new WP_Error( '001', 'Product name is required and cannot be blank.' );       
        wp_send_json_error( $error );
        die();
    }

    $product->set_props( array (
        'name'               => $values['name'],
        'manage_stock'       => true, 
        'stock_quantity'     => $values['quantity'],
        'product_type'       => $values['apf-product-type'],
        'regular_price'      => $values['regular_price'],
        'sale_price'         => $values['sale_price'],
        'upsell_ids'         => $values['upsell_ids'],
        'cross_sell_ids'     => $values['crossell_ids'],
        'category_ids'       => $values['category_ids'],
        'image_id'           => $values['supw_product_image'],
    ) );

    $product->set_status( 'pending' );


    // Categories
    if($_POST['categories']) {    
      $product->set_category_ids($_POST['categories']);
    }
    
    $pid = $product->save();

    if($values['apf-product-type']) {
      wp_set_object_terms( $product->get_id(), $values['apf-product-type'], 'product_type' );
    }
    

    $product_id = $pid;

    $attribute = $_POST['attribute'];

    $attributes = get_terms( array(
      'taxonomy' => $attribute,
      'hide_empty' => false,
    ) );

    $array = [];
    foreach ($attributes as $item) {
      $array [] = $item->name;
    }

    $attributes_data = array(
        array('name'=> $_POST['attribute_text'],  'options'=>$array, 'visible' => 1, 'variation' => 1 )
    );

    if( sizeof($attributes_data) > 0 ){
        $attributes = array(); // Initializing

        // Loop through defined attribute data
        foreach( $attributes_data as $key => $attribute_array ) {
            if( isset($attribute_array['name']) && isset($attribute_array['options']) ){
                // Clean attribute name to get the taxonomy
                $taxonomy = 'pa_' . wc_sanitize_taxonomy_name( $attribute_array['name'] );

                $option_term_ids = array(); // Initializing

                // Loop through defined attribute data options (terms values)
                foreach( $attribute_array['options'] as $option ){
                    if( term_exists( $option, $taxonomy ) ){
                        // Save the possible option value for the attribute which will be used for variation later
                        wp_set_object_terms( $product_id, $option, $taxonomy, true );
                        // Get the term ID
                        $option_term_ids[] = get_term_by( 'name', $option, $taxonomy )->term_id;
                    }
                }
            }
            // Loop through defined attribute data
            $attributes[$taxonomy] = array(
                'name'          => $taxonomy,
                'value'         => $option_term_ids, // Need to be term IDs
                'position'      => $key + 1,
                'is_visible'    => $attribute_array['visible'],
                'is_variation'  => $attribute_array['variation'],
                'is_taxonomy'   => '1'
            );
        }
        // Save the meta entry for product attributes
        update_post_meta( $product_id, '_product_attributes', $attributes );
    }


    if($_POST['variables']) {
      foreach ($_POST['variables'] as $value) {
        // var_dump($pid, $_POST['attribute'], $value['attribute'], $values['regular_price']);
        create_product_variations($pid, $_POST['attribute'], $value['attribute'], $values['regular_price'], $values['sale_price']);
      }
    }

    if ( is_wp_error( $pid ) ) {
        $response_array['status'] = 'ko';
        $response_array['description'] = 'Something went wrong during the saving of this product.';    
        wp_send_json_error( $response_array );
        die();
    }

    $response_array['status'] = 'ok';
    $response_array['prod_name'] = $product->get_title();
    $response_array['prod_id'] = $pid;
    $response_array['permalink'] = get_permalink( $product->get_id() );


    wp_send_json_success($response_array);
	die();
}



// Set variable attr product
function create_product_variations( $product_id, $attribute, $variation_ids, $variation_price, $sale_price ){
    
    $product = wc_get_product($product_id);
    $variation_post = array(
        'post_title'  => $product->get_name(),
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );
    $variation_id = wp_insert_post( $variation_post );
    $variation = new WC_Product_Variation( $variation_id );
    
    // If taxonomy doesn't exists we create it
    if( ! taxonomy_exists( $attribute ) ){
      register_taxonomy(
        $attribute,
        'product_variation',
        array(
          'hierarchical' => false,
          'label' => ucfirst( $variation_ids ),
          'query_var' => true,
          'rewrite' => array( 'slug' => sanitize_title($variation_ids) ), // The base slug
        )
      );
    }

    if( ! term_exists( $variation_ids, $attribute ) )
      wp_insert_term( $variation_ids, $attribute );

    $term_slug = get_term_by('name', $variation_ids, $attribute )->slug;
    $post_term_names =  wp_get_post_terms( $product_id, $attribute, array('fields' => 'names') );

    if( ! in_array( $variation_ids, $post_term_names ) )
      wp_set_post_terms( $product_id, $variation_ids, $attribute, true );
      update_post_meta( $variation_id, 'attribute_'.$attribute, $term_slug );

    if( ! empty( $variation_price ) )
      $variation->set_regular_price( $variation_price );
      $variation->set_sale_price( $sale_price );
      $variation->save(); // Save the data
}



// Saving variable attr product
add_action( 'wp_ajax_save_attr_product', 'save_attr_product' );
add_action( 'wp_ajax_nopriv_save_attr_product', 'save_attr_product' );
function save_attr_product() {
  check_ajax_referer( 'ns-apf-special-string', 'security' );

    $attribute = $_POST['attribute'];

    $attributes = get_terms( array(
      'taxonomy' => $attribute,
      'hide_empty' => false,
    ) );

    $output .= '<div class="block-variables">';
    foreach ($attributes as $item) {
      $output .= '<p><input type="checkbox" id="'.$item->term_id.'" value="'.$item->name.'" name="variables">';
      $output .= '<label for="'.$item->term_id.'">'.$item->name.' </label></p>';
    }
    $output .= '</div>';

    wp_send_json_success($output);

  die();
}
