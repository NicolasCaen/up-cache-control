<?php
/**
 * Plugin Name: Up Cache Control
 * Description: Adds cache clearing options to the admin menu and toolbar.
 * Version: 1.3
 * Author: GEHIN nicolas
 * License: GPL2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
include_once "extend/add-cache-control-item.php";
class UpCacheControl {
    private $cache_actions = [];

    public function __construct() {
        // Define cache actions
        $this->define_cache_actions();

        // Add admin menu page
        add_action('admin_menu', [$this, 'add_cache_flush_page']);

        // Add admin bar buttons
        add_action('admin_bar_menu', [$this, 'add_admin_bar_buttons'], 999);

        // Allow external addition of cache actions
        add_action('plugins_loaded', [$this, 'load_external_cache_actions']);
    }

    /**
     * Define the default cache actions.
     */
    private function define_cache_actions() {
        $this->add_cache_action(
            'flush-general',
            __('😡 General Cache', 'up'),
            function () {
                wp_cache_flush();
                wp_clean_themes_cache();
            }
        );

        $this->add_cache_action(
            'flush-gutenberg',
            __('😎 Gutenberg Cache', 'up'),
            function () {
                delete_transient('_wp_block_patterns_cache');
                delete_transient('_wp_block_pattern_categories_cache');
                delete_transient('_wp_block_styles_cache');
                delete_transient('_wp_gutenberg_features');
                delete_transient('block_templates');
                delete_transient('global_styles');
                wp_clean_themes_cache();
            }
        );

        $this->add_cache_action(
            'flush-transients',
            __('😛 Transients', 'up'),
            function () {
                global $wpdb;
                $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%'");
                echo '<div class="notice notice-success is-dismissible"><p>' . __('Transients vidés avec succès.', 'up') . '</p></div>';
            }
        );
    }

    /**
     * Allow external definition of cache actions through a filter.  Called on plugins_loaded action.
     */
    public function load_external_cache_actions() {
        $external_actions = apply_filters('up_cache_control_actions', []);
var_dump($external_actions);
        if (is_array($external_actions)) {
            foreach ($external_actions as $action) {
                if (isset($action['slug'], $action['label'], $action['callback']) && is_callable($action['callback'])) {
                    $this->add_cache_action($action['slug'], $action['label'], $action['callback']);
                } else {
                    error_log('Invalid Up Cache Control action provided: ' . print_r($action, true)); // Log invalid actions
                }
            }
        }
    }


    /**
     * Add a cache action.
     *
     * @param string   $slug     The unique slug for the action.
     * @param string   $label    The user-friendly label for the action.
     * @param callable $callback The callback function to execute.
     */
    public function add_cache_action($slug, $label, $callback) {
        $this->cache_actions[$slug] = [
            'label' => $label,
            'callback' => $callback
        ];
    }

    /**
     * Add the cache flush management page.
     */
    public function add_cache_flush_page() {
        add_management_page(
            __('Up Cache Control', 'up'),
            __('Up Cache Control', 'up'),
            'manage_options',
            'up-cache-control',
            [$this, 'render_cache_flush_page']
        );
    }

    /**
     * Render the cache flush page.
     */
    public function render_cache_flush_page() {
        if (isset($_GET['action']) && isset($_GET['type'])) {
            $action_type = sanitize_key($_GET['type']);
            if (isset($this->cache_actions[$action_type])) {
                check_admin_referer('flush-cache-nonce-' . $action_type);
                call_user_func($this->cache_actions[$action_type]['callback']);
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Cache vidé avec succès.', 'up') . '</p></div>';
            } else {
                echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('Invalid cache action.', 'up') . '</p></div>';
            }
        }

        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Up Cache Control', 'up'); ?></h1>
            <p><?php esc_html_e('Choose an action to clear specific caches.', 'up'); ?></p>

            <?php foreach ($this->cache_actions as $slug => $action): ?>
                <form method="get" style="margin-bottom: 20px;">
                    <input type="hidden" name="page" value="up-cache-control">
                    <input type="hidden" name="action" value="flush">
                    <input type="hidden" name="type" value="<?php echo esc_attr($slug); ?>">
                    <?php wp_nonce_field('flush-cache-nonce-' . $slug); ?>
                    <button type="submit" class="button button-primary"><?php echo esc_html($action['label']); ?></button>
                </form>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Add buttons to the admin bar.
     *
     * @param WP_Admin_Bar $admin_bar The admin bar object.
     */
    public function add_admin_bar_buttons($admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }

        $admin_bar->add_node([
            'id'    => 'up-cache-control-menu',
            'title' => __('Up Cache', 'up'),
            'href'  => admin_url('tools.php?page=up-cache-control'),
            'meta'  => ['title' => __('Manage Up Cache', 'up')],
        ]);

        foreach ($this->cache_actions as $slug => $action) {
            $admin_bar->add_node([
                'id'     => 'flush-cache-' . $slug,
                'title'  => apply_filters('up_cache_control_button_label', $action['label'], $slug),
                'href'   => add_query_arg([
                    'page'    => 'up-cache-control',
                    'action'  => 'flush',
                    'type'    => $slug,
                    '_wpnonce' => wp_create_nonce('flush-cache-nonce-' . $slug),
                ], admin_url('tools.php')),
                'parent' => 'up-cache-control-menu',
                'meta'   => [
                    'title' => apply_filters('up_cache_control_button_tooltip', $action['label'], $slug),
                ],
            ]);
        }
    }
       /**
     * Deletes the transient when the plugin is deactivated.
     */
    public function deactivate() {
        delete_transient( 'up_cache_control_external_actions' );
    }
}

// Instantiate the class
new UpCacheControl();