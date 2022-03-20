<?php
//** -- Advanced Custom Fields -- **//

// Option Pages
if (function_exists('acf_add_options_page')) {
	// Site Management
	acf_add_options_page (array(
		'page_title' => 'Site Management',
		'menu_title' => 'Site Management',
		'menu_slug' => 'site-management',
		'capability' => 'edit_posts',
		'redirect' => false,
		'icon_url' => 'dashicons-info',
		'position' => 2
	));
}

//add_filter('acf/settings/show_admin', '__return_false'); // Hide ACF from menu

/*
// Google Maps API 
add_filter('acf/fields/google_map/api', 'acf_google_map_api');
function acf_google_map_api($api){
    $api['key'] = '';
    return $api;
}
*/

//** -- Advanced Custom Fields -- **//
?>