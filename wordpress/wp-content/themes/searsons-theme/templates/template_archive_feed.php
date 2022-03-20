<?php
    $archives_feed_appearance = get_field('archives_feed_appearance', 'option');
    $archives_sidebar_display = get_field('archives_sidebar_display', 'option');
?>

<?php
    if (is_search()) :

        $post_search = new WP_Query("s=$s&post_type='post'&showposts=0"); 
        echo $post_search ->found_posts.' Content';

        $products_search = new WP_Query("s=$s&post_type='wps_products'&showposts=0"); 
        echo $products_search ->found_posts.' Products';

     endif;
?>

<section class="widget-feed<?php echo ' '; echo esc_attr($archives_feed_appearance['style']); ?>" id="widget-feed">
    <div class="hold">
        <?php if($archives_sidebar_display == 'yes') :
            echo '<div class="box-layout">'; // layout

                echo '<div class="aside-hold">'; // sidebar
                    get_template_part('templates/template_archive_sidebar');
                echo '</div>'; // sidebar
                
                echo '<div class="content-hold">'; // content
                    echo '<div class="hold-feed '. esc_attr($archives_feed_appearance['feed_format']) .'">';
                        get_template_part('templates/template_archive_query');
                    echo '</div>';
                echo '</div>'; // content

            echo '</div>'; // layout

            else :
            echo '<div class="hold-feed '. esc_attr($archives_feed_appearance['feed_format']) .'">';
                get_template_part('templates/template_archive_query');
            echo '</div>';
            endif;
        
            theme_pagination();
        ?>
    </div>
</section>