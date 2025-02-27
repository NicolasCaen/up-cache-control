<?php
/**
 * Plugin Name: Up Cache Control
 * Description: Adds cache clearing options to the admin menu and toolbar, executed via AJAX.
 * Version: 1.5
 * Author: GEHIN nicolas
 * License: GPL2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class UpCacheControl {
    private $cache_actions = [];

    public function __construct() {
        // Define cache actions
        $this->define_cache_actions();

        // Add admin bar buttons
        add_action('admin_bar_menu', [$this, 'add_admin_bar_buttons'], 999);

        // Allow external addition of cache actions
        add_action('plugins_loaded', [$this, 'load_external_cache_actions']);

        // Add AJAX action
        add_action('wp_ajax_up_flush_cache', [$this, 'ajax_flush_cache']);
    }

    private function define_cache_actions() {
        $this->add_cache_action('flush-general', __('😡 General Cache', 'up'), function () {
            wp_cache_flush();
            wp_clean_themes_cache();
        });

        $this->add_cache_action('flush-gutenberg', __('😎 Gutenberg Cache', 'up'), function () {
            delete_transient('_wp_block_patterns_cache');
            delete_transient('_wp_block_pattern_categories_cache');
            delete_transient('_wp_block_styles_cache');
            delete_transient('_wp_gutenberg_features');
            delete_transient('block_templates');
            delete_transient('global_styles');
            wp_clean_themes_cache();
        });

        $this->add_cache_action('flush-transients', __('😛 Transients', 'up'), function () {
            global $wpdb;
            $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%'");
        });
    }

    public function load_external_cache_actions() {
        $external_actions = apply_filters('up_cache_control_actions', []);
        if (is_array($external_actions)) {
            foreach ($external_actions as $action) {
                if (isset($action['slug'], $action['label'], $action['callback']) && is_callable($action['callback'])) {
                    $this->add_cache_action($action['slug'], $action['label'], $action['callback']);
                }
            }
        }
    }

    public function add_cache_action($slug, $label, $callback) {
        $this->cache_actions[$slug] = ['label' => $label, 'callback' => $callback];
    }

    public function ajax_flush_cache() {
        check_ajax_referer('up-cache-nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Insufficient permissions.', 'up')]);
        }

        $action_type = sanitize_key($_REQUEST['type']);
        error_log('Cache action reçue : ' . $action_type);

        if (isset($this->cache_actions[$action_type])) {
            call_user_func($this->cache_actions[$action_type]['callback']);
            wp_send_json_success(['message' => __('Cache flushed successfully.', 'up')]);
        } else {
            error_log('Invalid cache action: ' . $action_type);
            wp_send_json_error(['message' => __('Invalid cache action.', 'up')]);
        }
    }

    public function add_admin_bar_buttons($admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }

        wp_enqueue_script('up-cache-control-admin', plugin_dir_url(__FILE__) . 'assets/js/clear-cache.js', ['jquery'], '1.0', true);
        wp_localize_script('up-cache-control-admin', 'upCacheControl', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('up-cache-nonce'),
        ]);

        $admin_bar->add_node([
            'id'    => 'up-cache-control-menu',
            'title' => __('😎', 'up'),
            'href'  => '#',
            'meta'  => ['title' => __('Manage Up Cache', 'up')],
        ]);

        foreach ($this->cache_actions as $slug => $action) {
            $admin_bar->add_node([
                'id'     => 'flush-cache-' . $slug,
                'title'  => $action['label'],
                'href'   => admin_url('admin-ajax.php') . '?action=up_flush_cache&type=' . $slug . '&nonce=' . wp_create_nonce('up-cache-nonce'),
                'parent' => 'up-cache-control-menu',
                'meta'   => [
                    'title' => $action['label'],
                    'class' => 'up-cache-flush-button',
                ],
            ]);
        }
    }
}

new UpCacheControl();
