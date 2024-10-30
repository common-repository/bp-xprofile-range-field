<?php

/*
  Plugin Name: BP Xprofile Range Field Type
  Plugin URI: http://askaryabbas.com/plugins/bp-xprofile-range-field
  Description: This is BuddyPress Add-On plugin will add 'Range Field' types to BuddyPress Xprofile fields.
  Version: 1.2.1
  Author: Askary Abbas
  Author URI: http://askaryabbas.com
 */
if (!class_exists('Abp_Range_Slider')) {

    class Abp_Range_Slider {

        private $version;
        private $fields_type_with_select;
        private $abp_field_types = array('slider' => 'Abp_Field_Type_Range');

        function __construct() {
            $this->version = "1.2.1";
            add_action('plugins_loaded', array($this, 'abp_update'));
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_notices', array($this, 'admin_notices'));
            add_action('bp_init', array($this, 'init'));
            add_filter('bp_field_validation_type', array($this, 'abp_fields_validation_type'));
            add_filter('bp_field_type_for_query', array($this, 'abp_fields_for_query'));
            add_filter('bp_field_type_for_search_form', array($this, 'abp_fields_type_for_search_form'));
            add_filter('bp_xprofile_get_field_types', array($this, 'abp_get_field_types'), 10, 1);
        }

        public function abp_update() {
            $locale = apply_filters('abp_load_load_textdomain_get_locale', get_locale());
            if (!empty($locale)) {
                $mo_file_default = sprintf('%slang/%s.mo', plugin_dir_path(__FILE__), $locale);
                $mo_file = apply_filters('abp_load_textdomain_mofile', $mo_file_default);
                if (file_exists($mo_file)) {
                    load_textdomain("abp", $mo_file);
                }
            }
            if (!get_option('abp_activated')) {
                add_option('abp_activated', 1);
            }
            if (!get_option('abp_notices')) {
                add_option('abp_notices');
            }
        }

        public function admin_init() {
            if (is_admin() && get_option('abp_activated') == 1) {
                // Check if BuddyPress is installed.
                $version_bp = 0;
                if (function_exists('is_plugin_active') && is_plugin_active('buddypress/bp-loader.php')) {
                    // BuddyPress loaded.
                    $data = get_file_data(WP_PLUGIN_DIR . '/buddypress/bp-loader.php', array('Version'));
                    if (isset($data) && count($data) > 0 && $data[0] != '') {
                        $version_bp = (float) $data[0];
                    }
                }
                if ($version_bp < 2.5) {
                    $notices = get_option('abp_notices');
                    $notices[] = __('BuddyPress Xprofile Range Fields Type plugin needs <b>BuddyPress 2.5</b>, please install or upgrade BuddyPress.', 'bxcft');
                    update_option('abp_notices', $notices);
                    delete_option('abp_activated');
                }

                // Enqueue javascript.
                wp_enqueue_script('abp-js', plugin_dir_url(__FILE__) . 'js/admin.js', array(), $this->version, false);
                wp_localize_script('abp-js', 'fields_type_with_select', array('types' => $this->fields_type_with_select));

                if (isset($_GET['page']) && $_GET['page'] === 'bp-profile-edit') {
                    $this->load_js();
                }
            }
        }

        public function admin_notices() {
            $notices = get_option('abp_notices');
            if ($notices) {
                foreach ($notices as $notice) {
                    echo "<div class='error'><p>$notice</p></div>";
                }
                delete_option('abp_notices');
            }
        }

        public function abp_get_field_types($fields) {
            $fields = array_merge($fields, $this->abp_field_types);
            return $fields;
        }

        public function init() {
            require_once( 'classes/Abp_Field_Type_Slider.php' );
            if (bp_is_user_profile_edit() || bp_is_register_page()) {
                $this->load_js();
            }
        }

        public function load_js() {
            wp_enqueue_script('abp-public', plugin_dir_url(__FILE__) . 'js/public.js', array('jquery'), $this->version, true);
        }

        public function abp_fields_validation_type($field_type) {
            if ($field_type == 'slider') {
                $field_type = 'number';
            }
            return $field_type;
        }

        public function abp_fields_for_query($field_type) {
            if ($field_type == 'slider') {
                $field_type = 'number';
            }
            return $field_type;
        }

        public function abp_fields_type_for_search_form($field_type) {
            if ($field_type == 'slider') {
                $field_type = 'number';
            }
            return $field_type;
        }
        public static function activate() {
            add_option('abp_activated', 1);
            add_option('abp_notices', array());
        }

        public static function deactivate() {
            delete_option('abp_activated');
            delete_option('abp_notices');
        }

    }

}
if (class_exists('Abp_Range_Slider')) {
    register_activation_hook(__FILE__, array('Abp_Range_Slider', 'activate'));
    register_deactivation_hook(__FILE__, array('Abp_Range_Slider', 'deactivate'));
    $abp_range_slider = new Abp_Range_Slider();
}