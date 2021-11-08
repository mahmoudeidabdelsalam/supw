<?php
/**
 * simple upload products
 *
 * @package           SUPW
 * @author            Micah Wood
 * @copyright         Copyright 2019-2020 by Aaron Reimann & Micah Wood - All rights reserved.
 * @license           GPL2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Upload Products (SUPW)
 * Plugin URI:        https://hoods.com
 * Description:       Provides a blank page template for use with WordPress page builders.
 * Version:           1.0.1
 * Requires PHP:      5.3
 * Requires at least: 4.7
 * Author:            Aaron Reimann & Micah Wood
 * Author URI:        https://hoods.com
 * Text Domain:       blank-slate
 * Domain Path:       /languages
 * License:           GPL V2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

require_once('plugineye/plugineye-class.php');


if ( ! defined( 'RAC_NS_PLUGIN_DIR' ) )
define( 'RAC_NS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'RAC_NS_PLUGIN_DIR_URL' ) )
define( 'RAC_NS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

require __DIR__. '/functions.php';

require_once( plugin_dir_path( __FILE__ ).'inc/print-tooltip.php');
require_once( plugin_dir_path( __FILE__ ).'inc/include-template.php');

require_once( RAC_NS_PLUGIN_DIR.'async/apf-save-simple-product.php');
require_once( RAC_NS_PLUGIN_DIR.'async/apf-product-attributes.php');

// add_action( 'plugins_loaded', 'blank_slate_bootstrap' );
