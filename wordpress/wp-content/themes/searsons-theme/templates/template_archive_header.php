<?php 
    $archives_header_appearance = get_field('archives_header_appearance', 'option');
    while(have_rows('archives_header_appearance', 'option')) : the_row();
        $extra_styles = get_sub_field('extra_styles');

        while(have_rows('alignment', 'option')): the_row();
            $headlines = get_sub_field('headlines');
        endwhile;
    endwhile;

    $archives_header_image_display = get_field('archives_header_image_display', 'option');

    $archives_header_image = get_field('archives_header_image', 'option');
    if($archives_header_image_display == 'true') :
        while(have_rows('archives_header_image', 'option')) : the_row();
            $styles = get_sub_field('styles');

            $image = get_sub_field('image');
            if($image) :
                $size = 'large';
                $thumb = $image['sizes'][$size];
            endif;

            $image_mobile = get_sub_field('image_mobile');
            if($image_mobile) :
                $size = 'large';
                $thumb_mobile = $image_mobile['sizes'][$size];
            endif;
        endwhile;
    endif;

    $archives_tax_type = get_field('archives_tax_type', 'option');
    $archives_tax_name = get_field('archives_tax_name', 'option');
?>

<section class="widget-slideshow <?php
                echo ' '; echo esc_attr($archives_header_appearance['style']);
                if($headlines) :
                    echo ' alignment-headlines-'; echo $headlines;
                endif;
                echo ' '; if($extra_styles) :
                    echo ' '; echo implode(' ', $extra_styles);
                endif;
                ?>" id="widget-slideshow-header">
    <div class="hold">
        <div class="item<?php echo ' '; echo esc_attr($archives_header_image['position']); ?>">
            <div class="box-text<?php if($styles) : echo ' '; echo implode(' ', $styles); endif; ?>">
                <div class="box-headline">
                    <h6 class="<?php echo esc_attr($archives_tax_type['heading']); echo ' '; echo esc_attr($archives_tax_type['font_family']); echo ' '; echo esc_attr($archives_tax_type['font_weight']); echo ' '; echo esc_attr($archives_tax_type['text_transform']); ?>">
                        <?php
                            if (is_category()) :
                                echo 'Category';
                            endif;
                        
                            if (is_tag()) :
                                echo 'Tag';
                            endif;
                        
                            if (is_search()) :
                                echo 'Search';
                            endif;
                        ?>
                    </h6>
                    <h2 class="<?php echo esc_attr($archives_tax_name['heading']); echo ' '; echo esc_attr($archives_tax_name['font_family']); echo ' '; echo esc_attr($archives_tax_name['font_weight']); echo ' '; echo esc_attr($archives_tax_name['text_transform']); ?>" style="margin-top: 0;">
                        <?php  ?>
                        <?php
                            if (!is_search()) : 
                                single_cat_title();
                            endif;
                        
                            if (is_search()) :
                                echo get_search_query();
                            endif;
                        
                            if (is_home()) :
                                echo 'Blog';
                            endif;
                        ?>
                    </h2>
                    
                    <?php if (is_search()) : ?>
                    <div class="box-form">
                        <?php
                            $args = get_field('search_footer_placeholder', 'option');
                            get_template_part('templates/element_search_form', null, $args);
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if($archives_header_image_display == 'true') : ?>
                <figure class="box-image<?php if($styles) : echo ' '; echo implode(' ', $styles); endif; ?>" role="presentation">
                    <img src="<?php echo esc_url($thumb_mobile); ?>" alt="" class="display-mobile" class="no-lazy">
                    <img src="<?php echo esc_url($thumb); ?>" alt="" class="display-desktop" class="no-lazy">
                </figure>
            <?php endif; ?>
        </div>
    </div>
</section>