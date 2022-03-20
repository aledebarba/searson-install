<?php
	$content = get_sub_field('content');
	$content_font_family = get_sub_field('content_font_family');

	$image = get_sub_field('image');
	$embed = get_sub_field('embed');

    $element_link = get_sub_field('element_link');
    $element_caption = get_sub_field('element_caption');

	if($image) :
		$size = 'large';
		$thumb = $image['sizes'][$size];
        $alt = $image['alt'];
	endif;

    if($element_link) :
        $url = $element_link['url'];
        $title = $element_link['title'];
        $target = $element_link['target'] ? $element_link['target'] : '_self';
    endif;
?>
    <div class="box-text <?php echo $content_font_family; ?>">
        <?php
            get_template_part('templates/element_headline');
                echo $content;
            get_template_part('templates/element_buttons');
        ?>
    </div>

    <?php if(get_sub_field('extra_element') == 'show_image') : ?>
    <figure class="box-image">
        <div class="hold-image">
            <?php if ($element_link) : ?>
                <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">
            <?php endif; ?>
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($alt); ?>">
            <?php if ($element_link) : ?> 
                </a>
            <?php endif; ?>
        </div>

        <?php if(get_sub_field('element_caption')) :
            echo '<figcaption>';
            echo $element_caption;
            echo '</figcaption>';
        endif; ?>
    </figure>
    <?php endif; ?>

    <?php if(get_sub_field('extra_element') == 'show_video_embed') : ?>
    <figure class="box-embed">
        <div class="hold-embed">
            <?php echo $embed; ?>
        </div>

        <?php if(get_sub_field('element_caption')) :
            echo '<figcaption>';
            echo $element_caption;
            echo '</figcaption>';
        endif; ?>
    </figure>
    <?php endif; ?>