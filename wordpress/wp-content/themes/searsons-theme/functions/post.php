<?php
//** -- Posts -- **//
// Widget class name
function theme_widget_class_name() {
    if (get_row_layout() == 'article') : echo 'widget-article'; endif;
    if (get_row_layout() == 'list') : echo 'widget-feed'; endif;
    if (get_row_layout() == 'content_feed') : echo 'widget-feed'; endif;
    if (get_row_layout() == 'gallery') : echo 'widget-gallery'; endif;
    if (get_row_layout() == 'shortcode') : echo 'widget-shortcode'; endif;
    if (get_row_layout() == 'form') : echo 'widget-form'; endif;
};

// Thumbnails
add_theme_support('post-thumbnails');

update_option('thumbnail_size_w', 750);
update_option('thumbnail_size_h', 750);
update_option('thumbnail_crop', 0);

update_option('medium_size_w', 1250);
update_option('medium_size_h', 1250);
update_option('medium_crop', 0);

update_option('large_size_w', 2500);
update_option('large_size_h', 5000);
update_option('large_crop', 0);

// Excerpt
//remove_filter('the_excerpt', 'wpautop');
remove_filter('term_description', 'wpautop');

add_filter('excerpt_length', 'theme_custom_excerpt_length');
function theme_custom_excerpt_length($length) {
	return 35;
}

add_filter('excerpt_more', 'theme_custom_excerpt_more');
function theme_custom_excerpt_more($more) {
	return ' (...)';
}

add_post_type_support('page', 'excerpt'); // add excerpt to pages

// Pagination
function theme_pagination($pages = "", $range = 2){
	$showitems = ($range * 2)+1;
	global $paged;
	if(empty($paged)) $paged = 1;
	if($pages == "") {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if(!$pages) {
			$pages = 1;
		}
	}   
	if(1 != $pages) {
		// open --->
		echo '
			<div class="box-pagination">
    '; // open

                //if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<a href="'.get_pagenum_link(1).'">First Page</a>'; // first page

                //if($paged > 1 && $showitems < $pages) echo '
                if($paged > 1) echo '
                <div class="item-pagination before">
                    <a href="'.get_pagenum_link($paged - 1).'" class="button small line">
                        <i class="fal fa-long-arrow-left"></i>
                    </a>
                </div>
                '; // before

                for ($i=1; $i <= $pages; $i++) {
                    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems)) {
                        echo ($paged == $i)? '
                            <div class="item-pagination">
                                <span class="button small default" style="cursor: auto;">'.$i.'</span>
                            </div>
                        ' // selected
                        :
                        '
                            <div class="item-pagination">
                                <a href="'.get_pagenum_link($i).'" class="button small line">'.$i.'</a>
                            </div>
                        ' //items
                        ;
                    }
                }

                //if ($paged < $pages && $showitems < $pages) echo '
                if ($paged < $pages) echo '
                <div class="item-pagination next">
                    <a href="'.get_pagenum_link($paged + 1).'" class="button small line"><i class="fal fa-long-arrow-right"></i></a>
                </div>
                '; //next

                //if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<a href="'.get_pagenum_link($pages).'">Last Page</a>'; //last page
		echo '
			</div>
		'; //close
	}
}
//** ---- **//

//** -- Taxonomies -- **//
// Get Clean Taxonomy
function get_tax_name($args) {
	$terms = get_the_terms($post->ID, $args);
	if (!empty($terms)): foreach($terms as $term) {
		echo ''.$term->name.'';
		unset($term);
	}
	endif;
}

function get_tax_url($args) {
	$terms = get_the_terms($post->ID, $args);
	if (!empty($terms)): foreach($terms as $term) {
		$term_link = get_term_link($term);
		echo ''.esc_url($term_link).'';
		unset($term);
	}
	endif;
}
//** ---- **//

//** -- Post Types -- **//
// Prebuilt Widgets
add_action('init', 'create_prebuilt_widgets_post', 0);
function create_prebuilt_widgets_post() {
	register_post_type(
		'prebuilt', array(
			'labels' => array(
				'name' => __('Prebuilt Widgets'),
				'singular_name' => __('Prebuilt Widgets')
			),
			
			'menu_position' => 60,
			'menu_icon' => 'dashicons-tagcloud',
			'capability_type' => 'page',
			//'hierarchical' => true,
			
			'public' => true,
			'public_queryable' => true,
			'exclude_from_search' => true,
			'has_archive' => false,
			'can_export' => true,
			
			'query_var' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => true,
			
			'rewrite' => array(
				'slug' => 'prebuilt',
				'with_front' => true
			),
			
			'supports' => array (
				'title', 'revisions', 'editor', 'page-attributes'
			),
		)
	);
}

add_filter('wp_insert_post_data', 'theme_force_prebuilt_private');
function theme_force_prebuilt_private($post) {
    if ($post['post_type'] == 'prebuilt')
    $post['post_status'] = 'private';
    return $post;
}

/*
// Redirect Single 
add_action('template_redirect', 'theme_redirect_custom_post');
function theme_redirect_custom_post() {
    if (is_singular(array('testiminials'))) {
        wp_redirect(home_url(), 301);
        exit;
    }
}
*/
//** ---- **//

//** -- Post Editor -- **//
add_filter('quicktags_settings', 'theme_custom_editor');
function theme_custom_editor($arrange_buttons) {
	$arrange_buttons['buttons'] = 'strong,em,link,ul,ol,li,block';
	return $arrange_buttons;
}
//** ---- **//
?>