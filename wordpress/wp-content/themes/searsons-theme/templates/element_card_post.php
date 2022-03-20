<?php
    // archive //
    if(is_archive() || is_search() || is_home()) :
        $archives_card_format = get_field('archives_card_format', 'option');
    else :
        $card_format = get_sub_field('card_format');
    endif;
    // archive //
?>
<div class="hold-card">
    <div class="box-image <?php echo ' ';
                if(is_archive() || is_search() || is_home()) :
                    echo esc_attr($archives_card_format['element_format']);
                else :
                    echo esc_attr($card_format['element_format']);
                endif;
                ?>" role="presentation">
        <figure class="hold-image">
            <?php
                if (has_post_thumbnail()) { ?>
                    <img src="<?php $src = wp_get_attachment_image_src(get_post_thumbnail_id($page->ID), 'thumbnail', false, ''); echo $src[0]; ?>" alt="<?php the_title(); ?>">
                <?php }

                else {
                    echo '<img src="'.get_bloginfo('stylesheet_directory').'/assets/images/placeholder-logo.png" alt="">';
                }
            ?>
            
            <a href="<?php the_permalink() ?>" class="link-over"></a>
        </figure>
    </div>
    
    <div class="box-text">
        <div class="box-headline box-headline-card">
            <h3 class="h3"><?php the_title(); ?></h3>
        </div>

        <?php
            the_excerpt();
            get_template_part('templates/element_card_button');
        ?>
        
        <aside class="box-info">
            <div class="item-info">
                <?php the_category(', '); ?>
            </div>

            <div class="item-info">
                <time datetime="<?php the_time('Y-m-d'); ?>"><i class="fal fa-clock"></i> <?php the_time('F jS, Y'); ?></time>
            </div>
        </aside>
        
        <a href="<?php the_permalink() ?>" class="link-over"></a>
    </div>
</div>