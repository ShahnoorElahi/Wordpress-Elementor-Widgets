<?php
/**
 * Plugin Name: My Custom Elementor Widget
 * Description: A custom Elementor widget based on the provided image, now in a single file.
 * Version: 1.0.1
 * Author: Your Name
 * Requires Plugins: elementor
 * Elementor tested up to: 3.30.0
 * Elementor Pro tested up to: 3.30.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ✅ Enqueue assets for frontend and Elementor editor
add_action( 'wp_enqueue_scripts', 'my_custom_widget_enqueue_assets' );
add_action( 'elementor/editor/before_enqueue_scripts', 'my_custom_widget_enqueue_assets' );

function my_custom_widget_enqueue_assets() {
    // Enqueue Bootstrap1 CSS
    $css_path_bootstrap = plugin_dir_path(__FILE__) . 'css/bootstrap1.min.css';
    $css_url_bootstrap = plugins_url('css/bootstrap1.min.css', __FILE__);
    if (file_exists($css_path_bootstrap)) {
        wp_enqueue_style(
            'my-custom-widget-bootstrap1',
            $css_url_bootstrap,
            [],
            filemtime($css_path_bootstrap)
        );
    }

    // Enqueue custom style1 CSS
    $styles_path = plugin_dir_path(__FILE__) . 'css/style1.css';
    $styles_url = plugins_url('css/style1.css', __FILE__);
    if (file_exists($styles_path)) {
        wp_enqueue_style(
            'my-custom-widget-style1',
            $styles_url,
            [],
            filemtime($styles_path)
        );
    }

    // Enqueue custom JS
    $js_path = plugin_dir_path(__FILE__) . 'js/new.js';
    $js_url = plugins_url('js/new.js', __FILE__);
    if (file_exists($js_path)) {
        wp_enqueue_script(
            'my-custom-widget-new',
            $js_url,
            [],
            filemtime($js_path),
            true
        );
    }
}

// ✅ Register widget after Elementor is loaded
add_action( 'elementor/widgets/register', 'my_custom_elementor_widget_init' );

function my_custom_elementor_widget_init() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    if ( ! class_exists( 'Elementor_My_Custom_Widget' ) ) {
        class Elementor_My_Custom_Widget extends \Elementor\Widget_Base {

            public function get_name() {
                return 'my-custom-single-file-widget';
            }

            public function get_title() {
                return esc_html__( 'Single File Custom Widget', 'my-custom-widget' );
            }

            public function get_icon() {
                return 'eicon-image-box';
            }

            public function get_categories() {
                return [ 'general' ];
            }

            public function get_keywords() {
                return [ 'custom', 'repeater', 'image', 'description', 'single file', 'My' ];
            }

            protected function _register_controls() {
                $this->start_controls_section(
                    'content_section',
                    [
                        'label' => esc_html__( 'Content', 'my-custom-widget' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                );

                $repeater = new \Elementor\Repeater();

                $repeater->add_control(
                    'item_title',
                    [
                        'label' => esc_html__( 'Title', 'my-custom-widget' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__( 'Item Title', 'my-custom-widget' ),
                        'placeholder' => esc_html__( 'Enter your title', 'my-custom-widget' ),
                        'label_block' => true,
                    ]
                );

                $repeater->add_control(
                    'item_description',
                    [
                        'label' => esc_html__( 'Description', 'my-custom-widget' ),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => esc_html__( 'This is a description.', 'my-custom-widget' ),
                        'placeholder' => esc_html__( 'Enter your description', 'my-custom-widget' ),
                        'rows' => 5,
                        'separator' => 'after',
                    ]
                );

                $repeater->add_control(
                    'item_image',
                    [
                        'label' => esc_html__( 'Choose Image', 'my-custom-widget' ),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ]
                );

                $this->add_control(
                    'my_repeater_list',
                    [
                        'label' => esc_html__( 'Repeater List', 'my-custom-widget' ),
                        'type' => \Elementor\Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                            [
                                'item_title' => esc_html__( 'Item #1 Title', 'my-custom-widget' ),
                                'item_description' => esc_html__( 'Description for Item #1', 'my-custom-widget' ),
                                'item_image' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
                            ],
                        ],
                        'title_field' => '{{{ item_title }}}',
                    ]
                );

                $this->end_controls_section();

                // Style controls
                $this->start_controls_section(
                    'style_section',
                    [
                        'label' => esc_html__( 'Style', 'my-custom-widget' ),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    ]
                );

                $this->add_control(
                    'title_color',
                    [
                        'label' => esc_html__( 'Title Color', 'my-custom-widget' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .my-custom-widget-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    \Elementor\Group_Control_Typography::get_type(),
                    [
                        'name' => 'title_typography',
                        'selector' => '{{WRAPPER}} .my-custom-widget-title',
                    ]
                );

                $this->add_control(
                    'description_color',
                    [
                        'label' => esc_html__( 'Description Color', 'my-custom-widget' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} .my-custom-widget-description' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_group_control(
                    \Elementor\Group_Control_Typography::get_type(),
                    [
                        'name' => 'description_typography',
                        'selector' => '{{WRAPPER}} .my-custom-widget-description',
                    ]
                );

                $this->end_controls_section();
            }

            protected function render() {
                $settings = $this->get_settings_for_display();
                $fg = 1;

                if ( $settings['my_repeater_list'] ) {
                    echo '<div class="container-fluid1 px-01 mb-51 my-custom-widget-wrapper">
                        <div id="header-carousel1" class="carousel1 slide1" data-bs-ride1="carousel1">
                            <div class="carousel-inner1">';

                    foreach ( $settings['my_repeater_list'] as $item ) {
                        $item_id = $item['_id'];
                        if ($fg == 1) {
                            echo '<div class="carousel-item1 active1 my-custom-widget-item elementor-repeater-item-' . esc_attr( $item_id ) . '">';
                            $fg = 0;
                        } else {
                            echo '<div class="carousel-item1 my-custom-widget-item elementor-repeater-item-' . esc_attr( $item_id ) . '">';
                        }

                        if ( ! empty( $item['item_image']['url'] ) ) {
                            $image_id = $item['item_image']['id'];
                            if ( $image_id ) {
                                echo wp_get_attachment_image( $image_id, '', false, [ 'class' => 'w-1001 my-custom-widget-image' ] );
                            } else {
                                echo '<img src="' . esc_url( $item['item_image']['url'] ) . '" alt="' . esc_attr( $item['item_title'] ) . '" class="" />';
                            }
                        }

                        echo '<div class="carousel-caption1">
                                <div class="container1">
                                    <div class="row1 justify-content-center1">
                                        <div class="col-lg-101 text-start1">';

                        if ( ! empty( $item['item_description'] ) ) {
                            echo '<p class="my-custom-widget-description fs-51 fw-medium1 text-primary1 text-uppercase1 animated1 slideInRight1">' . wp_kses_post( $item['item_description'] ) . '</p>';
                        }

                        if ( ! empty( $item['item_title'] ) ) {
                            echo '<h1 class="my-custom-widget-title display-11 text-white1 mb-51 animated1 slideInRight1">' . esc_html( $item['item_title'] ) . '</h1>';
                        }

                        echo '<a href="" class="btn1 btn-primary1 py-31 px-51 animated1 slideInRight1">Explore More</a>';

                        echo '</div></div></div></div></div>';
                    }

                    echo '</div>
                    <button class="carousel-control-prev1" type="button" data-bs-target="#header-carousel1" data-bs-slide="prev1">
                        <span class="carousel-control-prev-icon1" aria-hidden="true"></span>
                        <span class="visually-hidden1">Previous</span>
                    </button>
                    <button class="carousel-control-next1" type="button" data-bs-target="#header-carousel1" data-bs-slide="next1">
                        <span class="carousel-control-next-icon1" aria-hidden="true"></span>
                        <span class="visually-hidden1">Next</span>
                    </button>
                </div>
            </div>';
                }
            }
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register( new Elementor_My_Custom_Widget() );
}
