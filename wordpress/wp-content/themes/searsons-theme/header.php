<!doctype html>
<html lang="en">
<head>
    <title><?php wp_title(); ?></title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- theme -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('template_directory'); ?>/assets/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('template_directory'); ?>/assets/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('template_directory'); ?>/assets/icons/favicon-16x16.png">
    <link rel="manifest" href="<?php bloginfo('template_directory'); ?>/assets/icons/site.webmanifest">
    <link rel="mask-icon" href="<?php bloginfo('template_directory'); ?>/assets/icons/safari-pinned-tab.svg" color="<?php the_field('theme_color', 'option'); ?>">
    
    <meta name="apple-mobile-web-app-title" content="<?php echo get_bloginfo('name');?>">
    <meta name="application-name" content="<?php echo get_bloginfo('name');?>">
    
    <meta name="msapplication-TileColor" content="<?php the_field('theme_color', 'option'); ?>">
    <meta name="theme-color" content="<?php the_field('theme_color', 'option'); ?>">

    <!-- /theme -->
	
    <?php
        wp_head();
        the_field('site_script_header', 'option'); // script header  
    ?>
</head>

<body>
    <?php get_template_part('templates/element_announcement_bar'); ?>
    
	<div id="site">
        <!-- header -->
        <?php if (!is_singular('prebuilt')) : ?>
            <?php
                //** custom header -- start **//
                if (get_field('header_appearance_set') == true) :
                // custom header -- page level  //
            ?>
                <?php while(have_rows('header_appearance')) : the_row();
                    $logo = get_field('header_appearance_logo');
                    $logo_size = get_field('header_appearance_logo_size');

                    $style = get_field('header_appearance_style');
                    $display_header = get_field('header_appearance_display');

                    $position = get_field('header_appearance_position');
                    $pre_header_position = get_field('header_appearance_pre_header_position');
                ?>

                <header class="header<?php if ($position == 'relative') : echo ' '; echo $style; endif; echo ' '; echo $display_header; echo ' '; echo $position; if (has_nav_menu('menu_pre_header_01') || has_nav_menu('menu_pre_header_02')) : echo ' '; echo $pre_header_position; endif;?>"<?php if ($position == 'absolute') : echo ' id="header-absolute"'; endif; if ($position == 'relative') : echo ' '; endif; ?>>     
                            <figure class="box-logo<?php echo ' '; echo $logo; echo ' '; echo $logo_size;?>" role="presentation">
                                <a href="<?php echo get_home_url(); ?>">
                                    <?php
                                        if(($logo) == 'primary') :
                                            get_template_part('templates/element_logo_primary');
                                        endif;
                                        if(($logo) == 'secondary') :
                                            get_template_part('templates/element_logo_secondary');
                                        endif;
                                    ?>
                                </a>
                            </figure>
                    <?php endwhile; ?>   
            <?php
                elseif($post->post_type == 'wps_products') :
                // header product -- inherit all characteristics from default header, except position which is always relative //
            ?>
                <?php while(have_rows('header_appearance', 'option')) : the_row();
                    $logo = get_sub_field('logo');
                    $logo_size = get_sub_field('logo_size');

                    $style = get_sub_field('style');
                    $display_header = get_sub_field('display');
                    
                    $pre_header_position = get_sub_field('pre_header_position');
                ?>
                    <!-- product -->
                    <header class="header relative<?php echo ' '; echo $style; echo ' '; echo $display_header; echo ' '; if (has_nav_menu('menu_pre_header_01') || has_nav_menu('menu_pre_header_02')) : echo ' '; echo $pre_header_position; endif;?>" id="header-relative">            
                            <figure class="box-logo<?php echo ' '; echo $logo; echo ' '; echo $logo_size;?>" role="presentation">
                                <a href="<?php echo get_home_url(); ?>">
                                    <?php
                                        if(($logo) == 'primary') :
                                            get_template_part('templates/element_logo_primary');
                                        endif;
                                        if(($logo) == 'secondary') :
                                            get_template_part('templates/element_logo_secondary');
                                        endif;
                                    ?>
                                </a>
                            </figure>
                    <?php endwhile; ?> 
            <?php
                else :
                // default header -- panel level  //
            ?> 
                <?php while(have_rows('header_appearance', 'option')) : the_row();
                    $logo = get_sub_field('logo');
                    $logo_size = get_sub_field('logo_size');

                    $style = get_sub_field('style');
                    $display_header = get_sub_field('display');

                    $position = get_sub_field('position');
                    $pre_header_position = get_sub_field('pre_header_position');
                ?>

                <header class="header<?php if ($position == 'relative') : echo ' '; echo $style; endif; echo ' '; echo $display_header; echo ' '; echo $position; if (has_nav_menu('menu_pre_header_01') || has_nav_menu('menu_pre_header_02')) : echo ' '; echo $pre_header_position; endif;?>"<?php if ($position == 'absolute') : echo ' id="header-absolute"'; endif; if ($position == 'relative') : echo ' id="header-relative"'; endif; ?>>            
                        <figure class="box-logo<?php echo ' '; echo $logo; echo ' '; echo $logo_size;?>" role="presentation">
                            <a href="<?php echo get_home_url(); ?>">
                                <?php
                                    if(($logo) == 'primary') :
                                        get_template_part('templates/element_logo_primary');
                                    endif;
                                    if(($logo) == 'secondary') :
                                        get_template_part('templates/element_logo_secondary');
                                    endif;
                                ?>
                            </a>
                        </figure>
                <?php endwhile; ?>   
            <?php
                endif;
                //** custom header -- end  **//
            ?>
                <div class="hold-menu">
                    <?php
                        $location = 'menu_header';

                        if (has_nav_menu($location)) :
                            while(have_rows('menu_header_appearance', 'option')) : the_row();
                            $size = get_sub_field('size');
                            $separator = get_sub_field('separator');
                            $font_family = get_sub_field('font_family');
                            $font_weight = get_sub_field('font_weight');
                            $text_transform = get_sub_field('text_transform');

                            echo '<nav class="menu-main '.$size.' '.$separator.' '.$font_family.' '.$font_weight.' '.$text_transform.'">';
                            wp_nav_menu(array(
                                'theme_location' => $location,
                                'container_id' => false,
                                'container' => '',
                                'container_class' => '',
                                'items_wrap' => '
                                    <ul>
                                        %3$s
                                    </ul>
                                ',
                                'depth' => '2',
                                'walker' => new theme_custom_menu_header()
                            ));
                            echo '</nav>';
                            endwhile;
                        endif;
                    ?> 

                    <?php if(have_rows('buttons_header_button', 'option')) : ?>
                    <nav class="menu-buttons">
                        <ul>
                            <?php
                                while(have_rows('buttons_header_button', 'option')) : the_row();
                                    get_template_part('templates/element_buttons_option');
                                endwhile;
                            ?>
                        </ul>
                    </nav>
                    <?php endif; ?>

                    <div class="hold-pre-header">
                        <?php
                            $location = 'menu_pre_header_01';

                            if (has_nav_menu($location)) :
                                while(have_rows('menu_pre_header_01_appearance', 'option')) : the_row();
                                $size = get_sub_field('size');
                                $separator = get_sub_field('separator');
                                $font_family = get_sub_field('font_family');
                                $font_weight = get_sub_field('font_weight');
                                $text_transform = get_sub_field('text_transform');

                                echo '<nav class="menu-pre-header one '.$size.' '.$separator.' '.$font_family.' '.$font_weight.' '.$text_transform.'">';
                                wp_nav_menu(array(
                                    'theme_location' => $location,
                                    'container_id' => false,
                                    'container' => '',
                                    'container_class' => '',
                                    'items_wrap' => '
                                        <ul>
                                            %3$s
                                        </ul>
                                    ',
                                    'depth' => '1',
                                    'walker' => new theme_custom_menu_header()
                                ));
                                echo '</nav>';
                                endwhile;
                            endif;
                        ?> 

                        <?php
                            $location = 'menu_pre_header_02';

                            if (has_nav_menu($location)) :
                                while(have_rows('menu_pre_header_02_appearance', 'option')) : the_row();
                                $size = get_sub_field('size');
                                $separator = get_sub_field('separator');
                                $font_family = get_sub_field('font_family');
                                $font_weight = get_sub_field('font_weight');
                                $text_transform = get_sub_field('text_transform');

                                echo '<nav class="menu-pre-header two '.$size.' '.$separator.' '.$font_family.' '.$font_weight.' '.$text_transform.'">';
                                wp_nav_menu(array(
                                    'theme_location' => $location,
                                    'container_id' => false,
                                    'container' => '',
                                    'container_class' => '',
                                    'items_wrap' => '
                                        <ul>
                                            %3$s
                                        </ul>
                                    ',
                                    'depth' => '1',
                                    'walker' => new theme_custom_menu_header()
                                ));
                                echo '</nav>';
                                endwhile;
                            endif;
                        ?>
                    </div>
                    
                    <?php if (get_field('search_hamburger', 'option') == 'inside') : ?>
                        <div class="box-form">
                            <?php
                                $args = get_field('search_hamburger_placeholder', 'option');
                                get_template_part('templates/element_search_form', null, $args);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (get_field('search_hamburger', 'option') == 'outside') : ?>
                    <div class="box-search call-search">
                        <div class="icon">
                            <i class="fal fa-search"></i>
                        </div>
                    </div> 
                <?php endif; ?>
                    
                <?php if (get_field('customer_hamburger', 'option') == 'outside') : ?>
                    <div class="box-customer call-customer">
                        <a class="icon">
                            <i class="fal fa-user"></i>
                        </a>
                    </div> 
                <?php endif; ?>
                    
                <div class="box-cart call-cart">
                    <?php echo do_shortcode('[wps_cart_icon]'); ?>
                </div>
                    
                <div class="box-hamburger call-menu">
                    <div class="icon">
                        <i class="fal fa-bars"></i>
                        <i class="fal fa-times"></i>
                    </div>
                </div>
            </header>
        <?php endif; ?>
		<!-- /header -->
                    
        <!-- search -->
        <div class="search-modal">
            <div class="box-form">
                <?php
                    $args = get_field('search_hamburger_placeholder', 'option');
                    get_template_part('templates/element_search_form', null, $args);
                   
                ?>
            </div>            
            <div class="box-close">
                <div class="icon">
                    <i class="fal fa-times"></i>
                </div>
            </div>
        </div>
        <!-- /search -->
        
        <main class="content" id="content">
