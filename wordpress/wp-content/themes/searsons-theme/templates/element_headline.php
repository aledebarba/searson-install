<?php
    if(have_rows('headlines')) :
    echo '<div class="box-headline'. $args['headline_card'] .'">';

    while(have_rows('headlines')) : the_row();

    // headline 01
    if(get_row_layout() == 'headline_01') : 
        $headline = get_sub_field('headline');
        $heading = get_sub_field('heading');
        $font_family = get_sub_field('font_family');
        $font_weight = get_sub_field('font_weight');
        $text_transform = get_sub_field('text_transform');

        echo '<h1 class="'. $heading .' '. $font_family .' '. $font_weight .' '. $text_transform .' ">';
        echo $headline;
        echo '</h1>';
    endif;

    // headline 02
    if(get_row_layout() == 'headline_02') : 
        $headline = get_sub_field('headline');
        $heading = get_sub_field('heading');
        $font_family = get_sub_field('font_family');
        $font_weight = get_sub_field('font_weight');
        $text_transform = get_sub_field('text_transform');

        echo '<h2 class="'. $heading .' '. $font_family .' '. $font_weight .' '. $text_transform .' ">';
        echo $headline;
        echo '</h2>';
    endif;

    // headline 03
    if(get_row_layout() == 'headline_03') : 
        $headline = get_sub_field('headline');
        $heading = get_sub_field('heading');
        $font_family = get_sub_field('font_family');
        $font_weight = get_sub_field('font_weight');
        $text_transform = get_sub_field('text_transform');

        echo '<h3 class="'. $heading .' '. $font_family .' '. $font_weight .' '. $text_transform .' ">';
        echo $headline;
        echo '</h3>';
    endif;

    // headline 04
    if(get_row_layout() == 'headline_04') : 
        $headline = get_sub_field('headline');
        $heading = get_sub_field('heading');
        $font_family = get_sub_field('font_family');
        $font_weight = get_sub_field('font_weight');
        $text_transform = get_sub_field('text_transform');

        echo '<h4 class="'. $heading .' '. $font_family .' '. $font_weight .' '. $text_transform .' " >';
        echo $headline;
        echo '</h4>';
    endif;

    // headline 05
    if(get_row_layout() == 'headline_05') : 
        $headline = get_sub_field('headline');
        $heading = get_sub_field('heading');
        $font_family = get_sub_field('font_family');
        $font_weight = get_sub_field('font_weight');
        $text_transform = get_sub_field('text_transform');

        echo '<h5 class="'. $heading .' '. $font_family .' '. $font_weight .' '. $text_transform .' ">';
        echo $headline;
        echo '</h5>';
    endif;

    // headline 06
    if(get_row_layout() == 'headline_06') : 
        $headline = get_sub_field('headline');
        $heading = get_sub_field('heading');
        $font_family = get_sub_field('font_family');
        $font_weight = get_sub_field('font_weight');
        $text_transform = get_sub_field('text_transform');

        echo '<h6 class="'. $heading .' '. $font_family .' '. $font_weight .' '. $text_transform .' ">';
        echo $headline;
        echo '</h6>';
    endif; 

    // paragraph
    if(get_row_layout() == 'paragraph') : 
        $content = get_sub_field('content');

        echo '<div class="paragraph">';
            echo $content;
        echo '</div>';
    endif; 

    endwhile;
    echo '</div>';
    endif;
?>