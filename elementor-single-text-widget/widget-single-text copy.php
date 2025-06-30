<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class Elementor_Single_Text_Widget extends Widget_Base {

	public function get_name() {
		return 'single_text_widget';
	}

	public function get_title() {
		return __( 'Single Text Widget', 'plugin-name' );
	}

	public function get_icon() {
		return 'eicon-editor-text';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text_input',
			[
				'label' => __( 'Text Input', 'plugin-name' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your text here', 'plugin-name' ),
				'default' => '',
			]
		);

		$this->add_control(
			'text_list',
			[
				'label' => __( 'Text Inputs', 'plugin-name' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'text_input' => __( 'Example text', 'plugin-name' ) ],
				],
				'title_field' => '{{{ text_input }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['text_list'] ) ) {
			echo '<div class="single-text-widget">';
			foreach ( $settings['text_list'] as $item ) {
				echo '<p>' . esc_html( $item['text_input'] ) . '</p>';
			}
			echo '</div>';
		}
	}
}
