<?php
/**
 * WooCommerce Recently Viewed Products from all visitors by Samsys
 *
 * @package   samsys_WC_recently_viewed
 * @author    Ricardo Correia, Samsys <ricardo.correia@samsys.pt>
 * @license   GPL-2.0+
 * @link      http://samsys.pt
 * @copyright 2013 Samsys - Consultoria e Soluções Informáticas, Lda.
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Recently Viewed Products from all visitors by Samsys
 * Plugin URI:        http://samsys.pt
 * Description:       Displays recently viewed products from all website visitors in a widget
 * Version:           1.0.0
 * Author:            Ricardo Correia, Samsys
 * Author URI:        http://profiles.wordpress.org/ricardocorreia
 * Text Domain:       samsys-WC-recently-viewed
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * 
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action('plugins_loaded', 'woocommerce_ssys_recently_viewed_init', 0);
require_once( plugin_dir_path( __FILE__ ) . 'ssys-recently-viewed-widget.php' );

/**
 * Initialize the plugin by setting localization and loading public scripts,
 * styles and Actions
 *
 * @since     1.0.0
 */
function woocommerce_ssys_recently_viewed_init() {
	
	//Hook Save Last Viewed Date to Single Product Page
	add_action( 'woocommerce_before_single_product','ssys_Generate_CF_save_visits' );
	
	// Load plugin text domain
	add_action( 'init', 'Ssys_WC_recently_viewed_load_plugin_textdomain' );
}

/**
 * Load the plugin text domain for translation.
 *
 * @since    1.0.0
 */
function Ssys_WC_recently_viewed_load_plugin_textdomain() {

	$domain = 'woocommerce-ssys-recently-viewed';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	
}

/**
 * Initialize Hidden Custom Field And Save This Product Last Viewed Date, Hooks to 
 *
 * @since     1.0.0
 */
function ssys_Generate_CF_save_visits(){
	global $post;
	
	update_post_meta($post->ID, '_ssys_Last_Viewed_Date', date('U') );
	
}



?>