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
        'product_type'       => $values['apf-product-type'],
        'sku'                => $values['sku'],
        'regular_price'      => $values['regular_price'],
        'sale_price'         => $values['sale_price'],
        'stock_quantity'     => $values['supw_stock'], // Stock quantity or null
        'upsell_ids'         => $values['upsell_ids'],
        'cross_sell_ids'     => $values['crossell_ids'],
        'parent_id'          => $values['parent_id'],
        'category_ids'       => $values['category_ids'],
        'tag_ids'            => $values['tag_ids'],
        'image_id'           => $values['supw_product_image'],
        'gallery_image_ids'  => $values['supw_product_gallery_ids'],
    ) );


    $product->set_status( 'pending' );

    // Custom attributes
    if($_POST['custom_attributes']) {
      $cus_attributes = array();
      foreach ($_POST['custom_attributes'] as $key => $value) {
          if($value) {
              $cus_attribute = new WC_Product_Attribute();
              $cus_attribute->set_id(0);
              $cus_attribute->set_name($key);
              $cus_attribute->set_options(explode ("|", $value['val']));
              $cus_attribute->set_position(0);
              $cus_attribute->set_visible($value['visibility'] == 'on' ? true : false );
              array_push ($cus_attributes, $cus_attribute);
          }
      }
  
      $product->set_attributes( $cus_attributes );
    }


    // Categories
    if($_POST['categories']) {    
      $product->set_category_ids($_POST['categories']);
    }
    
    $pid = $product->save();

    if($values['apf-product-type']) {
      wp_set_object_terms( $product->get_id(), $values['apf-product-type'], 'product_type' );
    }
    
    if($_POST['attributes']) {
    // Attributes
      foreach ($_POST['attributes'] as $key => $value) {
        if($value) {
            wp_set_object_terms( $pid, intval($value['val']), $key, true );

            $att = Array($key => Array(
                'name'=> $key,
                'value'=> intval($value['val']),
                'is_visible' => '1',
                'is_variation' => '1',
                'is_taxonomy' => '1'
              ));
              $prev = get_post_meta($pid, '_product_attributes', true);
              if($prev) {
                update_post_meta( $pid, '_product_attributes',  array_merge($prev,$att));
              }
              else {
                update_post_meta( $pid, '_product_attributes',  $att);
              }
            
        }
      }
    }


    // var_dump($_POST['variables']);
    if($_POST['variables']) {
      foreach ($_POST['variables'] as $value) {
        create_product_variations($pid, $value['slug_one'], $value['slug_two'], $value['attr_one'], $value['attr_two'], $value['variable_price'], $value['number_sku']);
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





// Saving variable attr product
add_action( 'wp_ajax_save_attr_product', 'save_attr_product' );
add_action( 'wp_ajax_nopriv_save_attr_product', 'save_attr_product' );
function save_attr_product() {
  check_ajax_referer( 'ns-apf-special-string', 'security' );

    $value_one = $_POST['value_one'];
    $value_two = $_POST['value_two'];
    $slug_attr_one = $_POST['slug_attr_one'];
    $slug_attr_two = $_POST['slug_attr_two'];


    $output .= '<div class="attributes-variable">';
    $output .= '<input class="custom-input" type="text" readonly name="attribute_one" value="'.$value_one.'">';
    $output .= '<input class="custom-input" type="text" readonly name="attribute_two" value="'.$value_two.'">';
    $output .= '<input type="text" name="attribute_slug_one" value="'.$slug_attr_one.'" hidden>';
    $output .= '<input type="text" name="attribute_slug_two" value="'.$slug_attr_two.'" hidden>';
    $output .= '<input class="custom-input" type="text" name="variable_regular_price" value="" placeholder="Regular price ($)">';
    $output .= '<input class="custom-input" type="text" name="variable_sku" value="" placeholder="SKU">';
    $output .= '</div>';


    wp_send_json_success($output);
  die();
}


// Set variable attr product
function create_product_variations( $product_id, $slug_one, $slug_two, $attr_one, $attr_two, $variation_price, $variation_sku ){
    
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
    if( ! taxonomy_exists( $slug_one ) ){
      register_taxonomy(
        $slug_one,
        'product_variation',
        array(
          'hierarchical' => false,
          'label' => ucfirst( $attr_one ),
          'query_var' => true,
          'rewrite' => array( 'slug' => sanitize_title($attr_one) ), // The base slug
        )
      );
    }
    if( ! term_exists( $attr_one, $slug_one ) )
      wp_insert_term( $attr_one, $slug_one );

    $term_slug = get_term_by('name', $attr_one, $slug_one )->slug;
    $post_term_names =  wp_get_post_terms( $product_id, $slug_one, array('fields' => 'names') );

    if( ! in_array( $attr_one, $post_term_names ) )
      wp_set_post_terms( $product_id, $attr_one, $slug_one, true );

    update_post_meta( $variation_id, 'attribute_'.$slug_one, $term_slug );


    // If taxonomy doesn't exists we create it
    if( ! taxonomy_exists( $slug_two ) ){
      register_taxonomy(
        $slug_two,
        'product_variation',
        array(
          'hierarchical' => false,
          'label' => ucfirst( $attr_two ),
          'query_var' => true,
          'rewrite' => array( 'slug' => sanitize_title($attr_two) ), // The base slug
        )
      );
    }
    if( ! term_exists( $attr_two, $slug_two ) )
      wp_insert_term( $attr_two, $slug_two );

    $term_slug_two = get_term_by('name', $attr_two, $slug_two )->slug;
    $post_term_names_two =  wp_get_post_terms( $product_id, $slug_two, array('fields' => 'names') );

    if( ! in_array( $attr_two, $post_term_names_two ) )
      wp_set_post_terms( $product_id, $attr_two, $slug_two, true );

    update_post_meta( $variation_id, 'attribute_'.$slug_two, $term_slug_two );

        

    ## Set/save all other data
    // SKU
    if( ! empty( $variation_sku ) )
        $variation->set_sku( $variation_data['sku'] );
    // price
    if( ! empty( $variation_price ) )
      $variation->set_regular_price( $variation_price );

    $variation->save(); // Save the data
}



?>



