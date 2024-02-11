<?php
/**
 * Plugin Name:       Shopify Buy Button
 * Description:       Shortcodes for using the Shopify Buy Button.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            drdogbot7
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       shopify-buy-button
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load Carbon Fields
 */

 function crb_load() {
	require_once( 'vendor/autoload.php' );
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'plugin_loaded', 'crb_load' );


/**
 * Create Plugin Settings Page
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

function dbi_add_plugin_settings_page() {
	Container::make( 'theme_options', __( 'Shopify Buy Button' ) )
		->set_page_parent( 'options-general.php' )
		->add_fields( array(
			Field::make( 'text', 'sbb_shopify_domain', 'Shopify Domain' )
				->set_attribute( 'placeholder', 'your-store-name.myshopify.com'),
			Field::make( 'text', 'sbb_access_token', 'Storefront Access Token' )
				->set_attribute( 'placeholder', 'q5dfnjm8s73qrz4nmwnzd6rsnpz8c2jf')
		) );
}
add_action( 'carbon_fields_register_fields', 'dbi_add_plugin_settings_page' );


/**
 * Enqueue Scripts
 */
function sbb_enqueue_front()
{
	$asset_file = include plugin_dir_path(__FILE__) . 'build/index.asset.php';
	wp_enqueue_script(
		'sbb_js',
		plugin_dir_url(__FILE__) . 'build/index.js',
		['jquery'],
		$asset_file['version']
	);

	$sbb_options = [
		'domain' => carbon_get_theme_option( 'sbb_shopify_domain' ) ?: false,
		'token' => carbon_get_theme_option( 'sbb_access_token' ) ?: false
	];
	
	wp_add_inline_script( 'sbb_js', 'const sbbOptions = ' . wp_json_encode( $sbb_options ), 'before' );

}
add_action('wp_enqueue_scripts', 'sbb_enqueue_front', 100);


/**
 * Register Shortcodes
 */

 function sbb_do_shopify_product( $atts ) {

	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts
	);
	if ($atts['id']) {
		return '<div><div class="shopify-product" data-id="' . $atts['id'] . '"></div></div>';
	}
}
add_shortcode( 'shopify-product', 'sbb_do_shopify_product' );

function sbb_do_shopify_collection( $atts ) {

	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts
	);
	
	if ($atts['id']) {
		return '<div><div class="shopify-collection" data-id="' . $atts['id'] . '"></div></div>';
	}
}
add_shortcode( 'shopify-collection', 'sbb_do_shopify_collection' );