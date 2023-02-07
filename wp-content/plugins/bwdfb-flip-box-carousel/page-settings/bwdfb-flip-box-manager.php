<?php
namespace Wppflipbox\PageSettings;

use Elementor\Controls_Manager;
use Elementor\Core\DocumentTypes\PageBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Page_Settings {

	const PANEL_TAB = 'new-tab';

	public function __construct() {
		add_action( 'elementor/init', [ $this, 'bwdfb_flip_box_add_panel_tab' ] );
		add_action( 'elementor/documents/register_controls', [ $this, 'bwdfb_flip_box_register_document_controls' ] );
	}

	public function bwdfb_flip_box_add_panel_tab() {
		Controls_Manager::add_tab( self::PANEL_TAB, esc_html__( 'Flip Box', 'bwdfb_flip_box' ) );
	}

	public function bwdfb_flip_box_register_document_controls( $document ) {
		if ( ! $document instanceof PageBase || ! $document::get_property( 'has_elements' ) ) {
			return;
		}

		$document->start_controls_section(
			'bwdfb_flip_box_new_section',
			[
				'label' => esc_html__( 'Settings', 'bwdfb_flip_box' ),
				'tab' => self::PANEL_TAB,
			]
		);

		$document->add_control(
			'bwdfb_flip_box_text',
			[
				'label' => esc_html__( 'Title', 'bwdfb_flip_box' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Title', 'bwdfb_flip_box' ),
			]
		);

		$document->end_controls_section();
	}
}
