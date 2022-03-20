<?php
include('functions/remove.php');
include('functions/enqueue.php');
include('functions/post.php');
include('functions/menu.php');
include('functions/acf.php');
include('functions/custom_panel.php');
include('modules/guts/main.php');

//** -- SVG -- ** //
add_filter('upload_mimes', 'theme_files_format_upload');

function theme_files_format_upload($file_types){
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    
    $file_types = array_merge($file_types, $new_filetypes );
    
    return $file_types;
}
//** -- SVG -- ** //

//** -- Contact Form 7 -- **//
/*
if(class_exists('WPCF7')) {
	function theme_enqueue_wpcf7_scripts() {
		
		// Pages to add CF7 scripts
		$pages_cf7_add_scripts = array('contact');
		
		if(is_page($pages_cf7_add_scripts) || is_single($single_cf7_add_scripts)) {
			if(function_exists('wpcf7_enqueue_scripts')) {
				wpcf7_enqueue_scripts();
			}
			
			if(function_exists('wpcf7_enqueue_styles')) {
				//wpcf7_enqueue_styles();
			}
		}
	
	}
	
	add_filter('wpcf7_load_js', '__return_false'); // javascript
	add_filter('wpcf7_load_css', '__return_false'); // css
	add_action('wp_enqueue_scripts', 'theme_enqueue_wpcf7_scripts');
}
*/

add_filter('wpcf7_load_css', '__return_false'); // css
//** -- Contact Form 7 -- **//


//** -- WP Shopify -- **//
 add_filter('wpshopify_use_products_single_template', function($use_plugin_template) {
	return false;
 });

add_filter(
    'shopwp_storefront_default_payload_settings',
    function ($storefront_settings) {
        $storefront_settings['filterable_price_values'] = [
            '0.00 - 15.00',
            '15.00 - 25.00',
            '25.00 - 50.00',
            '50.00 - 100.00',
            '100.00 +',
        ];
        return $storefront_settings;
    }
);

add_filter(
    'shopwp_products_default_payload_settings',
    function($settings) {
        //$settings['image_placeholder'] = plugin_dir_url(dirname(__FILE__)) . 'public/imgs/placeholder.png');
        $settings['image_placeholder'] = get_bloginfo('stylesheet_directory') . '/assets/images/placeholder-logo.png';
        return $settings;
    }
);
//** -- WP Shopify -- **//

?>