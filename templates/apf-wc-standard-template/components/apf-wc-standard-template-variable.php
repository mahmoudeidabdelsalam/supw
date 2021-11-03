<?php
/**
 * Product data variations
 *
 * @package WooCommerce\Admin\Metaboxes\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$attr_one = get_terms( array(
  'taxonomy' => get_field('slug_attr_one'),
  'hide_empty' => false,
) );

$attr_two = get_terms( array(
  'taxonomy' => get_field('slug_attr_tow'),
  'hide_empty' => false,
) );

?>

<div id="variable_product_options" class="panel wc-metaboxes-wrapper hidden">
  <div id="variable_product_options_inner">

    <div class="header-meta-attr">
      <select class="apf-input attribute_do" name="attribute_value_one" style="width: 50%;">
        <option>select item</option>
        <?php
          foreach ( $attr_one as $term ) {
            echo "<option value=".$term->term_id.">" .$term->name. "</option>";
          }
        ?>
      </select>

      <select class="apf-input attribute_do" name="attribute_value_two" style="width: 50%;">
        <option>select item</option>
        <?php
          foreach ( $attr_two as $term ) {
            echo "<option value=".$term->term_id.">" .$term->name. "</option>";
          }
        ?>
      </select>

      <?php if(get_field('slug_attr_one')): ?>
        <input type="text" hidden value="<?= the_field('slug_attr_one'); ?>" name="slug_attr_one">
      <?php endif; ?>
      <?php if(get_field('slug_attr_tow')): ?>
        <input type="text" hidden value="<?= the_field('slug_attr_tow'); ?>" name="slug_attr_tow">
      <?php endif; ?>

      <input type="text" hidden value="0" name="counter">

      <button type="button" class="attribute_do_add"> Add attribute </button>
    </div>


    <div class="box-container-attribute"></div>
 
  </div>
</div>

  