<?php
   // default //
    if(get_row_layout() == 'button') : 
        $button_link = get_sub_field('link');
        if($button_link) :
            $button_url = $button_link['url'];
            $button_label = $button_link['title'];
            $button_target = $button_link['target'] ? $button_link['target'] : '_self';
        endif;

        $button_class = get_sub_field('class');
        $button_size = get_sub_field('size');

        $button_font_family = get_sub_field('font_family');
        $button_font_weight = get_sub_field('font_weight');
        $button_text_transform = get_sub_field('text_transform');

        $button_icon = get_sub_field('icon');
        $button_icon_position = get_sub_field('icon_position'); { ?>
            <li>
                <a href="<?php echo esc_url($button_url); ?>" class="button <?php echo $button_size; echo ' '; echo $button_class; echo ' '; echo $button_font_family; echo ' '; echo $button_font_weight; echo ' '; echo $button_text_transform; echo ' '; echo $button_icon_position; ?>" target="<?php echo esc_attr($button_target); ?>">
                <span class="label"><?php echo esc_html($button_label); ?></span>
                <?php if($button_icon) : echo '<span class="icon">'; echo $button_icon; echo '</span>'; endif; ?></a>
            </li>
    <?php } endif;
    // default //

    // branded //
    if(get_row_layout() == 'button_branded') : 
        $button_link = get_sub_field('link');
        if($button_link) :
            $button_url = $button_link['url'];
            $button_label = $button_link['title'];
            $button_target = $button_link['target'] ? $button_link['target'] : '_self';
        endif;

        $button_class = get_sub_field('class');
        $button_channel = get_sub_field('channel');
        $button_size = get_sub_field('size');

        $button_font_family = get_sub_field('font_family');
        $button_font_weight = get_sub_field('font_weight');
        $button_text_transform = get_sub_field('text_transform');
        $button_icon_position = get_sub_field('icon_position'); { ?>

            <li>
                <a href="<?php echo esc_url($button_url); ?>" class="button <?php echo $button_class; echo ' '; echo $button_channel; echo ' '; echo $button_size; echo ' '; echo $button_font_family; echo ' '; echo $button_font_weight; echo ' '; echo $button_text_transform; echo ' '; echo $button_icon_position; ?>" target="<?php echo esc_attr($target); ?>">
                <span class="label"><?php echo esc_html($button_label); ?></span>
                <?php
                if($button_channel == 'apple-music') :
                    echo '<span class="icon"><i class="fab fa-apple"></i></span>';
                endif;

                if($button_channel == 'deezer') :
                    echo '<span class="icon"><i class="fab fa-deezer"></i></span>';
                endif;

                if($button_channel == 'facebook') :
                    echo '<span class="icon"><i class="fab fa-facebook"></i></span>';
                endif;

                if($button_channel == 'google') :
                    echo '<span class="icon"><i class="fab fa-google"></i></span>';
                endif;

                if($button_channel == 'instagram') :
                    echo '<span class="icon"><i class="fab fa-instagram"></i></span>';
                endif;
                
                if($button_channel == 'linkedin') :
                    echo '<span class="icon"><i class="fab fa-linkedin"></i></span>';
                endif;
                
                if($button_channel == 'spotify') :
                    echo '<span class="icon"><i class="fab fa-spotify"></i></span>';
                endif;
                
                if($button_channel == 'twitter') :
                    echo '<span class="icon"><i class="fab fa-twitter"></i></span>';
                endif;
                
                if($button_channel == 'whatsapp') :
                    echo '<span class="icon"><i class="fab fa-whatsapp"></i></span>';
                endif;
                
                if($button_channel == 'youtube') :
                    echo '<span class="icon"><i class="fab fa-youtube"></i></span>';
                endif;
                ?>
                </a>
            </li>
    <?php } endif;
    // branded //
?>