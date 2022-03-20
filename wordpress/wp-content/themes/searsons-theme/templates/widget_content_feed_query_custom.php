<?php
    $feed_format = get_sub_field('feed_format');
    $post_include = get_sub_field('post_include');

    // archive //
    if(is_archive()) :
        $card_format = get_field('post_card_format', 'option');
    else :
        $card_format = get_sub_field('card_format');
    endif;
    // archive //

    $current_post = $post->ID;

    $feed = new WP_Query(
        array(
            'post_type' => 'any',
            'post__in' => $post_include,
            'orderby' => 'post__in',
            'posts_per_page' => -1,
        )
    );

    while ($feed->have_posts()) : $feed->the_post(); ?>

    <?php if($post->post_type == 'wps_products') : // Products ?>
        <?php get_template_part('templates/element_card_products'); ?>
    <?php endif; ?>

    <?php if($post->post_type != 'wps_products') : // Other Post Types ?>
        <div class="card <?php echo esc_attr($card_format['direction']); echo ' '; echo esc_attr($card_format['size']); echo ' '; echo esc_attr($card_format['style']); if($current_post == $post->ID) : echo ' current-post'; endif; ?>">
            <?php
            // post type = post //
            if($post->post_type == 'post') :
                get_template_part('templates/element_card_post');
            endif;
            // post type = post //
            
            // post type = page //
            if($post->post_type == 'page') :
                get_template_part('templates/element_card_page');
            endif;
            // post type = page //
            ?>  
        </div>
    <?php endif; ?>

<?php
    endwhile;
    wp_reset_query();
?>