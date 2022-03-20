<?php
	get_header();

    get_template_part('templates/template_archive_header');

    $search_refer = $_GET["post_type"];

    if ($search_refer == 'post') {
        load_template(TEMPLATEPATH . '/template_archive_feed');
    }

    elseif ($search_refer == 'wps_products') { ?>
        
        <section class="widget-feed primary">
            <div class="hold">
                <div class="hold-feed mosaic col-25">
                    <?php
                        if (have_posts()) :

                        while (have_posts()) : the_post();
                        get_template_part('templates/element_card_products');

                        endwhile;

                        else :
                            echo '<p>Sorry, no products were found.</p>';
                        endif;

                        wp_reset_query();
                    ?>

                    <?php
                        /*

                        $args = array(
                            's'=> get_search_query(),
                            'post_type' => 'wps_products',
                            'posts_per_page'=> -1,
                        );

                        $query = new WP_Query($args);
                        echo $post->ID;

                        $store = "[wps_storefront post_id='' link_to='wordpress' link_target='_self' sort_by='best_selling' page_size='12' items_per_row='3' filter_by_label_text='Filters' types_heading='Categories' page_size_label_text='Products per Page:']";

                        echo do_shortcode($store);
                        */
                    ?>
                </div>
                
                <?php theme_pagination(); ?>
            </div>
        </section>

    <?php };

	get_footer();
?>