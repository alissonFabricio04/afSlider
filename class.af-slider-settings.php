<?php

if( ! class_exists('AF_Slider_exists') ){
    class AF_Slider_Settings{
        public static $options;

        public function __construct(){
            self::$options = get_option( 'af_slider_options' );
            add_action( 'admin_init', array( $this, 'admin_init' ) );
        }

        public function admin_init(){
            register_setting( 'af_slider_group', 'af_slider_options', array( $this, 'af_slider_validate' ) );

            add_settings_section(
                'af_slider_main_section',
                esc_html__( 'How does it word?', 'af-slider' ),
                null,
                'af_slider_page1'
            );
            
            add_settings_section(
                'af_slider_second_section',
                esc_html__( 'Other plugin options', 'af-slider' ),
                null,
                'af_slider_page2'
            );

            add_settings_field(
                'af_slider_shortcode',
                esc_html__( 'Shortcode', 'af-slider' ),
                array( $this, 'af_slider_shortcode_callback' ),
                'af_slider_page1',
                'af_slider_main_section'
            );

            add_settings_field(
                'af_slider_title',
                esc_html__( 'Slider title', 'af-slider' ),
                array( $this, 'af_slider_title_callback' ),
                'af_slider_page2',
                'af_slider_second_section',
                array(
                    'label_for' => 'af_slider_title'
                )
            );

            add_settings_field(
                'af_slider_bullets',
                esc_html__( 'Display Bullets', 'af-slider' ),
                array( $this, 'af_slider_bullets_callback' ),
                'af_slider_page2',
                'af_slider_second_section',
                array(
                    'label_for' => 'af_slider_bullets'
                )
            );

            add_settings_field(
                'af_slider_style',
                esc_html__( 'Slider Style','af-slider' ),
                array( $this, 'af_slider_style_callback'),
                'af_slider_page2',
                'af_slider_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2'
                    ),
                    'label_for' => 'af_slider_style'
                )
            );

        }

        public function af_slider_shortcode_callback(){
            ?>
                <span><?php esc_html_e('Use the shortcode [af_slider] to display the slider in any page/post/widget', 'af-slider'); ?></span>
            <?php
        }

        public function af_slider_title_callback( $args ){
            ?>
                <input type="text" name="af_slider_options[af_slider_title]" id="af_slider_title"  value="<?php echo ( isset( self::$options['af_slider_title'] ) ) ? esc_attr( self::$options['af_slider_title'] ) : '' ?>" />
            <?php
        }

        public function af_slider_bullets_callback( $args ){
            ?>
                <input 
                    type="checkbox" 
                    name="af_slider_options[af_slider_bullets]" 
                    id="af_slider_bullets" 
                    value="1" 
                    <?php
                        if( isset( self::$options['af_slider_bullets'] ) ){
                            checked( "1", self::$options["af_slider_bullets"], true );
                        }
                    ?>
                />
                <label for="af_slider_bullets"><?php esc_html_e( 'Whether to dosplay bullets or not', 'af-slider' ); ?></label>
            <?php
        }

        public function af_slider_style_callback( $args ){
            ?>
                <select name="af_slider_options[af_slider_style]" id="af_slider_Style">
                    <?php
                        foreach( $args['items'] as $item ){
                            ?>
                            <option value="<?php echo esc_attr($item) ?>" <?php ( isset( self::$options['af_slider_style'] ) ) ? selected( $item, self::$options['af_slider_style'], true ) : '' ?> >
                                <?php echo esc_html( ucfirst( str_replace( "-", " ", $item ) ) ); ?>
                            </option>        
                            <?php
                        }
                    ?>
                </select>
            <?php
        }

        public function af_slider_validate( $input ){
            $new_input = array();
                foreach( $input as $key => $value ){
                    $new_input[$key] = sanitize_text_field( $value );
                }
            return $new_input;
        }


    }
}