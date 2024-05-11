<?php

//Display all orders using shortcode
function wppool_display_all_orders() { 
	ob_start();
	?>
<div id="my-react-app"></div>
<?php return ob_get_clean();
}
// register shortcode
add_shortcode('all_orders', 'wppool_display_all_orders'); 