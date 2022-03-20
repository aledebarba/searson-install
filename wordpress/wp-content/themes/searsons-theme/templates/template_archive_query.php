<?php
    $archives_card_format = get_field('archives_card_format', 'option');

    if (have_posts()) :
    while (have_posts()) : the_post();
?>

    <?php/* if($post->post_type == 'wps_products') : ?>
        <?php get_template_part('templates/element_card_products'); ?>
    <?php else : ?>
    <?php endif; */ ?>

    <div class="card <?php echo esc_attr($archives_card_format['direction']); echo ' '; echo esc_attr($archives_card_format['size']); echo ' '; echo esc_attr($archives_card_format['style']); if($current_post == $post->ID) : echo ' current-post'; endif; ?>">
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

<?php
    endwhile;

    else :
        echo '<p>Sorry, no posts were found.</p>';
    endif;

    wp_reset_query();
?>