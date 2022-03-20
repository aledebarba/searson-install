<?php
/**
 * 
 * Add type='module' to js enqueue tag
 * 
 */
add_filter('script_loader_tag', 'add_type_module_to_js_enqueue', 10, 3);
function add_type_module_to_js_enqueue ($tag, $handle, $src) {
  if ($handle == 'guts_elt-filter_js') {
    $version = rand(2390, 9999);
    $tag = "<script type='module' src='$src?v=a-$version'></script>";
  }
  return $tag;
}
/**
 * 
 * 
 * Enqueue styles and scripts
 *
 * 
 */
add_action( 'wp_enqueue_scripts', function() {
  $path = get_template_directory_uri();
  wp_enqueue_style ( 'guts_styles',         $path . '/modules/guts/styles/styles.css', null, null, "all" );
  wp_enqueue_script( 'guts_elt-filter_js',  $path . '/modules/guts/main.js', null, null, true );
  wp_localize_script( 'guts_elt-filter_js', "gutsGlobals", [
    // pass the ajax url to the js file
    'ajaxurl' => admin_url( 'admin-ajax.php' )
  ]);
  // enqueue exteranl cdn scripts
  // wp_enqueue_script('JSONata', 'https://cdn.jsdelivr.net/npm/jsonata/jsonata.min.js', null, null, null, false);  
  // wp_enqueue_script('JSRequire', "https://requirejs.org/docs/release/2.3.5/minified/require.js", null, null, null, false);  
}
);
?>