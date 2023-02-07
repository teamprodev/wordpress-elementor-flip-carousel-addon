<?php
/**
 * Plugin Name: BWD Flip Box Carousel
 * Description:  BWD Flip box Carousel is an Elementor plugin that includes 31+ preset designs and all content customizable
 * Plugin URI:  https://bestwpdeveloper.com/flip-box-carousel
 * Version:     1.0
 * Author:      Best WP Developer
 * Author URI:  https://bestwpdeveloper.com/
 * Text Domain: bwdfb-flip-box
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.7.3
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once ( plugin_dir_path(__FILE__) ) . '/includes/requires-check.php';

final class FinalbwdfbBox{

	const VERSION = '1.0';

	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	const MINIMUM_PHP_VERSION = '7.0';

	public function __construct() {
		// Load translation
		add_action( 'bwdfb_init', array( $this, 'bwdfb_loaded_textdomain' ) );
		// bwdfb_init Plugin
		add_action( 'plugins_loaded', array( $this, 'bwdfb_init' ) );
	}

	public function bwdfb_loaded_textdomain() {
		load_plugin_textdomain( 'bwdfb-flip-box' );
	}

	public function bwdfb_init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			// Elementor  activation check
			add_action( 'admin_notices', 'bwdfb_flip_box_register_required_plugins');
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'bwdfb_admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'bwdfb_admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'bwdfb-boots.php' );
	}


	public function bwdfb_admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'bwdfb-flip-box' ),
			'<strong>' . esc_html__( 'Team Box', 'bwdfb-flip-box' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'bwdfb-flip-box' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'bwdfb-flip-box') . '</p></div>', $message );
	}

	public function bwdfb_admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'bwdfb-flip-box' ),
			'<strong>' . esc_html__( 'Team Box', 'bwdfb-flip-box' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'bwdfb-flip-box' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'bwdfb-flip-box') . '</p></div>', $message );
	}
}

// Instantiate bwdfb-flip-box.
new FinalbwdfbBox();
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );