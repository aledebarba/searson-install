<?php
// Remove User Panel Options
remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker'); // Color Scheme

add_action('personal_options', function($profileuser) { ?>
	<style>
		h2:first-of-type, /* Personal Options Headline */
		.user-rich-editing-wrap, /* Visual Editor */
		.user-syntax-highlighting-wrap, /* Syntax Highlight */
		.user-comment-shortcuts-wrap, /* Keyboard Shortcuts */
		.show-admin-bar, /* Admin Bar */
		.user-language-wrap /* Language */
		{
			display: none;
		}
	</style>
<?php });

// Admin Menu Order
add_filter('custom_menu_order', function() {
	return true;
});
add_filter('menu_order', 'theme_change_admin_menu');

function theme_change_admin_menu($menu_order) {
	$new_positions = array(
		'upload.php' => 9,
		'edit.php?post_type=page' => 3,
	);
	
	function move_element(&$array, $a, $b) {
		$out = array_splice($array, $a, 1);
		array_splice($array, $b, 0, $out);
	}
	
	foreach($new_positions as $value => $new_index) {
		if($current_index = array_search($value, $menu_order)) {
			move_element($menu_order, $current_index, $new_index);
		}
	}
	
	return $menu_order;
};

// Admin Menus
add_action('admin_menu', 'theme_remove_admin_menus');
function theme_remove_admin_menus() {
	//remove_menu_page('themes.php');
	//remove_submenu_page('themes.php','customize.php?return=' . urlencode(wp_unslash ($_SERVER['REQUEST_URI'])));
    //remove_menu_page('tools.php');
	//remove_submenu_page('options-general.php', 'options-writing.php');
	//remove_submenu_page('options-general.php', 'options-media.php');
	//remove_submenu_page('options-general.php', 'options-permalink.php');
	remove_submenu_page('options-general.php','options-discussion.php');
}

// Admin Menu Nav
add_action('admin_menu', 'theme_change_menus_position');
function theme_change_menus_position() {
    // Remove old menu
    remove_submenu_page('themes.php', 'nav-menus.php');

    //Add new menu page
     add_menu_page(
       'Menus',
       'Menus',
       'edit_theme_options',
       'nav-menus.php',
       '',
       'dashicons-editor-ul',
       60
    );
}

// Admin Bar Menus
add_action('admin_bar_menu', 'theme_remove_admin_bar_menus', 999);
function theme_remove_admin_bar_menus($wp_admin_bar) {
    $wp_admin_bar->remove_menu('customize');
}

// Admin Style
add_action('wp_before_admin_bar_render', 'theme_admin_css');

function theme_admin_css() { ?>
	<style>
		/* Logo */
		#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
            color: transparent;
            
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-panel.png) !important;
            
			-moz-background-size: cover;
			-webkit-background-size: cover;
			background-size: cover;
            
			background-position: 0 0;
		}
		#wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
			background-position: 0 0;
		}
        /* Logo */
		
		/* Bar */
		#wpadminbar {}
		
		#adminmenu,
		#adminmenu .wp-submenu,
		#adminmenuback,
		#adminmenuwrap {
			background: #222222;
		}
        
        #wpadminbar #wp-admin-bar-wp-logo > .ab-sub-wrapper,
        #wpadminbar #wp-admin-bar-site-name > .ab-sub-wrapper {
            display: none !important;
        }
		
		#adminmenu .wp-has-current-submenu .wp-submenu,
		#adminmenu .wp-has-current-submenu .wp-submenu.sub-open,
		#adminmenu .wp-has-current-submenu.opensub .wp-submenu,
		#adminmenu a.wp-has-current-submenu:focus+.wp-submenu,
		.no-js li.wp-has-current-submenu:hover .wp-submenu {
			background: #403e41;
		}
		
		#adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,
		#adminmenu .wp-menu-arrow,
		#adminmenu .wp-menu-arrow div,
		#adminmenu li.current a.menu-top,
		#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
		.folded #adminmenu li.current.menu-top,
		.folded #adminmenu li.wp-has-current-submenu {
            background: <?php the_field('theme_color', 'option'); ?>;
		}
        /* Bar */
        
        /* Advanced Custom Fields */
		.acf-field-image .show-if-value.image-wrap { /* Image Size */
			max-width: 150px !important; 
		}
        
        .acf-repeater.-block .acf-row+.acf-row > td { /* Row Separator */
           border-top-width: 10px !important;
        }
        /* Advanced Custom Fields */
	</style>
<?php }

//** -- Login Page -- **//

// Change Login Page
// Reference: https://premium.wpmudev.org/blog/customize-login-page/
add_action('login_enqueue_scripts', 'theme_login_css');

function theme_login_css() { ?>
	<style>
		/* background colour */
		body.login {
			background-color: white;
		}
		
		/* logo */
		body.login div#login h1 a {
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-login.png);
			width: 320px;
			height: 131px;
			padding-bottom: 0;
			-moz-background-size: auto 80%;
			-webkit-background-size: auto 80%;
			background-size: auto 80%;
			background-repeat: no-repeat;
			background-position: center center;
		}
		*/
		
		/* box */
		body.login form {
			color: white;
			border: 0;
			background: <?php the_field('theme_color', 'option'); ?>;
			border-radius: 3px;
		}
		
		/* submit button */
		body.login form input#wp-submit {
			background: <?php the_field('theme_color', 'option'); ?>;
    		border-color: transparent;
		}
		
		/* link */
		body.login #nav a {
			color: <?php the_field('theme_color', 'option'); ?>;
		}
	</style>
<?php }

add_filter('login_headerurl', 'theme_login_logo_url');
function theme_login_logo_url() {
	return home_url();
}

add_filter('login_headertitle', 'theme_login_logo_title');
function theme_login_logo_title() {
    //return 'Your Site Name and Info';
	return get_bloginfo('name');
}

//** -- Login Page -- **//

?>