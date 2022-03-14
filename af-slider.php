<?php

/**
 * Plugin Name: AF Slider
 * Plugin URI: https://adastra-creative.com
 * Description: A simple Image slider
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Amar Filali
 * Author URI: https://amarfilali.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: af-slider
 * Domain Path: /languages
 */

/*AF Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
AF Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with AF Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH')) {
    die("This is just a wordpress plugin");
    exit;
}


if (!class_exists('AF_Slider')) {
    class AF_Slider
    {
        function __construct()
        {
            $this->define_constants();

            $this->load_textdomain();

            add_action('admin_menu', array($this, 'add_menu'));

            require_once(AF_SLIDER_PATH . 'post-types/class.af-slider-cpt.php');
            $AF_Slider_Post_Type = new AF_Slider_post_type();

            require_once(AF_SLIDER_PATH . 'class.af-slider-settings.php');
            $AF_Slider_settings = new AF_Slider_Settings();

            require_once(AF_SLIDER_PATH . 'shortcodes/class.af-slider-shortcode.php');
            $AF_Slider_Shortcode = new AF_Slider_Shortcode();

            add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 999);

            add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        }

        public function define_constants()
        {
            define('AF_SLIDER_PATH', plugin_dir_path(__FILE__));
            define('AF_SLIDER_URL', plugin_dir_url(__FILE__));
            define('AF_SLIDER_VERSION', '1.0.0');
        }

        public static function activate()
        {
            update_option('rewrite_rules', '');
        }

        public static function deactivate()
        {
            flush_rewrite_rules();
            unregister_post_type('af-slider');
        }

        public static function uninstall()
        {
            delete_option('af_slider_options');

            $posts = get_posts(
                array(
                    'post_type' => 'af-slider',
                    'number_posts' => -1,
                    'post_status' => 'any'
                )
            );

            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        public function load_textdomain()
        {
            load_plugin_textdomain(
                'af-slider',
                false,
                dirname(plugin_basename(__FILE__))  . '/languanges/'
            );
        }

        public function add_menu()
        {
            add_menu_page(
                esc_html__('AF Slider Options', 'af-slider'),
                esc_html__('AF Slider', 'af-slider'),
                'manage_options',
                'af_slider_admin',
                array($this, 'af_slider_settings_page'),
                'dashicons-images-alt2',
            );

            add_submenu_page(
                'af_slider_admin',
                esc_html__('Manage slides', 'af-slider'),
                esc_html__('Manage slides', 'af-slider'),
                'manage_options',
                'edit.php?post_type=af-slider',
                null,
                null
            );

            add_submenu_page(
                'af_slider_admin',
                esc_html__('Add new slide', 'af-slider'),
                esc_html__('Add new Slide', 'af-slider'),
                'manage_options',
                'post-new.php?post_type=af-slider',
                null,
                null
            );
        }

        public function af_slider_settings_page()
        {
            if (!current_user_can('manage_options')) {
                return;
            }
            if (isset($_GET['settings-updated'])) {
                add_settings_error('af_slider_options', 'af_slider_message', __('Settings Saved', 'af-slider'), 'success');
            }
            settings_errors('af_slider_options');

            require(AF_SLIDER_PATH . 'views/settings-page.php');
        }

        public function register_scripts()
        {
            wp_register_script('af-slider-main-jq', AF_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js', array('jquery'), AF_SLIDER_VERSION, true);
            wp_register_script('af-slider-options-js', AF_SLIDER_URL . 'vendor/flexslider/flexslider.js', array('jquery'), AF_SLIDER_VERSION, true);
            wp_register_style('af-slider-main-css', AF_SLIDER_URL . 'vendor/flexslider/flexslider.css', array(), AF_SLIDER_VERSION, 'all');
            wp_register_style('af-slider-style-css', AF_SLIDER_URL . "assets/css/frontend.css", array(), AF_SLIDER_VERSION, 'all');
        }

        public function register_admin_scripts()
        {
            global $typenow;
            if ($typenow == 'af-slider') {
                wp_enqueue_style('af-slider-admin', AF_SLIDER_URL . 'assets/css/admin.css', array(), AF_SLIDER_VERSION, 'all');
            }
        }
    }
}

if (class_exists('AF_Slider')) {
    register_activation_hook(__FILE__, array('AF_Slider', 'activate'));
    register_deactivation_hook(__FILE__, array('AF_Slider', 'deactivate'));
    register_uninstall_hook(__FILE__, array('AF_Slider', 'uninstall'));
    $af_slider = new AF_Slider();
}
