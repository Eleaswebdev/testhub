<?php

/**
 * Custom meta box for order post type
 * And it's functionality
 */

// Meta box for Billing Details
add_action('add_meta_boxes', 'wppool_add_billing_order_meta_boxes');

function wppool_add_billing_order_meta_boxes() {
    $screens = ['orders'];
    foreach ($screens as $screen) {
        add_meta_box(
            'billing_order_details_meta_box',
            __('Billing Details', 'hub-order-plugin'),
            'render_billing_order_meta_box',
            $screen,
            'normal',                
            'high'                     
        );
    }
}

function render_billing_order_meta_box($post) {
    // Retrieve existing values for meta fields
    $first_name = get_post_meta($post->ID, 'first_name', true);
    $last_name = get_post_meta($post->ID, 'last_name', true);
    $email = get_post_meta($post->ID, 'email', true);
    $company = get_post_meta($post->ID, 'company', true);
    $address_1 = get_post_meta($post->ID, 'address_1', true);
    $address_2 = get_post_meta($post->ID, 'address_2', true);
    $city = get_post_meta($post->ID, 'city', true);
    $state = get_post_meta($post->ID, 'state', true);
    $postcode = get_post_meta($post->ID, 'postcode', true);
    $country = get_post_meta($post->ID, 'country', true);
    $phone = get_post_meta($post->ID, 'phone', true);
    ?>
<div class="billing-details">

    <label for="first_name"><?php _e('First Name:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="first_name" name="first_name" value="<?php echo esc_attr($first_name); ?>"><br>

    <label for="last_name"><?php _e('Last Name:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="last_name" name="last_name" value="<?php echo esc_attr($last_name); ?>"><br>

    <label for="email"><?php _e('Email:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="email" name="email" value="<?php echo esc_attr($email); ?>"><br>

    <label for="address_1"><?php _e('Address Line 1:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="address_1" name="address_1" value="<?php echo esc_attr($address_1); ?>"><br>

    <label for="address_2"><?php _e('Address Line 2:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="address_2" name="address_2" value="<?php echo esc_attr($address_2); ?>"><br>

    <label for="city"><?php _e('City:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="city" name="city" value="<?php echo esc_attr($city); ?>"><br>

    <label for="state"><?php _e('State:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="state" name="state" value="<?php echo esc_attr($state); ?>"><br>

    <label for="postcode"><?php _e('Postal Code:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="postcode" name="postcode" value="<?php echo esc_attr($postcode); ?>"><br>

    <label for="country"><?php _e('Country:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="country" name="country" value="<?php echo esc_attr($country); ?>"><br>

    <label for="phone"><?php _e('Phone:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>"><br>

</div>
<?php
}

// Save custom meta field values when saving the order post
add_action('save_post', 'wppool_save_billing_order_meta_fields');

function wppool_save_billing_order_meta_fields($post_id) {

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'first_name',
        'last_name',
        'email',
        'address_1',
        'address_2',
        'city',
        'state',
        'postcode',
        'country',
        'phone',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }
}

// Meta box for shipping details
add_action('add_meta_boxes', 'wppool_add_shipping_order_meta_boxes');

function wppool_add_shipping_order_meta_boxes() {
    $screens = ['orders'];
    foreach ($screens as $screen) {
        add_meta_box(
            'shipping_order_details_meta_box',           
            __('Shipping Details', 'hub-order-plugin'), 
            'wppool_render_shipping_order_meta_box', 
            $screen,
            'normal',                 
            'high'                    
        );
    }
}

function wppool_render_shipping_order_meta_box($post) {
    // Retrieve existing values for meta fields
    $shipping_first_name = get_post_meta($post->ID, 'shipping_first_name', true);
    $shipping_last_name = get_post_meta($post->ID, 'shipping_last_name', true);
    $shipping_company = get_post_meta($post->ID, 'shipping_company', true);
    $shipping_address_1 = get_post_meta($post->ID, 'shipping_address_1', true);
    $shipping_address_2 = get_post_meta($post->ID, 'shipping_address_2', true);
    $shipping_city = get_post_meta($post->ID, 'shipping_city', true);
    $shipping_state = get_post_meta($post->ID, 'shipping_state', true);
    $shipping_postcode = get_post_meta($post->ID, 'shipping_postcode', true);
    $shipping_country = get_post_meta($post->ID, 'shipping_country', true);
    $shipping_phone = get_post_meta($post->ID, 'shipping_phone', true);
    ?>
<div class="shipping-details">
    <label for="shipping_first_name"><?php _e('First Name:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_first_name" name="shipping_first_name"
        value="<?php echo esc_attr($shipping_first_name); ?>"><br>

    <label for="shipping_last_name"><?php _e('Last Name:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_last_name" name="shipping_last_name"
        value="<?php echo esc_attr($shipping_last_name); ?>"><br>

    <label for="shipping_address_1"><?php _e('Address Line 1:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_address_1" name="shipping_address_1"
        value="<?php echo esc_attr($shipping_address_1); ?>"><br>

    <label for="shipping_address_2"><?php _e('Address Line 2:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_address_2" name="shipping_address_2"
        value="<?php echo esc_attr($shipping_address_2); ?>"><br>

    <label for="shipping_city"><?php _e('City:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_city" name="shipping_city"
        value="<?php echo esc_attr($shipping_city); ?>"><br>

    <label for="shipping_state"><?php _e('State:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_state" name="shipping_state"
        value="<?php echo esc_attr($shipping_state); ?>"><br>

    <label for="shipping_postcode"><?php _e('Postal Code:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_postcode" name="shipping_postcode"
        value="<?php echo esc_attr($shipping_postcode); ?>"><br>

    <label for="shipping_country"><?php _e('Country:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_country" name="shipping_country"
        value="<?php echo esc_attr($shipping_country); ?>"><br>
    <label for="shipping_phone"><?php _e('Phone:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="shipping_phone" name="shipping_phone"
        value="<?php echo esc_attr($shipping_phone); ?>"><br>

</div>

<?php
}

// Save custom meta field values when saving the order post
add_action('save_post', 'wppool_save_shipping_order_meta_fields');

function wppool_save_shipping_order_meta_fields($post_id) {

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'shipping_first_name',
        'shipping_last_name',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_state',
        'shipping_postcode',
        'shipping_country',
        'shipping_phone',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }

}

// Meta box for other order details
add_action('add_meta_boxes', 'wppool_add_other_details_meta_box');

function wppool_add_other_details_meta_box() {
    $screens = ['orders'];
    foreach ($screens as $screen) {
        add_meta_box(
            'other_order_details_meta_box',           
            __('Other Details', 'hub-order-plugin'), 
            'wppool_render_other_order_meta_box', 
            $screen,
            'normal',                 
            'high'                    
        );
    }
}

function wppool_render_other_order_meta_box($post) {
    // Retrieve existing values for meta fields
    $date_created = get_post_meta($post->ID, 'date_created', true);
    $status = get_post_meta($post->ID, 'status', true);
    $total = get_post_meta($post->ID, 'total', true);
    $payment_method_title = get_post_meta($post->ID, 'payment_method_title', true);
    $order_notes = get_post_meta($post->ID, 'order_notes', true);
    $order_id = get_post_meta($post->ID, 'order_id', true);
    ?>
<div class="other-details">
    <label for="order_id"><?php _e('Order ID:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="order_id" name="order_id" value="<?php echo esc_attr($order_id); ?>"><br>
    <label for="date_created"><?php _e('Date Created:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="date_created" name="date_created"
        value="<?php echo esc_attr($date_created); ?>"><br>

    <label for="total"><?php _e('Total:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="total" name="total" value="<?php echo esc_attr($total); ?>"><br>

    <label for="payment_method_title"><?php _e('Payment Method:', 'hub-order-plugin'); ?></label>
    <input readonly type="text" id="payment_method_title" name="payment_method_title"
        value="<?php echo esc_attr($payment_method_title); ?>"><br>


    <label for="status"><?php _e('Status:', 'hub-order-plugin'); ?></label>
    <select id="status" name="status">
        <option value="pending-payment" <?php selected( $status, 'pending-payment' ); ?>>
            <?php _e('Pending', 'hub-order-plugin'); ?></option>
        <option value="processing" <?php selected( $status, 'processing' ); ?>>
            <?php _e('Processing', 'hub-order-plugin'); ?></option>
        <option value="completed" <?php selected( $status, 'completed' ); ?>>
            <?php _e('Completed', 'hub-order-plugin'); ?></option>
        <option value="cancelled" <?php selected( $status, 'cancelled' ); ?>>
            <?php _e('Cancelled', 'hub-order-plugin'); ?></option>
        <option value="on-hold" <?php selected( $status, 'on-hold' ); ?>><?php _e('On Hold', 'hub-order-plugin'); ?>
        </option>
        <option value="refunded" <?php selected( $status, 'refunded' ); ?>><?php _e('Refunded', 'hub-order-plugin'); ?>
        </option>
        <option value="failed" <?php selected( $status, 'failed' ); ?>><?php _e('Failed', 'hub-order-plugin'); ?>
        </option>
        <option value="draft" <?php selected( $status, 'draft' ); ?>><?php _e('Draft', 'hub-order-plugin'); ?></option>
    </select><br>

    <label for="order_notes"><?php _e('Order Notes:', 'hub-order-plugin'); ?></label>
    <input type="text" id="order_notes" name="order_notes" value="<?php echo esc_attr($order_notes); ?>"><br>


</div>

<?php
}

// Save custom meta field values when saving the order post
add_action('save_post', 'wppool_save_other_order_meta_fields');

function wppool_save_other_order_meta_fields($post_id) {

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'order_id',
        'date_created',
        'total',
        'payment_method_title',
        'status',
        'order_notes',

    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }

}

// Add custom meta fields to REST API response
add_filter('rest_prepare_orders', 'wppool_include_order_meta_in_rest', 10, 3);

function wppool_include_order_meta_in_rest($response, $post, $request) {
    // Check if this is the 'orders' post type
    if ($post->post_type !== 'orders') {
        return $response;
    }

    // Retrieve existing meta fields
    $title = get_post_meta($post->title, 'title', true);
    $order_id = get_post_meta($post->ID, 'order_id', true);
    $first_name = get_post_meta($post->ID, 'first_name', true);
    $last_name = get_post_meta($post->ID, 'last_name', true);
    $email = get_post_meta($post->ID, 'email', true);
    $company = get_post_meta($post->ID, 'company', true);
    $address_1 = get_post_meta($post->ID, 'address_1', true);
    $address_2 = get_post_meta($post->ID, 'address_2', true);
    $city = get_post_meta($post->ID, 'city', true);
    $state = get_post_meta($post->ID, 'state', true);
    $postcode = get_post_meta($post->ID, 'postcode', true);
    $country = get_post_meta($post->ID, 'country', true);
    $phone = get_post_meta($post->ID, 'phone', true);

    //shipping fields
    $shipping_first_name = get_post_meta($post->ID, 'shipping_first_name', true);
    $shipping_last_name = get_post_meta($post->ID, 'shipping_last_name', true);
    $shipping_company = get_post_meta($post->ID, 'shipping_company', true);
    $shipping_address_1 = get_post_meta($post->ID, 'shipping_address_1', true);
    $shipping_address_2 = get_post_meta($post->ID, 'shipping_address_2', true);
    $shipping_city = get_post_meta($post->ID, 'shipping_city', true);
    $shipping_state = get_post_meta($post->ID, 'shipping_state', true);
    $shipping_postcode = get_post_meta($post->ID, 'shipping_postcode', true);
    $shipping_country = get_post_meta($post->ID, 'shipping_country', true);

    //other fields
    $status = get_post_meta($post->ID, 'status', true);
    $order_notes = get_post_meta($post->ID, 'order_notes', true);

    // Add meta fields to the response data
    $response->data['order_id'] = $order_id;
    $response->data['first_name'] = $first_name;
    $response->data['last_name'] = $last_name;
    $response->data['email'] = $email;
    $response->data['company'] = $company;
    $response->data['address_1'] = $address_1;
    $response->data['address_2'] = $address_2;
    $response->data['city'] = $city;
    $response->data['state'] = $state;
    $response->data['postcode'] = $postcode;
    $response->data['country'] = $country;
    $response->data['phone'] = $phone;

    $response->data['shipping_first_name'] = $shipping_first_name;
    $response->data['shipping_last_name'] = $shipping_last_name;
    $response->data['shipping_company'] = $shipping_company;
    $response->data['shipping_address_1'] = $shipping_address_1;
    $response->data['shipping_address_2'] = $shipping_address_2;
    $response->data['shipping_city'] = $shipping_city;
    $response->data['shipping_state'] = $shipping_state;
    $response->data['shipping_postcode'] = $shipping_postcode;
    $response->data['shipping_country'] = $shipping_country;

    $response->data['status'] = $status;
    $response->data['order_notes'] = $order_notes;

    return $response;
}