		</main>

        <?php
            $prefooter = get_field('site_template_prefooter', 'option');
            if($prefooter):
            
                if(have_rows('content_widgets', $prefooter->ID)) :
                while (have_rows('content_widgets', $prefooter->ID)) : the_row(); 
                    get_template_part('templates/template_widgets');
                endwhile;
                endif;
                wp_reset_postdata();

            endif;
        ?>

        <!-- footer -->
        <?php
            //** custom footer -- start **//

            if (get_field('footer_appearance_set') == true) :
            // custom footer -- page level  //

            while(have_rows('footer_appearance')) : the_row();
            $logo = get_field('footer_appearance_logo');
            $logo_size = get_field('footer_appearance_logo_size');
            $display = get_field('footer_appearance_display');
            $style = get_field('footer_appearance_style');
        ?>
        <footer class="widget-footer<?php echo ' '; echo $display; echo ' '; echo $style; ?>">
            <div class="hold">
                <?php if (get_field('search_footer', 'option') == 'above') : ?>
                    <div class="hold-form above">
                        <?php
                            $args = get_field('search_footer_placeholder', 'option');
                            get_template_part('templates/element_search_form', null, $args);
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- hold header -->
                <div class="hold-header">
                    <figure class="box-logo<?php echo ' '; echo $logo;  echo ' '; echo $logo_size; ?>" role="presentation">
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
        <?php
            endwhile;
            else :
            // default footer -- panel level  //
                    
            while(have_rows('footer_appearance', 'option')) : the_row();
            $logo = get_sub_field('logo');
            $logo_size = get_sub_field('logo_size');
            $display = get_sub_field('display');
            $style = get_sub_field('style');
        ?>
        <footer class="widget-footer<?php echo ' '; echo $display; echo ' '; echo $style; ?>">
            <div class="hold">
                <?php if (get_field('search_footer', 'option') == 'above') : ?>
                    <div class="hold-form above">
                        <?php
                            $args = get_field('search_footer_placeholder', 'option');
                            get_template_part('templates/element_search_form', null, $args);
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- hold header -->
                <div class="hold-header">
                    <figure class="box-logo<?php echo ' '; echo $logo;  echo ' '; echo $logo_size; ?>" role="presentation">
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
        <?php
            endwhile;
            endif;
            //** custom footer -- end **//
        ?>  
                
                    <!-- buttons -->
                    <nav class="menu-buttons">
                        <ul>
                            <?php
                                while(have_rows('buttons_footer_button', 'option')) : the_row();
                                    get_template_part('templates/element_buttons_option');
                                endwhile;
                            ?>
                        </ul>
                    </nav>
                    <!-- /buttons -->
                </div>
                <!-- /hold header -->
                
                <!-- hold menu -->
                <div class="hold-menu">
                    <?php
                        $location = 'menu_footer';
                    
                        if (has_nav_menu($location)) :
                            while(have_rows('menu_footer_appearance', 'option')) : the_row();
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
                                'depth' => '1',
                                'walker' => new theme_custom_menu_footer()
                            ));
                            echo '</nav>';
                            endwhile;
                        endif;
                    ?>
                    
                    <?php
                        $location = 'menu_footer_extra';
                    
                        if (has_nav_menu($location)) :
                            while(have_rows('menu_footer_extra_appearance', 'option')) : the_row();
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
                                'depth' => '1',
                                'walker' => new theme_custom_menu_footer()
                            ));
                            echo '</nav>';
                            endwhile;
                        endif;
                    ?>

                    <?php 
                        while(have_rows('menu_footer_social_appearance', 'option')) : the_row();
                        $size = get_sub_field('size');
                        $separator = get_sub_field('separator');
                        $font_family = get_sub_field('font_family');
                        $font_weight = get_sub_field('font_weight');
                        $text_transform = get_sub_field('text_transform');
                    
                        echo '<nav class="menu-social '.$size.' '.$separator.' '.$font_family.' '.$font_weight.' '.$text_transform.'">';
                    ?>
                        <ul>
                        <?php
                            if(have_rows('site_social_media', 'option')) :
                            while(have_rows('site_social_media', 'option')) : the_row();
                            
                            $icon = get_sub_field('icon');
                            $link = get_sub_field('link');
                            
                            if($link) :
                                $url = $link['url'];
                                $title = $link['title'];
                                $target = $link['target'] ? $link['target'] : '_blank';
                            endif;
                        ?>
                            <li>
                                <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                                    <?php echo $icon; ?> <?php echo esc_html($title); ?>
                                </a>
                            </li>
                        <?php
                            endwhile;
                            endif;
                        ?>
                        </ul>
                    <?php
                        echo '</nav>';
                        endwhile;
                    ?>
                </div>
                <!-- /hold menu -->
                
                <?php // hold badges
                    if(get_field('badges', 'option')) :
                    while(have_rows('badges', 'option')) : the_row();
                        
                        if(have_rows('groups')) :
                        echo '<div class="hold-badges">';
                        while(have_rows('groups')) : the_row();
                            $title = get_sub_field('title');
                            $icon = get_sub_field('icon');
                            $size = get_sub_field('size');
                            // item start //
                            echo '<div class="item">';
                                echo '<div class="title">';
                                    echo $title;
                                    echo ' ';
                                    echo $icon;
                                echo '</div>';

                                $images = get_sub_field('gallery');
                
                                if($images) :
                                foreach($images as $image) :
                
                                $link_extra = get_field('link_extra', $image['ID']);
                                    if($link_extra) :
                                        $url = $link_extra['url'];
                                        $title = $link_extra['title'];
                                        $target = $link_extra['target'] ? $link_extra['target'] : '_self';
                                    endif;
                                ?>

                                <figure class="image <?php echo $size; ?>">
                                    <?php if(get_field('link_extra', $image['ID'])) : ?>
                                        <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">     
                                    <?php endif; ?>
                                        <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                                    <?php if(get_field('link_extra', $image['ID'])) : ?>
                                        </a> 
                                    <?php endif; ?>
                                </figure>

                                <?php
                                endforeach;
                                endif;
                            echo '</div>';
                            // item end //
                        endwhile;
                        echo '</div>';
                        endif;
                
                    endwhile;
                    endif;
                ?>
                
                <?php if (get_field('search_footer', 'option') == 'below') : ?>
                    <div class="hold-form below">
                        <?php
                            $args = get_field('search_footer_placeholder', 'option');
                            get_template_part('templates/element_search_form', null, $args);
                        ?>
                    </div>
                <?php endif; ?>

                <!-- hold footer -->
                <div class="hold-footer">
                    <?php
                        if(get_field('footer_disclaimer', 'option')) :
                            echo '<div class="disclaimer">';
                            the_field('footer_disclaimer', 'option');
                            echo '</div>';
                        endif;
                    ?>

                    <?php 
                        while(have_rows('menu_institutional_appearance', 'option')) : the_row();
                        $size = get_sub_field('size');
                        $separator = get_sub_field('separator');
                        $font_family = get_sub_field('font_family');
                        $font_weight = get_sub_field('font_weight');
                        $text_transform = get_sub_field('text_transform');
                    
                        echo '<nav class="menu-institutional '.$size.' '.$separator.' '.$font_family.' '.$font_weight.' '.$text_transform.'">';
                    ?>
                        <ul role="presentation">
                            <li class="copy"><span>&copy;<?php echo date("Y"); echo ' '; echo get_bloginfo('name');?></span></li>
                            <?php wp_nav_menu(array(
                                'theme_location' => 'menu_institutional',
                                'container_id' => false,
                                'container' => '',
                                'container_class' => '',
                                'items_wrap' => '%3$s',
                                'depth' => '1',
                                'walker' => new theme_custom_menu_institutional()
                            )); ?>
                            <li class="credit"><a href="https://circulateonline.com/" target="_blank">Circulate</a></li>
                        </ul>
                    <?php
                        echo '</nav>';
                        endwhile;
                    ?>
                </div>
                <!-- /hold footer -->
            </div>
		  </footer>
		  <!-- /footer -->
	</div>

	<?php
        wp_footer();
                
        the_field('site_script_footer', 'option'); // script footer

        //** header -- script for style - start **//
        if (get_field('header_appearance_set') == true) :
        // custom header -- page level  //
            while(have_rows('header_appearance')) : the_row();
            $position = get_field('header_appearance_position');
            if ($position == 'absolute') :
                ?>
                    <script>
                        // header_appearance script //
                        var header = document.getElementById("header-absolute");

                        if(document.getElementById("header-widget-class").classList.contains("primary")) {
                            header.classList.add("primary");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("primary-inverse")) {
                            header.classList.add("primary-inverse");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("secondary")) {
                            header.classList.add("secondary");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("secondary-inverse")) {
                            header.classList.add("secondary-inverse");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("extra")) {
                            header.classList.add("extra");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("extra-inverse")) {
                            header.classList.add("extra-inverse");
                        }
                        // header_appearance script //
                    </script>
                <?php
            endif;
            endwhile;
        else :
        // default header -- panel level  //
            while(have_rows('header_appearance', 'option')) : the_row();
            $position = get_sub_field('position');
            if ($position == 'absolute') :
                ?>
                    <script>
                        // header_appearance script //
                        var header = document.getElementById("header-absolute");

                        if(document.getElementById("header-widget-class").classList.contains("primary")) {
                            header.classList.add("primary");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("primary-inverse")) {
                            header.classList.add("primary-inverse");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("secondary")) {
                            header.classList.add("secondary");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("secondary-inverse")) {
                            header.classList.add("secondary-inverse");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("extra")) {
                            header.classList.add("extra");
                        }

                        if(document.getElementById("header-widget-class").classList.contains("extra-inverse")) {
                            header.classList.add("extra-inverse");
                        }
                        // header_appearance script //
                    </script>
                <?php
            endif;
            endwhile;
        endif;
        //** header -- script for style - end **//
    ?>

    <script>
        // Customer Link //
        $('.call-customer').click(function() {
            window.open('<?php the_field('customers_url', 'option'); ?>');
        });
        
        // Fix viewport size mobile //
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
        
        // GA Linker
		gtag('require', 'linker');
		gtag('linker:autoLink', ['searsons.com', 'shop.searsons.com', 'searsons.myshopify.com'], false, true);
    </script>
</body>
</html>