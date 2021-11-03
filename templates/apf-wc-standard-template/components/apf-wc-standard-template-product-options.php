<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- WC Product options -->
<div class="apf-box-container apf-product-box-container">
    <!-- Header -->
    <div class="apf-header">
        <h2> <?php _e( 'Product Data', 'supw_plugin' ); ?> — </h2>
        <span>
            <select id="apf-product-type" name="apf-product-type">
                <option value="simple" selected="selected"><?php _e( 'Simple product', 'supw_plugin' ); ?></option>
                <option value="variable"><?php _e( 'Variable product', 'supw_plugin' ); ?></option>
            </select>
        </span>
    </div>

    <!-- Content -->
    <div class="apf-content">
        <!-- Tabs -->
        <ul class="apf-wc-tabs">
            <li class="apf-tab-option apf-general-options active">
                <a href="#apf-general-product-data"><span><?php _e( 'General', 'supw_plugin' ); ?></span></a>
            </li>
            <li class="apf-tab-option apf-attributes-options">
                <a href="#apf-attributes-product-data"><span><?php _e( 'attributes', 'supw_plugin' ); ?></span></a>
            </li>
            <li class="apf-tab-option apf-variable-options apf-inventory-options" style="display: none;">
                <a href="#apf-variable-product-data"><span><?php _e( 'Variable', 'supw_plugin' ); ?></span></a>
            </li>
        </ul>

        <!-- General options -->
        <div id="apf-general-product-data" class="apf-option-panel">
            <div class="apf-option-group">
                <p>
                    <label for="apf-regular-price"><?php _e( 'Regular price', 'supw_plugin' ); echo ' ('.get_woocommerce_currency_symbol().') ';?></label> 
                    <input type="text" class="apf-input apf-input-inner" name="regular_price" id="apf-regular-price" placeholder="">
                </p>
                <p>
                    <label for="apf-sale-price"><?php _e( 'Sale price', 'supw_plugin' ); echo ' ('.get_woocommerce_currency_symbol().') ';?></label> 
                    <input type="text" class="apf-input apf-input-inner" name="sale_price" id="apf-sale-price" placeholder="">
                </p>

                <?php
                    $sku_tooltip = __('SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'supw_plugin');
                    $stock_status_tooltip = __('Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'supw_plugin');
                ?>
                <p>
                    <label for="apf-sku"><?php _e( 'SKU', 'supw_plugin' );?></label> 
                    <input type="text" class="apf-input apf-input-inner" name="sku" id="apf-sku" placeholder="">
                    <?php
                        supw_print_tooltip($sku_tooltip);
                    ?>
                </p>
            </div>
        </div>
        <!-- attributes options -->
        <div id="apf-attributes-product-data" class="apf-option-panel apf-hide-section">
            <div class="apf-loader apf-attributes-loader"></div>
            <?php
                require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template-product-attributes.php');
            ?>
        </div>
        <!-- variable options -->
        <div id="apf-variable-product-data" class="apf-option-panel apf-hide-section">
            <div class="apf-loader apf-variable-loader"></div>
            <?php
                require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template-variable.php');
            ?>
        </div>

    </div>
</div>