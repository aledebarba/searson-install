<?php
//** -- Menu -- **//

// Register Menus
add_action('init', 'theme_register_menus');
function theme_register_menus() {
	register_nav_menus(
        array(
            'menu_header' => __('Header'),
            'menu_pre_header_01' => __('Pre Header 01'),
            'menu_pre_header_02' => __('Pre Header 02'),
            'menu_footer' => __('Footer'),
            'menu_footer_extra' => __('Footer Extra'),
            'menu_institutional' => __('Institutional'),
	   )
    );
}

// Custom Menu - Header
class theme_custom_menu_header extends Walker_Nav_Menu {
	function filter_builtin_classes($var) {
	    return (FALSE === strpos($var, 'item')) ? $var : '';
	}
	
	// menu -->
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		// submenu
		$children = get_posts(array('post_type' => 'nav_menu_item', 'nopaging' => true, 'numberposts' => 1, 'meta_key' => '_menu_item_menu_item_parent', 'meta_value' => $item->ID));
		
		$indent = ($depth) ? str_repeat("\n", $depth) : '';
		$indent = '';
		
		$unfiltered_classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes = array_filter($unfiltered_classes, array($this, 'filter_builtin_classes'));
		
        // children
        if (!empty($children)) {
            $classes[] = 'has-sub-menu';
        }
        
        // active
		if (preg_grep("/^current/", $unfiltered_classes)) {
			$classes[] = 'active';
		}
    
        
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="'. esc_attr($class_names) .'"' : '';
		
		$id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';
		
		$output .= $indent . '<li' . $value . $class_names .'>';
        
		// link -->
		$atts = array();
		$atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
		$atts['target'] = ! empty($item->target)     ? $item->target     : '';
		$atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
		$atts['href']   = ! empty($item->url)        ? $item->url        : '';
		$atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
		$attributes = '';
		foreach ($atts as $attr => $value) {
			if (! empty($value)) {
				$value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		// <-- link
		
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
	
	function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>";
    }
	// <-- menu
	
	// submenu -->
	function start_lvl(&$output, $depth = 0, $args = array()) {
		//$indent = str_repeat("\t", $depth);
		$output .= "$indent<ol class=\"sub-menu\">";
	}
	
	function end_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ol>";
	}
	// <-- submenu
}

// Custom Menu - Footer
class theme_custom_menu_footer extends Walker_Nav_Menu {
	function filter_builtin_classes($var) {
	    return (FALSE === strpos($var, 'item')) ? $var : '';
	} 

	// menu -->
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = '';
		
		$unfiltered_classes = empty($item->classes) ? array() : (array) $item->classes;
		
		$classes = array_filter($unfiltered_classes, array($this, 'filter_builtin_classes'));
		if (preg_grep("/^current/", $unfiltered_classes)) {
			$classes[] = 'active';
		}
		
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="'. esc_attr($class_names) .'"' : '';
		
		$output .= $indent . '<li' . $value . $class_names .'>';
		
		// link -->
		$atts = array();
		$atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
		$atts['target'] = ! empty($item->target)     ? $item->target     : '';
		$atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
		$atts['href']   = ! empty($item->url)        ? $item->url        : '';
		$atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
		$attributes = '';
		foreach ($atts as $attr => $value) {
			if (! empty($value)) {
				$value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		// <-- link
		
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
	
	function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>";
    }
	// <-- menu
}

// Custom Menu - Institutional
class theme_custom_menu_institutional extends Walker_Nav_Menu {
	function filter_builtin_classes($var) {
	    return (FALSE === strpos($var, 'item')) ? $var : '';
	}
	
	// menu -->

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$indent = '';
		
		$unfiltered_classes = empty($item->classes) ? array() : (array) $item->classes;
		
		$classes = array_filter($unfiltered_classes, array($this, 'filter_builtin_classes'));
		if (preg_grep("/^current/", $unfiltered_classes)) {
			$classes[] = 'active';
		}
		
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="'. esc_attr($class_names) .'"' : '';
		
		$output .= $indent . '<li' . $value . $class_names .'>';
		
		// link -->
		$atts = array();
		$atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
		$atts['target'] = ! empty($item->target)     ? $item->target     : '';
		$atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
		$atts['href']   = ! empty($item->url)        ? $item->url        : '';
		$atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
		$attributes = '';
		foreach ($atts as $attr => $value) {
			if (! empty($value)) {
				$value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		// <-- link
		
		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}
	
	function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= "</li>";
    }
	// <-- menu
}

//** ---- **//
?>