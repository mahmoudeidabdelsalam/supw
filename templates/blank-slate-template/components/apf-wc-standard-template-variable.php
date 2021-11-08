<?php
/**
 * Product data variations
 *
 * @package WooCommerce\Admin\Metaboxes\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="variable_product_options" class="panel wc-metaboxes-wrapper">
  <div id="variable_product_options_inner">
    <div class="block-meta-attr">
      <div class="apf-option-group">
        <p>
          <label for="apf-attributes-acf">Variable</label>
          <select class="apf-input attribute_do" name="attribute_value_one" id="apf-attributes-acf">
            <option>select item</option>
            <?php
              if( have_rows('attributes_acf') ):
                while( have_rows('attributes_acf') ) : the_row();
                  $slug = get_sub_field('slug_attributes');
                  $name = get_sub_field('name_attributes');
                  echo "<option value=".$slug.">" .$name. "</option>";
                endwhile;
              endif;
            ?>
          </select>          
        </p>

        <button type="button" class="attribute_do_add">Add attributes</button>
      </div>
    </div>

    

    <div class="box-container-attribute"></div>
 
  </div>
</div>

  