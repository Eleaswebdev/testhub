<?php 
/**
 * * @wordpress-plugin
 * 
 * Plugin Name:       Hub Order Plugin
 * Plugin URI:        https://https://github.com/eleaswebdev
 * Description:       Sends updated order meta to store and sync with WooCommerce.
 * Version:           1.0.0
 * Author:            Eleas Kanchon
 * Author URI:        https://https://github.com/eleaswebdev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hub-order-plugin
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}    
// Load plugin text domain
add_action('plugins_loaded', 'wppool_load_hub_order_plugin_textdomain');
function wppool_load_hub_order_plugin_textdomain() {
    load_plugin_textdomain( 'hub-order-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

require_once plugin_dir_path(__FILE__) . 'includes/rest-api-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/orders-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/order-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/order-meta-management.php';
require_once plugin_dir_path(__FILE__) . 'includes/order-sync.php';
require_once plugin_dir_path(__FILE__) . 'includes/order-filter.php';

add_action('wp_enqueue_scripts', 'wppool_enq_react');
function wppool_enq_react(){
    wp_register_script('display-react',
    plugin_dir_url( __FILE__ ) . '/build/index.js',
    ['wp-element'],
    rand(), // Change this to null for production
    true);
    wp_enqueue_script( 'display-react' );    
}
function wppool_enqueue_styles() {
    wp_enqueue_style('hub-order-plugin-css', plugin_dir_url(__FILE__) . '/css/style.css');
}
add_action('admin_enqueue_scripts', 'wppool_enqueue_styles');

?>