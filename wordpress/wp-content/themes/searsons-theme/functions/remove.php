<?php
// Admin Bar
add_filter('show_admin_bar', 'theme_remove_admin_bar'); // remove admin bar from front-end

function theme_remove_admin_bar() {
	//return false;
}

// Remove elements and functions
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');

add_filter('user_can_richedit', '__return_false', 50);
remove_action('welcome_panel', 'wp_welcome_panel');

// Remove Feed
/*
function theme_remove_feed() {
	wp_die( __('No feed available, please visit the <a href="'. esc_url( home_url( '/' ) ) .'">homepage</a>!'));
}

add_action('do_feed', 'theme_remove_feed', 1);
add_action('do_feed_rdf', 'theme_remove_feed', 1);
add_action('do_feed_rss', 'theme_remove_feed', 1);
add_action('do_feed_rss2', 'theme_remove_feed', 1);
add_action('do_feed_atom', 'theme_remove_feed', 1);
add_action('do_feed_rss2_comments', 'theme_remove_feed', 1);
add_action('do_feed_atom_comments', 'theme_remove_feed', 1);
*/

// Remove Comments
add_action('admin_init', 'theme_remove_comments_support');
function theme_remove_comments_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}

add_filter('comments_open', 'theme_remove_comments_status', 20, 2);
add_filter('pings_open', 'theme_remove_comments_status', 20, 2);
function theme_remove_comments_status() {
	return false;
}

add_filter('comments_array', 'theme_remove_comments_hide_existing_', 10, 2);
function theme_remove_comments_hide_existing($comments) {
	$comments = array();
	return $comments;
}

add_action('admin_menu', 'theme_remove_comments_admin_menu');
function theme_remove_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}

add_action('admin_init', 'theme_remove_comments_admin_menu_redirect');
function theme_remove_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url());
		exit;
	}
}

add_action('admin_init', 'theme_remove_comments_dashboard');
function theme_remove_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

add_action('wp_before_admin_bar_render', 'theme_remove_comments_admin_bar'); // admin bar
function theme_remove_comments_admin_bar() {
	global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}

// Remove Smilies
add_filter('option_use_smilies', 'theme_remove_smilies', 99, 1);
function theme_remove_smilies($bool) {
	return false;
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');

add_filter('emoji_svg_url', '__return_false');
?>