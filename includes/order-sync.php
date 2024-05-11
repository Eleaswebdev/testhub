<?php
/**
 * Funtionality to sync order that is coming from store
 */
add_action('rest_api_init', function() {
    register_rest_route('hub-order-plugin/v1', '/new-order', array(
        'methods' => 'POST', 
        'callback' => 'receive_new_order_from_store',
    ));
});

function receive_new_order_from_store($request) {
    $order_data = $request->get_json_params();
    $secret_key = get_option('rest_secret_key');
    $sent_secret_key = $_SERVER['HTTP_AUTHORIZATION'];
    if ($sent_secret_key !== 'Basic ' . base64_encode('wppool:' . $secret_key)) {
        return rest_ensure_response(array('success' => false, 'message' => 'Unauthorized access.'), 403);
    }

    $order_id = $order_data['order_id'];
    $post_id = wp_insert_post(array(
        'post_title' => 'Order #' . $order_id, 
        'post_type' => 'orders', 
        'post_status' => 'publish', 
    ));

    if ($post_id && $order_id) {
        foreach ($order_data as $meta_key => $meta_value) {
          update_post_meta($post_id, $meta_key, $meta_value);
        }
        return rest_ensure_response(array('success' => true, 'message' => 'Order created successfully.'));
    } else {
        return rest_ensure_response(array('success' => false, 'message' => 'Failed to create order.'));
    }
}

//send updated order info to store
add_action('save_post', 'wppool_send_update_data_to_store', 10, 3);

function wppool_send_update_data_to_store($post_id, $post, $update) {
    // Check if this is an auto save routine
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check if this is a revision
    if (wp_is_post_revision($post_id)) {
        return;
    }

    // Check post type
    if ($post->post_type != 'orders') {
        return;
    }
    $secret_key = get_option('rest_secret_key');
    $base_url = get_option('rest_base_url');
    $updated_order_id = isset($_POST['order_id']) ? sanitize_text_field($_POST['order_id']) : '';
    $updated_status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
    $updated_note = isset($_POST['order_notes']) ? sanitize_text_field($_POST['order_notes']) : '';
    $updated_orders_data = array(
    'order_id' => $updated_order_id,
    'order_notes' => $updated_note,
    'status' => $updated_status,
    );
    // Send the data to the store site using wp_remote_post
    $endpoint = '/wp-json/store-order-plugin/v1/update-order';
    $request_url = $base_url . $endpoint;
    $response = wp_remote_post($request_url, array(
        'body' => json_encode($updated_orders_data),
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('wppool:' . $secret_key),
        ),
    ));
}