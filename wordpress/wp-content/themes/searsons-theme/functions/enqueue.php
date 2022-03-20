<?php

// jQuery
add_action('wp_enqueue_scripts', 'theme_remove_default_jquery');
function theme_remove_default_jquery() {
	wp_deregister_script('jquery');
    
    wp_register_script( 'jquery', "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js", array(), '3.6.0', false );
    wp_enqueue_script('jquery');
}

// Remove Scripts
add_action('wp_footer', 'theme_remove_scripts');
function theme_remove_scripts(){
	//wp_dequeue_script('wp-embed');
}

// Remove CSS
add_action('wp_print_styles', 'theme_remove_styles', 100);
function theme_remove_styles() {
    wp_dequeue_style('wp-block-library');
}

//** -- Generate random version -- ** //

use function PHPSTORM_META\type;

// Get theme version
function wpmix_get_version() {
	$theme_data = wp_get_theme();
	return $theme_data->Version;
}

$theme_version = wpmix_get_version();
global $theme_version;

// Get random number
function wpmix_get_random() {
	$randomizr = '-' . rand(100,999);
	return $randomizr;
}

$random_number = wpmix_get_random();
global $random_number;

//** -- Generate random version -- ** //


// Style
add_action('wp_enqueue_scripts', 'theme_enqueue_css');

function theme_enqueue_css() {
    global $wp_styles;
    global $theme_version, $random_number;
     
    // Main Stylesheet
    //wp_enqueue_style('main-css', get_stylesheet_directory_uri().'/assets/style/style.css?v=211220212300', array(), null, 'all');
    wp_enqueue_style('main-css', get_stylesheet_directory_uri().'/assets/style/style.css', false, $theme_version . $random_number);
}

// Scripts
add_action('wp_enqueue_scripts', 'theme_enqueue_js', 90);

function theme_enqueue_js() {
    global $wp_query;
    global $acf;

    $scripts_url = get_stylesheet_directory_uri()."/assets/scripts/";
    $font_awesome_kit = get_field('font_awesome_kit', 'option');

    // Script Header
    wp_register_script('script-header', $scripts_url."script-header.js", null, null, false);
    wp_enqueue_script('script-header');
    
    // Script Footer
    wp_register_script('script-footer', $scripts_url."script-footer.js?v=091220211700", null, null, true);
    wp_enqueue_script('script-footer');
    
    // Font Awesome
    wp_register_script('font-awesome', $font_awesome_kit, null, null, true);
    wp_enqueue_script('font-awesome');
}


/**
* Add async or defer attributes to script enqueues
* @author Mike Kormendy
* @param  String  $tag     The original enqueued <script src="...> tag
* @param  String  $handle  The registered unique name of the script
* @return String  $tag     The modified <script async|defer src="...> tag
*/
// only on the front-end
/*
if(!is_admin()) {
    function add_asyncdefer_attribute($tag, $handle) {
        // if the unique handle/name of the registered script has 'async' in it
        if (strpos($handle, 'async') !== false) {
            // return the tag with the async attribute
            return str_replace( '<script ', '<script async ', $tag );
        }
        // if the unique handle/name of the registered script has 'defer' in it
        else if (strpos($handle, 'defer') !== false) {
            // return the tag with the defer attribute
            return str_replace( '<script ', '<script defer ', $tag );
        }
        // otherwise skip
        else {
            return $tag;
        }
    }
    add_filter('script_loader_tag', 'add_asyncdefer_attribute', 10, 2);
}
*/
?>