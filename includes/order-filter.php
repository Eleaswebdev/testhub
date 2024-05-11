<?php
/**
 * Funtionality to add custom filter in orders post type
 */

add_action('restrict_manage_posts', 'wppool_add_order_status_filter');

function wppool_add_order_status_filter() {
    global $typenow;
    if ($typenow == 'orders') {
        $selected = isset($_GET['order_status']) ? $_GET['order_status'] : '';
        $statuses = array(
            'pending-payment' => __('Pending', 'hub-order-plugin'),
            'processing'      => __('Processing', 'hub-order-plugin'),
            'completed'       => __('Completed', 'hub-order-plugin'),
            'cancelled'       => __('Cancelled', 'hub-order-plugin'),
            'on-hold'         => __('On Hold', 'hub-order-plugin'),
            'refunded'        => __('Refunded', 'hub-order-plugin'),
            'failed'          => __('Failed', 'hub-order-plugin'),
            'draft'           => __('Draft', 'hub-order-plugin')
        );

        echo '<select name="order_status">';
        echo '<option value="">' . __('Filter by Order Status', 'hub-order-plugin') . '</option>';
        foreach ($statuses as $key => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                $key,
                selected($selected, $key, false),
                $label
            );
        }
        echo '</select>';
    }
}

// Modify main query based on selected order status
add_action('pre_get_posts', 'wppool_modify_order_query');

function wppool_modify_order_query($query) {
    global $pagenow;

    // Ensure this is your custom post type and it's the main query on admin page
    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'orders' && isset($_GET['order_status']) && $_GET['order_status'] != '') {
        $query->set('meta_key', 'status');
        $query->set('meta_value', $_GET['order_status']);
    }
}

add_filter('manage_edit-orders_columns', 'wppool_add_order_status_column');

function wppool_add_order_status_column($columns) {
    // Add a new column for status
    $columns['order_status'] = __('Status', 'hub-order-plugin');
    return $columns;
}

// Populate custom column with data
add_action('manage_orders_posts_custom_column', 'wppool_populate_order_status_column', 10, 2);

function wppool_populate_order_status_column($column, $post_id) {
    // If the column is for "Status"
    if ($column === 'order_status') {
        // Retrieve the status meta field value for the post
        $status = get_post_meta($post_id, 'status', true);

        // Output the status
        echo esc_html($status);
    }
}