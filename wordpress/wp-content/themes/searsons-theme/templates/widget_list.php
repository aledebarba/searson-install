<?php
    $list_format = get_sub_field('list_format');
    $card_format = get_sub_field('card_format');
    $accordion_format = get_sub_field('accordion_format');
    $field = get_sub_field_object('list_format');
    $id = get_the_ID();

    get_template_part('templates/element_headline');
?>
<div class="hold-feed <?php echo $list_format; ?>" id="hold-list-<?php echo $field['ID']; echo get_row_index(); echo $id; ?>"<?php if($list_format == 'timeline') : echo ' data-columns'; endif; ?>>
    <?php
        if(have_rows('content')) :
        while(have_rows('content')) : the_row();

        $image = get_sub_field('image');
        $icon = get_sub_field('icon');       

        $link = get_sub_field('link');

        if($image) :
            $image_size = 'medium';
            $image_thumb = $image['sizes'][$image_size];
        endif;

        if($link) :
            $url = $link['url'];
            $title = $link['title'];
            $target = $link['target'] ? $link['target'] : '_self';
        endif;

        $text_content = get_sub_field('content');

        if($list_format == 'accordion') :
    ?>
        <div class="item-accordion <?php echo esc_attr($accordion_format['size']); echo ' '; echo esc_attr($accordion_format['style']); ?>">
            <div class="title-accordion">
                <?php
                    if(have_rows('headlines')) :
                    while(have_rows('headlines')) : the_row();

                        // headline 01
                        if(get_row_layout() == 'headline_01') : 
                            $headline = get_sub_field('headline');

                            echo '<h6 class="h6 font-extra font-weight-regular">';
                            echo $headline;
                            echo '</h6>';
                        endif;

                    endwhile;
                    endif;
                ?>

                <div class="button-accordion">
                    <i class="fal fa-plus"></i>
                    <i class="fal fa-minus"></i>
                </div>
            </div>

            <div class="content-accordion">
                <div class="hold-accordion">
                    <?php
                        if(get_sub_field('optional_element') == 'show_image') :
                    ?>
                        <div class="box-image <?php echo ' '; echo esc_attr($accordion_format['element_format']); ?>" role="presentation">
                            <figure class="hold-image">
                                <?php if(get_sub_field('link')) : ?>
                                    <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                                <?php endif; ?>
                                    <img src="<?php echo esc_url($image_thumb); ?>">
                                <?php if(get_sub_field('link')) : ?>
                                    </a>
                                <?php endif; ?>
                            </figure>
                        </div>
                    <?php
                        endif;
                        if(get_sub_field('optional_element') == 'show_icon') :
                    ?>
                        <div class="box-icon <?php echo ' '; echo esc_attr($accordion_format['element_format']); echo ' '; echo esc_attr($accordion_format['icon_size']); ?>" role="presentation">
                            <div class="hold-icon">
                                <?php if(get_sub_field('link')) : ?>
                                    <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                                <?php endif; ?>
                                    <?php echo $icon; ?>
                                <?php if(get_sub_field('link')) : ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                        endif;
                    ?>

                    <div class="box-text">
                        <?php
                            $args = array(
                                'headline_card' => ' box-headline-card',
                                'buttons_card' => 'box-buttons-card'
                            );

                            get_template_part('templates/element_headline', null, $args);
                            echo $text_content;
                            get_template_part('templates/element_buttons', null, $args);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="card <?php echo esc_attr($card_format['direction']); echo ' '; echo esc_attr($card_format['size']); echo ' '; echo esc_attr($card_format['style']); ?>">
            <div class="hold-card">
                <?php
                    if(get_sub_field('optional_element') == 'show_image') : 
                ?>
                    <div class="box-image <?php echo ' '; echo esc_attr($card_format['element_format']); ?>" role="presentation">
                        <figure class="hold-image">
                            <?php if(get_sub_field('link')) : ?>
                                <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                            <?php endif; ?>
                                <img src="<?php echo esc_url($image_thumb); ?>">
                            <?php if(get_sub_field('link')) : ?>
                                </a>
                            <?php endif; ?>
                        </figure>
                    </div>
                <?php
                    endif;
                    if(get_sub_field('optional_element') == 'show_icon') :
                ?>
                    <div class="box-icon <?php echo ' '; echo esc_attr($card_format['element_format']); echo ' '; echo esc_attr($card_format['icon_size']); ?>" role="presentation">
                        <div class="hold-icon">
                            <?php if(get_sub_field('link')) : ?>
                                <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
                            <?php endif; ?>
                                <?php echo $icon; ?>
                            <?php if(get_sub_field('link')) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
                    endif;
                ?>

                <div class="box-text">
                    <?php
                        $args = array(
                            'headline_card' => ' box-headline-card',
                            'buttons_card' => 'box-buttons-card'
                        );
                    
                        get_template_part('templates/element_headline', null, $args);
                        echo $text_content;
                        get_template_part('templates/element_buttons', null, $args);
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php
        endwhile;
        endif;
    ?>
</div>

<?php if($list_format == 'carousel col-50' || $list_format == 'carousel col-33' || $list_format == 'carousel col-25') : ?>
<script>
    ;(function(slide) {
        var gallery = slide.getElementById('hold-list-<?php echo $field['ID']; echo get_row_index(); echo $id; ?>');
        var count = gallery.getElementsByClassName('card').length;

        if (count > 1) {
            var flkty = new Flickity(gallery, {
                wrapAround: true,
                draggable: true,
                
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

                prevNextButtons: true,
                pageDots: false,

                arrowShape:  'M30.6,77.8l1.6-1.6c1-1,1-2.7,0-3.8L13.4,53.8h83.9c1.5,0,2.7-1.2,2.7-2.7v-2.2c0-1.5-1.2-2.7-2.7-2.7H13.4l18.7-18.6c1-1,1-2.7,0-3.8l-1.6-1.6c-1-1-2.7-1-3.8,0l-26,25.9c-1,1-1,2.7,0,3.8l26,25.9C27.8,78.8,29.5,78.8,30.6,77.8z'
            });
        }
    })(document);
</script>
<?php endif; ?>

<?php get_template_part('templates/element_buttons'); ?>