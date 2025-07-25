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
    exit; // Exit if accessed directly.
}

/**
 * Register a custom Elementor widget.
 *
 * @since 1.0.1
 */
function my_custom_elementor_widget_init() {
    // We need to ensure Elementor is loaded.
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Now, define the widget class directly here.
    if ( ! class_exists( 'Elementor_My_Custom_Widget' ) ) {
        class Elementor_My_Custom_Widget extends \Elementor\Widget_Base {

            /**
             * Get widget name.
             *
             * @since 1.0.1
             * @access public
             * @return string Widget name.
             */
            public function get_name() {
                return 'my-custom-single-file-widget';
            }

            /**
             * Get widget title.
             *
             * @since 1.0.1
             * @access public
             * @return string Widget title.
             */
            public function get_title() {
                return esc_html__( 'Single File Custom Widget', 'my-custom-widget' );
            }

            /**
             * Get widget icon.
             *
             * @since 1.0.1
             * @access public
             * @return string Widget icon.
             */
            public function get_icon() {
                return 'eicon-image-box'; // Using a relevant Elementor icon
            }

            /**
             * Get widget categories.
             *
             * @since 1.0.1
             * @access public
             * @return array Widget categories.
             */
            public function get_categories() {
                return [ 'general' ];
            }

            /**
             * Get widget keywords.
             *
             * @since 1.0.1
             * @access public
             * @return array Widget keywords.
             */
            public function get_keywords() {
                return [ 'custom', 'repeater', 'image', 'description', 'single file', 'My' ];
            }

            /**
             * Register widget controls.
             *
             * Adds different input fields to allow the user to change and customize the widget settings.
             *
             * @since 1.0.1
             * @access protected
             */
            protected function _register_controls() {
                $this->start_controls_section(
                    'content_section',
                    [
                        'label' => esc_html__( 'Content', 'my-custom-widget' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                );

                // Initialize the repeater control
                $repeater = new \Elementor\Repeater();

                // Add controls to the repeater item
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

                // // Get WordPress default image sizes
                // $image_sizes = get_intermediate_image_sizes();
                // $image_sizes_options = [ 'full' => esc_html__( 'Full', 'my-custom-widget' ) ];
                // foreach ( $image_sizes as $size_name ) {
                //     $image_sizes_options[ $size_name ] = ucwords( str_replace( '-', ' ', $size_name ) );
                // }

                // $repeater->add_control(
                //     'item_image_resolution',
                //     [
                //         'label' => esc_html__( 'Image Resolution', 'my-custom-widget' ),
                //         'type' => \Elementor\Controls_Manager::SELECT,
                //         'options' => $image_sizes_options,
                //         'default' => 'medium',
                //     ]
                // );

                // Add the repeater control to the main widget
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
                                'item_image_resolution' => 'medium',
                            ],
                            [
                                'item_title' => esc_html__( 'Item #2 Title', 'my-custom-widget' ),
                                'item_description' => esc_html__( 'Description for Item #2', 'my-custom-widget' ),
                                'item_image' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
                                'item_image_resolution' => 'medium',
                            ],
                        ],
                        'title_field' => '{{{ item_title }}}', // This will show the title in the repeater item header
                    ]
                );

                $this->end_controls_section();

                // You can add more style controls sections here if needed
                $this->start_controls_section(
                    'style_section',
                    [
                        'label' => esc_html__( 'Style', 'my-custom-widget' ),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    ]
                );

                // Example style control for title
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

                // Example style control for description
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

            /**
             * Render widget output on the frontend.
             *
             * Written in PHP and used to generate the final HTML.
             *
             * @since 1.0.1
             * @access protected
             */
            protected function render() {
                $settings = $this->get_settings_for_display();
                $fg=1;
                if ( $settings['my_repeater_list'] ) {
                    
                    // <!-- Carousel Start -->
                    echo '<div class="container-fluid px-0 mb-5 my-custom-widget-wrapper">
                        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">';


                    foreach ( $settings['my_repeater_list'] as $item ) {
                        $item_id = $item['_id']; // Unique ID for each repeater item
                        if($fg==1){
                            echo '<div class="carousel-item '.'active my-custom-widget-item elementor-repeater-item-' . esc_attr( $item_id ) . '">';
                            $fg=0;
                        }else{
                            echo '<div class="carousel-item my-custom-widget-item elementor-repeater-item-' . esc_attr( $item_id ) . '">';
                        }
                        // Render image
                        if ( ! empty( $item['item_image']['url'] ) ) {
                            $image_id = $item['item_image']['id'];
                            // $image_size = $item['item_image_resolution'];

                            if ( $image_id ) {
                                echo wp_get_attachment_image( $image_id, "", false, [ 'class' => 'w-100 my-custom-widget-image' ] );
                            } else {
                                // Fallback for external URLs or if image ID is not set
                                echo '<img src="' . esc_url( $item['item_image']['url'] ) . '" alt="' . esc_attr( $item['item_title'] ) . '" class="" />';
                            }
                        }

                        echo '<div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-10 text-start">';
                        
                        // Render description
                         if ( ! empty( $item['item_description'] ) ) {
                            echo '<p class="my-custom-widget-description fs-5 fw-medium text-primary text-uppercase animated slideInRight">' . wp_kses_post( $item['item_description'] ) . '</p>';
                        }
                        
                        // Render title
                        if ( ! empty( $item['item_title'] ) ) {
                            echo '<h1 class="my-custom-widget-title display-1 text-white mb-5 animated slideInRight">' . esc_html( $item['item_title'] ) . '</h1>';
                        }

                        echo '<a href="" class="btn btn-primary py-3 px-5 animated slideInRight">Explore More</a>'; 


                        echo '          </div>
                                    </div>
                                </div>
                            </div>
                        </div>'; // .my-custom-widget-item
                    }
                    


                    echo '</div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>';
            // <!-- Carousel End -->
                }
            }
        }
    }

    // Register the widget
    \Elementor\Plugin::instance()->widgets_manager->register( new Elementor_My_Custom_Widget() );
}
add_action( 'elementor/widgets/register', 'my_custom_elementor_widget_init' );
