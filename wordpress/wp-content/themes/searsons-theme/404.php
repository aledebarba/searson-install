<?php
	get_header();

    $page_404 = get_field('site_template_404', 'option');
    if($page_404):

        $style = get_field('style', $page_404->ID);
        $extra_styles = get_field('extra_styles', $page_404->ID);
        $field = get_field_object('style', $page_404->ID); ?>
            <section class="widget-slideshow<?php
                            echo ' '; echo $style;
                            echo ' ';  echo implode(' ', $extra_styles);
                            while(have_rows('alignment')): the_row();
                                if($headlines = get_sub_field('headlines')) : echo ' alignment-headlines-'; echo $headlines; endif;
                                if($buttons = get_sub_field('buttons')) : echo ' alignment-buttons-'; echo $buttons; endif;
                            endwhile;
                            echo ' widget-'.$field['ID'].'';
                            echo get_row_index();
                            ?>" id="header-widget-class">
                <div class="hold">
                    <div class="slide-hold" id="slideshow-<?php echo $field['ID']; echo get_row_index();?>">
                        <?php
                            if(have_rows('slideshow', $page_404->ID)) :
                            while(have_rows('slideshow', $page_404->ID)) : the_row();
                                get_template_part('templates/widget_slideshow');
                            endwhile;
                            endif;
                            wp_reset_postdata();
                        ?>
                    </div>
                </div>

                <script>
                    ;(function(slide) {
                        var gallery = slide.getElementById('slideshow-<?php echo $field['ID']; echo get_row_index();?>');
                        var count = gallery.getElementsByClassName('item').length;

                        if (count > 1) {
                            var flkty = new Flickity(gallery, {
                                wrapAround: true,
                                cellAlign: 'left',
                                draggable: true,

                                prevNextButtons: true,
                                pageDots: false,

                                autoPlay: 7000,
                                pauseAutoPlayOnHover: true,

                                adaptiveHeight: true,

                                arrowShape:  'M30.6,77.8l1.6-1.6c1-1,1-2.7,0-3.8L13.4,53.8h83.9c1.5,0,2.7-1.2,2.7-2.7v-2.2c0-1.5-1.2-2.7-2.7-2.7H13.4l18.7-18.6c1-1,1-2.7,0-3.8l-1.6-1.6c-1-1-2.7-1-3.8,0l-26,25.9c-1,1-1,2.7,0,3.8l26,25.9C27.8,78.8,29.5,78.8,30.6,77.8z'
                            });
                        }
                    })(document);
                </script>
            </section>

       <?php
        if(have_rows('content_widgets', $page_404->ID)) :
        while (have_rows('content_widgets', $page_404->ID)) : the_row(); 
            get_template_part('templates/template_widgets');
        endwhile;
        endif;
        wp_reset_postdata();

    endif;

	get_footer();
?>