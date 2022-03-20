<?php
    $content_type = get_sub_field('content_type');
    $content_position = get_sub_field('content_position');

    $image = get_sub_field('image');
    $image_mobile = get_sub_field('image_mobile');
    $image_styles = get_sub_field('image_styles');

    if($image) :
        $size = 'large';
        $thumb = $image['sizes'][$size];
    endif;

    if($image_mobile) :
        $size_mobile = 'large';
        $thumb_mobile = $image_mobile['sizes'][$size_mobile];
    endif;

    $video = get_sub_field('video');
    $poster = get_sub_field('poster');
?>

<div class="item<?php if((($content_type) == 'image') || (($content_type) == 'video')) : echo ' '; echo $content_position; endif; ?>">
    <!-- text -->
    <div class="box-text<?php if($image_styles) : echo ' '; echo implode(' ', $image_styles); endif; ?>">
        <?php
            get_template_part('templates/element_headline');
            get_template_part('templates/element_buttons');
        ?>
    </div>
    <!-- /text -->
    <?php
        if(($content_type) == "image") :
    ?>
        <figure class="box-image<?php if($image_styles) : echo ' '; echo implode(' ', $image_styles); endif; ?>" role="presentation">
            <?php if($image_mobile) : ?>
                <img src="<?php echo esc_url($thumb_mobile); ?>" alt="" class="display-mobile" class="no-lazy">
                <img src="<?php echo esc_url($thumb); ?>" alt="" class="display-desktop" class="no-lazy">
            <?php else : ?>
                <img src="<?php echo esc_url($thumb); ?>" alt="" class="no-lazy">
            <?php endif; ?>
        </figure>
    <?php
        endif;
        if(($content_type) == "video") :
    ?>
        <figure class="box-video" role="presentation">
            <video autoplay muted loop poster="<?php echo $poster;?>">
                <source src="<?php echo $video; ?>" type="video/mp4">
            </video>
        </figure>
    <?php endif; ?>
</div>