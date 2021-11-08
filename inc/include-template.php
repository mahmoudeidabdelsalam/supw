<?php
add_filter( 'page_template', 'supw_attach_main_page_template' );
function supw_attach_main_page_template( $page_template )
{
    $selected_plugin_page_id = get_option('supw_plugin_page_id');
    $selected_plugin_page = get_page($selected_plugin_page_id);
    
    if ( is_page( $selected_plugin_page->post_name ) ) {
        $plugin_template_option = get_option( 'supw_plugin_template' );

        $page_template = RAC_NS_PLUGIN_DIR.'/templates/'.$plugin_template_option.'.php';

        wp_enqueue_style( $plugin_template_option, '/wp-content/plugins/simple-upload-products/templates/'.$plugin_template_option.'/'.$plugin_template_option.'.css' );
        wp_enqueue_script( $plugin_template_option, '/wp-content/plugins/simple-upload-products/templates/'.$plugin_template_option.'/'.$plugin_template_option.'.js', array( 'jquery' ) );
        wp_enqueue_style( 'selectize-css', '/wp-content/plugins/simple-upload-products/templates/'.$plugin_template_option.'/components/selectize/dist/css/selectize.css' );
        wp_enqueue_script( 'selectize-js', '/wp-content/plugins/simple-upload-products/templates/'.$plugin_template_option.'/components/selectize/dist/js/standalone/selectize.min.js', array( 'jquery' ) );
    }
    return $page_template;
}
?>