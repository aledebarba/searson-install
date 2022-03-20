<?php
    get_template_part('templates/element_headline');

    $feed_type = get_sub_field('feed_type');
    $post_type = get_sub_field('post_type');
?>

<?php // Products 
    if($feed_type == 'dynamic' && $post_type == 'wps_products') :

    $products_number_items = get_sub_field('products_number_items'); // items per row
    $products_number_posts = get_sub_field('products_number_posts');
    $products_sort_by = get_sub_field('products_sort_by');
    $products_reverse = get_sub_field('products_reverse');
    $products_collection = get_sub_field('products_collection');

    if($products_number_posts == '4' || $products_number_posts == '8' || $products_number_posts == '12') :
		$products_number_result = "limit='" . $products_number_posts . "' pagination='false'";
    elseif($products_number_posts == '250') :
		$products_number_result = "limit='" . $products_number_posts . "' page_size='16' pagination='true'";
	else :
		$products_number_result = "limit='" . $products_number_posts . "' page_size='" . $products_number_posts . "' pagination='false'";
		//$products_number_result = "limit='" . $products_number_posts . "' page_size='8' pagination='true'";
	endif;

    if($products_reverse == 'true') :
		$products_reverse_true = "reverse='true'";
	endif;

    if($products_collection) :
        $products_collection_true = "collection='". $products_collection ." '";
        //$products_collection_true = "collection='" . esc_html($products_collection->post_title) ." '";
    endif;

    $store = "[wps_products $products_collection_true $products_number_result link_to='wordpress' link_target='_self' items_per_row='$products_number_items' sort_by='$products_sort_by' $products_reverse_true available_for_sale='true' hide_quantity='true' link_with_buy_button='true' subscriptions='true' subscriptions_select_on_load='true']";

    echo do_shortcode($store);
    //echo $store;

    //Other Post Types
    //if($post_type != 'wps_products') :
    else :
    
    $feed_type = get_sub_field('feed_type');
    $feed_format = get_sub_field('feed_format');
    $field = get_sub_field_object('feed_format');
    $sidebar = get_sub_field('sidebar');
?>
    <?php if($feed_format == 'list') :
        if($sidebar == 'yes') :
            echo '<div class="box-layout">'; // layout
                echo '<div class="aside-hold">'; // sidebar
                    get_template_part('templates/widget_content_feed_sidebar');
                echo '</div>'; // sidebar

                echo '<div class="content-hold">'; // content
                    echo '<div class="hold-feed list">';
                        if($feed_type == 'dynamic') :
                            get_template_part('templates/widget_content_feed_query_dynamic');
                        endif;

                        if($feed_type == 'custom') :
                            get_template_part('templates/widget_content_feed_query_custom');
                        endif;
                    echo '</div>';
                echo '</div>'; // content
            echo '</div>'; // layout

            else :
            echo '<div class="hold-feed list">';
                if($feed_type == 'dynamic') :
                    get_template_part('templates/widget_content_feed_query_dynamic');
                endif;

                if($feed_type == 'custom') :
                    get_template_part('templates/widget_content_feed_query_custom');
                endif;
            echo '</div>';
        endif;
    endif; ?>

    <?php if($feed_format == 'carousel col-50' || $feed_format == 'carousel col-33' || $feed_format == 'carousel col-25') : ?>
        <div class="hold-feed <?php echo $feed_format; ?>" id="hold-list-<?php echo $field['ID']; echo get_row_index(); ?>">
            <?php 
                if($feed_type == 'dynamic') :
                    get_template_part('templates/widget_content_feed_query_dynamic');
                endif;

                if($feed_type == 'custom') :
                    get_template_part('templates/widget_content_feed_query_custom');
                endif;
            ?>
        </div>

        <script>
            ;(function(slide) {
                var gallery = slide.getElementById('hold-list-<?php echo $field['ID']; echo get_row_index(); ?>');
                var count = gallery.getElementsByClassName('card').length;

                if (count > 1) {
                    var flkty = new Flickity(gallery, {
                        wrapAround: true,
                        adaptiveHeight: true,

                        <?php
                            while(have_rows('alignment')): the_row();
                            if(get_sub_field('content') == 'center') :
                        ?>
                        cellAlign: 'center',
                        <?php
                            else :
                        ?>
                        cellAlign: 'left',
                        <?php
                            endif;
                            endwhile;
                        ?>

                        draggable: true,

                        prevNextButtons: true,
                        pageDots: false,

                        initialIndex: '.current-post',

                        arrowShape:  'M30.6,77.8l1.6-1.6c1-1,1-2.7,0-3.8L13.4,53.8h83.9c1.5,0,2.7-1.2,2.7-2.7v-2.2c0-1.5-1.2-2.7-2.7-2.7H13.4l18.7-18.6c1-1,1-2.7,0-3.8l-1.6-1.6c-1-1-2.7-1-3.8,0l-26,25.9c-1,1-1,2.7,0,3.8l26,25.9C27.8,78.8,29.5,78.8,30.6,77.8z'
                    });
                }
            })(document);
    </script>
    <?php endif; ?>

    <?php if($feed_format == 'mosaic col-50' || $feed_format == 'mosaic col-33' || $feed_format == 'mosaic col-25' || $feed_format == 'mosaic col-20') : ?>
        <div class="hold-feed <?php echo $feed_format; ?>">
            <?php 
                if($feed_type == 'dynamic') :
                    get_template_part('templates/widget_content_feed_query_dynamic');
                endif;

                if($feed_type == 'custom') :
                    get_template_part('templates/widget_content_feed_query_custom');
                endif;
            ?>
        </div>
    <?php endif; ?>

<?php
    endif;
?>

<?php
    get_template_part('templates/element_buttons');
?>