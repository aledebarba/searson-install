<?php
    if(have_rows('announcement_bar', 'option')) :
    while(have_rows('announcement_bar', 'option')) : the_row();

    if(get_sub_field('display') == 'yes') :

    $display_close = get_sub_field('display_close');

    $style = get_sub_field('style');
    $size = get_sub_field('size');

    $text_desktop = get_sub_field('text_desktop');
    $text_mobile = get_sub_field('text_mobile');

    if(!empty($_COOKIE["announcement_bar"]) && $_COOKIE["announcement_bar"] == 'true') :
    else :
    ?>
    <div class="announcement-bar <?php echo $style; echo ' '; echo $size; ?>">
        <div class="text-desktop">
             <?php echo $text_desktop; ?>
        </div>

        <div class="text-mobile">
             <?php echo $text_mobile; ?>
        </div>

        <?php get_template_part('templates/element_buttons'); ?>

        <?php if(($display_close) == 'yes') : ?>
            <div class="announcement-close button small clean text-transform icon-end">
                <span class="label">Close</span>
                <span class="icon"><i class="fal fa-times"></i></span>
            </div>
        <?php endif; ?>
    </div>

    <style>
		.announcement-bar p {
			padding: 0 10%;
			text-align: center;
		}
	</style>
<?php
    endif;
    
    endif;

    endwhile;
    endif;
?>