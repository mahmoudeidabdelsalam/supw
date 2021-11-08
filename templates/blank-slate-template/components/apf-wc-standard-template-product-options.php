<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- WC Product options -->
<div class="apf-box-container apf-product-box-container">
    <!-- Header -->
    <div class="apf-title">
        <h4> <?php _e( 'Product Data', 'supw_plugin' ); ?> — </h4>
        <span>
            <select id="apf-product-type" name="apf-product-type">
                <option value="variable" selected="selected"><?php _e( 'Variable product', 'supw_plugin' ); ?></option>
                <option value="simple"><?php _e( 'Simple product', 'supw_plugin' ); ?></option>
            </select>
        </span>
    </div>

    <!-- Content -->
    <div class="apf-content">
     

        <!-- General options -->
        <div id="apf-general-product-data" class="apf-option-panel" style="width:100%;">
            <div class="apf-option-group">
                <p>
                    <label for="apf-regular-price"><?php _e( 'Regular price', 'supw_plugin' ); echo ' ('.get_woocommerce_currency_symbol().') ';?></label> 
                    <input type="text" class="apf-input apf-input-inner" name="regular_price" id="apf-regular-price" placeholder="">
                </p>

                <p>
                    <label for="apf-sale-price"><?php _e( 'Sale price', 'supw_plugin' ); echo ' ('.get_woocommerce_currency_symbol().') ';?></label> 
                    <input type="text" class="apf-input apf-input-inner" name="sale_price" id="apf-sale-price" placeholder="">
                </p>

                <p>
                    <label for="apf-sku"><?php _e( 'Stock quantity', 'supw_plugin' );?></label> 
                    <input type="text" class="apf-input apf-input-inner" name="quantity" id="apf-quantity" placeholder="">
                </p>
            </div>
        </div>
        <!-- variable options -->
        <div id="apf-variable-product-data" class="apf-option-panel" style="width:100%;">
            <div class="apf-loader apf-variable-loader"></div>
            <?php
                require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template-variable.php');
            ?>
        </div>

    </div>
</div>