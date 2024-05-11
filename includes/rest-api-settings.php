<?php
// Step 1: Register a settings menu page
add_action('admin_menu', 'register_store_order_settings_page');
function register_store_order_settings_page() {
    add_options_page(
        __('Rest API Settings', 'hub-order-plugin'),   
        __('Rest API Settings', 'hub-order-plugin'),      
        'manage_options',             
        'rest-api-settings',       
        'render_rest_api_settings_page' 
    );
}

// Step 2: Render the settings page
function render_rest_api_settings_page() {
    ?>
<div class="wrap">
    <h1><?php echo esc_html__('Rest API Settings', 'hub-order-plugin'); ?></h1>
    <form method="post" action="options.php">
        <?php
            settings_fields('rest_api_settings_group');
            do_settings_sections('rest-api-settings');
            submit_button(__('Save', 'store-order-plugin'));
            ?>
    </form>
</div>
<?php
}

// Step 3: Add settings fields
add_action('admin_init', 'wppool_add_rest_api_settings_fields');
function wppool_add_rest_api_settings_fields() {
    add_settings_section(
        'rest_api_settings_section',    
        __('General Settings', 'hub-order-plugin'),                
        'render_settings_section',                               
        'rest-api-settings'             
    );
    
    // Add fields
    add_settings_field(
        'secret_key',                         
        __('Secret Key', 'hub-order-plugin'),                            
        'render_secret_key_field',            
        'rest-api-settings',            
        'rest_api_settings_section',   
    );
    add_settings_field(
        'base_url',                         
        __('Base URL', 'hub-order-plugin'),                          
        'render_base_url_field',            
        'rest-api-settings',            
        'rest_api_settings_section',   
    );
    
    // Register settings
    register_setting(
        'rest_api_settings_group',      // Option group
        'rest_secret_key'              // Option name
    );
    register_setting(
        'rest_api_settings_group',    
        'rest_base_url'              
    );
}
function render_settings_section() {
    echo '<p>' . esc_html__('Please provide your store site base URL. Example: yoursite.com', 'hub-order-plugin') . '</p>';
    echo '<p><b>' . esc_html__('Also keep in mind that your secret key should match with the store site secret key. Otherwise, your update or receive orders from store will not work.', 'hub-order-plugin') . '</b></p>';
}
// Callback function to render Secret Key field
function render_secret_key_field() {
    $secret_key = get_option('rest_secret_key');
    ?>
<input style="width: 50%" type="text" name="rest_secret_key" value="<?php echo esc_attr($secret_key); ?>" /></br>
<?php
}
function render_base_url_field() {
    $base_url = get_option('rest_base_url');
    ?>
<input style="width: 50%" type="text" name="rest_base_url" value="<?php echo esc_attr($base_url); ?>" /></br>
<?php
}