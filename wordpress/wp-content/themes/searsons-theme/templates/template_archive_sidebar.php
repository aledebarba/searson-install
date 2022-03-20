<?php
    if(have_rows('archives_sidebar_items', 'option')) :
    while(have_rows('archives_sidebar_items', 'option')) : the_row();

    // search
    if(get_row_layout() == 'search_bar') : ?>
        <div class="item-sidebar clean">
            <?php get_template_part('templates/element_search_form'); ?>
        </div>
    <?php endif;

    // categories
    if(get_row_layout() == 'categories') : ?>
        <div class="item-sidebar">
            <?php get_template_part('templates/element_sidebar_categories'); ?>
        </div>
    <?php endif;

    // tags
    if(get_row_layout() == 'tags') : ?>
        <div class="item-sidebar">
            <?php get_template_part('templates/element_sidebar_tags'); ?>
        </div>
    <?php endif;

    // latest_posts
    if(get_row_layout() == 'latest_posts') : ?>
        <div class="item-sidebar">
            <?php get_template_part('templates/element_sidebar_latest_posts'); ?>
        </div>
    <?php endif;

    endwhile;
    endif;
?>