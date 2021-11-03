<?php
get_header();
if(is_user_logged_in()) {
    update_option( 'supw_plugin_template', 'apf-wc-standard-template' );
?>

    <div class="apf-plugin-context">
        <div class="apf-plugin-context-inner">
            <form method="post" id="apf-product-form">
                <div class="apf-col">
                  
                    <?php
                        require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-product-image.php'); // Product image
                        require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-title.php'); // Title
                        require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-categories.php'); // Categories
                        // require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-description.php'); // Description
                        require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-product-options.php'); // WC Product options
                        // require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-excerpt.php'); // Short description / excerpt
                        require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-product-gallery.php'); // Product gallery
                        // require_once( plugin_dir_path( __FILE__ ).'apf-wc-standard-template/components/apf-wc-standard-template-tags.php'); // Tags
                    ?>
                </div>
                
                </div>
                <div style="width: 100%;float: left;">
                    <button id="apf-save-product" class="apf-button-default apf-button-primary" type="submit">Save</button>
                </div>
            </form>
            <!-- The Modal -->
            <div class="apf-modal">
                <!-- Modal content -->
                <div class="apf-modal-content">
                    <div class="apf-loading-section">
                        <div class="apf-loader"></div>
                    </div>
                    
                    <!-- Success -->
                    <div class="apf-modal-result apf-hide-section">
                        <div class="apf-modal-header">
                            <h2><?php _e( 'Success', 'supw_plugin' ); ?></h2>
                        </div>
                        <div class="apf-modal-main-section">
                            <p><?php _e( 'Your product has been added correctly!', 'supw_plugin' ); ?></p>
                            <p class="apf-p-name"><?php _e( 'Product permalink', 'supw_plugin' ); ?>: <span> </span></p>
                        </div>
                        <button  class="apf-button-default apf-button-primary"> <?php _e( 'OK', 'supw_plugin' ); ?> </button>
                    </div>

                    <!-- Error -->
                    <div class="apf-modal-result-error apf-hide-section">
                        <div class="apf-modal-header">
                            <h2><?php _e( 'Error!', 'supw_plugin' ); ?></h2>
                        </div>
                        <div class="apf-modal-main-section">
                            <p></p>
                        </div>
                        <button  class="apf-button-default apf-button-primary"> <?php _e( 'Close', 'supw_plugin' ); ?> </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
<?php
}
else {
?>
    <div class="apf-plugin-context">
        <div class="apf-warning-message">
            <p><?php _e( 'You  must be logged in to view this content!', 'supw_plugin' ); ?></p>
        </div>
    </div>
<?php
   
}
get_footer();
?>