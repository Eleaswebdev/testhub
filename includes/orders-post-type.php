<?php

// Register custom post type for Orders
add_action('init', 'wppool_register_order_post_type');

function wppool_register_order_post_type() {
    register_post_type('orders', array(
        'labels' => array(
            'name' => __('Orders', 'hub-order-plugin'),
            'singular_name' => __('Order', 'hub-order-plugin'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_in_menu' => true,
        'supports' => array('title'),
    ));
}