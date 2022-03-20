<?php
    get_template_part('templates/element_headline');

    $feed_format = get_sub_field('feed_format');
    $card_format = get_sub_field('card_format');
    $field = get_sub_field_object('feed_format');
?>
<div class="hold-gallery <?php echo $feed_format; ?>" data-columns>
<?php
    $images = get_sub_field('content');
    
    if($images) :
    foreach($images as $image) :
    
        $link_extra = get_field('link_extra', $image['ID']);
    
        if($link_extra) :
            $url = $link_extra['url'];
            $title = $link_extra['title'];
            $target = $link_extra['target'] ? $link_extra['target'] : '_self';
        endif;
?>
    <figure class="item <?php echo $card_format; ?>">
        <?php if(get_field('link_extra', $image['ID'])) : ?>
        <a href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>">     
        <?php else : ?>
        <a href="<?php echo esc_url($image['sizes']['large']); ?>" class="lightbox-gallery">
        <?php endif; ?>
            <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" <?php if($image['caption']) : echo' title="'.esc_attr($image['caption']).'"'; endif; ?>>
        </a>
        <?php if($image['caption']) : ?>
        <figcaption>
            <p><?php echo esc_attr($image['caption']); ?></p>
        </figcaption>
        <?php endif; ?>
    </figure>
<?php
    endforeach;
    wp_reset_postdata();
    endif;
?>
</div>
                
<?php get_template_part('templates/element_buttons'); ?>