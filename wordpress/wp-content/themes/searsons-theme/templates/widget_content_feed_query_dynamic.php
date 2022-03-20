<?php
    $feed_format = get_sub_field('feed_format');
    $post_type = get_sub_field('post_type');
    $number_posts = get_sub_field('number_posts');
    $post_order = get_sub_field('post_order');
    $post_order_by = get_sub_field('post_order_by');
    $post_author = get_sub_field('post_author');
    $post_exclude = get_sub_field('post_exclude');
    $pagination = get_sub_field('pagination'); // show pagination or not

    // archive //
    if(is_archive()) :
        $card_format = get_field('post_card_format', 'option');
    else :
        $card_format = get_sub_field('card_format');
    endif;
    // archive //

    // post type = post //
    if((($post_type) == 'post')) :
        $posts_categories = get_sub_field('posts_categories');
    endif;
    // post type = post //

    $current_post = $post->ID; ?>
<?php
    $feed = new WP_Query(
        array(
            'post_type' => $post_type,
            'posts_per_page' => $number_posts,
            'order' => $post_order,
            'orderby' => $post_order_by,
            'post__not_in' => $post_exclude,
            'author' => $post_author,
            'cat' => $posts_categories,
            'paged' => $paged,
        )
    );
    while ($feed->have_posts()) : $feed->the_post();
?>
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
<?php
    endwhile;

    if($pagination == 'yes') :
        theme_pagination();
    endif;
    
    wp_reset_query();
?>