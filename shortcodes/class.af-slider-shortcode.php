<?php

if( ! class_exists('AF_Slider_Shortcode') ){
    class AF_Slider_Shortcode{
        public function __construct(){
            add_shortcode( 'af_slider', array( $this, 'add_shortcode' ) );
        }

        public function add_shortcode( $atts = array(), $content = null, $tag = '' ){

            $atts = array_change_key_case( (array) $atts, CASE_LOWER );

            extract( shortcode_atts( 
                array(
                    'id' => '',
                    'orderby' => 'date'
                ), 
                $atts,
                $tag
            ));

            if( !empty( $id ) ){
                $id = array_map( 'absint', explode( ',', $id ) );
            }

            ob_start();
            require( AF_SLIDER_PATH . 'views/af-slider_shortcode.php' );
            wp_enqueue_script( 'af-slider-main-jq' );
            wp_enqueue_script( 'af-slider-options-js' );
            wp_localize_script( 'af-slider-options-js', 'afSliderOptions', array( 'bullets' => ( isset( AF_Slider_settings::$options['af_slider_bullets'] ) ) ? true : false ));
            wp_enqueue_style( 'af-slider-main-css' );
            wp_enqueue_style( 'af-slider-style-css' );

            return ob_get_clean();

        }
    }
}