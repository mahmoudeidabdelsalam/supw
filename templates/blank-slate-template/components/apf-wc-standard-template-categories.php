<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!-- Product categories -->
<div class="apf-box-container apf-product-box-container apf-category-container">
    <div class="apf-title">
        <h4> <?php _e( 'Product categories', 'supw_plugin' ); ?></h4>
    </div>
    <div class="apf-content" style="padding: 12px;">
      <?php
        $select_category = get_field('select_category');
        foreach ($select_category as $category_id) {
          $category = get_term_by('id', $category_id, 'product_cat');    
          echo '<span class="d-inline-block mr-3"><label class="apf-cat-list"><input value="'. $category_id.'" type="checkbox" name="supw_product_cat[]">'.$category->name.'</label></span>';
        }
      ?>
    </div>
</div>