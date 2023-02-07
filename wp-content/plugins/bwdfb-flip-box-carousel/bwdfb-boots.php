<?php
namespace Wppflipbox;

use Wppflipbox\PageSettings\Page_Settings;
define( "bwdfb_ASFSK_ASSETS_PUBLIC_DIR_FILE", plugin_dir_url( __FILE__ ) . "assets/public" );
define( "bwdfb_ASFSK_ASSETS_ADMIN_DIR_FILE", plugin_dir_url( __FILE__ ) . "assets/admin" );

class Classbwdfbox {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function bwdfb_admin_editor_scripts() {
		add_filter( 'script_loader_tag', [ $this, 'bwdfb_admin_editor_scripts_as_a_module' ], 10, 2 );
	}

	public function bwdfb_admin_editor_scripts_as_a_module( $tag, $handle ) {
		if ( 'bwdfb_the_team_box_editor' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}

		return $tag;
	}

	private function include_widgets_files() {
		require_once( __DIR__ . '/widgets/bwdfb-flipbox.php' );
	}

	public function bwdfb_register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\bwdfbFlipBox() );
	}
	private function add_page_settings_controls() {
		require_once( __DIR__ . '/page-settings/bwdfb-flip-box-manager.php' );
		new Page_Settings();
	}


	// Register Category
	function bwdfb_add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'bwdfb-flip-box-category',
			[
				'title' => esc_html__( 'Flip Box', 'bwdfb-flip-box' ),
				'icon' => 'eicon-person',
			]
		);
	}
	public function bwdfb_all_assets_for_the_public(){

		wp_enqueue_script( 'bwdfb_flip_box_owlcarouseljs', plugin_dir_url( __FILE__ ) . 'assets/public/js/owl.carousel.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'bwdfb_flip_box_main', plugin_dir_url( __FILE__ ) . 'assets/public/js/main.js', array('jquery'), '1.0', true );
		
		$all_css_js_file = array(
			'bwdfb_flip_box_fontAwesome' => array('bwdfb_path_define'=>bwdfb_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/all.min.css'),
			'bwdfb_flip_box_animate' => array('bwdfb_path_define'=>bwdfb_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/animate.min.css'),
			'bwdfb_flip_box_owlcarousel' => array('bwdfb_path_define'=>bwdfb_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/owl.carousel.min.css'),
			'bwdfb_flip_box_owlcarouseltheme' => array('bwdfb_path_define'=>bwdfb_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/owl.theme.default.min.css'),
            'bwdfb_flip_box_style' => array('bwdfb_path_define'=>bwdfb_ASFSK_ASSETS_PUBLIC_DIR_FILE . '/css/style.css'),

        );
        foreach($all_css_js_file as $handle => $fileinfo){
            wp_enqueue_style( $handle, $fileinfo['bwdfb_path_define'], null, '1.0', 'all');
        }
	}

	public function bwdfb_all_assets_for_elementor_editor_admin(){
		$all_css_js_file = array(
            'bwdfb_admin_main_css' => array('bwdfb_path_admin_define'=>bwdfb_ASFSK_ASSETS_ADMIN_DIR_FILE . '/icon.css'),
        );
        foreach($all_css_js_file as $handle => $fileinfo){
            wp_enqueue_style( $handle, $fileinfo['bwdfb_path_admin_define'], null, '1.0', 'all');
        }
	}
	public function __construct() {
		// For public assets
		add_action('wp_enqueue_scripts', [$this, 'bwdfb_all_assets_for_the_public']);

		// For Elementor Editor
		add_action('elementor/editor/before_enqueue_scripts', [$this, 'bwdfb_all_assets_for_elementor_editor_admin']);
		
		// Register Category
		add_action( 'elementor/elements/categories_registered', [ $this, 'bwdfb_add_elementor_widget_categories' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'bwdfb_register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'bwdfb_admin_editor_scripts' ] );
		$this->add_page_settings_controls();
	}
}

// Instantiate Plugin Class
Classbwdfbox::instance();