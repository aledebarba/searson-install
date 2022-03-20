<?php
// button //
if(have_rows('custom_button')) :
while(have_rows('custom_button')) : the_row();

    if(get_sub_field('show') == 'yes') :

    $button_text = get_sub_field('text');
    $button_class = get_sub_field('class');
    $button_size = get_sub_field('size');

    $button_font_family = get_sub_field('font_family');
    $button_font_weight = get_sub_field('font_weight');
    $button_text_transform = get_sub_field('text_transform');

    $button_icon = get_sub_field('icon');
    $button_icon_position = get_sub_field('icon_position');
?>
    <div class="box-buttons box-buttons-card">
        <div class="button <?php echo $button_class; echo ' '; echo $button_size; echo ' '; echo $button_font_family; echo ' '; echo $button_font_weight; echo ' '; echo $button_text_transform; echo ' '; echo $button_icon_position; ?>"><span class="label"><?php echo $button_text; ?></span><?php if($button_icon) : echo '<span class="icon">'; echo $button_icon; echo '</span>'; endif; ?></div>
    </div>
<?php
    endif;

endwhile;
endif;
// button //
?>