<?php
add_filter('up_cache_control_actions', 'other_custom_cache_actions');

function other_custom_cache_actions($actions) {
    $actions[] = [
        'slug' => 'flush-my-custom-cache',
        'label' => __('My Custom Cache', 'my-theme'),
        'callback' => function () {
            // Your code to flush the custom cache goes here
            // For example:
            delete_transient('my_custom_cache_transient');
            echo '<div class="notice notice-success is-dismissible"><p>' . __('My Custom Cache cleared!', 'my-theme') . '</p></div>';
        },
    ];

    return $actions;
}