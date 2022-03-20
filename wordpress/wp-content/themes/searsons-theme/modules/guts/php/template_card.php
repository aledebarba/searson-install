<div class="card column regular default">
    <div class="hold-card hold-card-product">
        <div class="box-image" role="presentation">
            <div class="hold-image-product">
                <?php echo do_shortcode('[wps_products_gallery link_to="wordpress" link_target="_self" post_id="'. $post->ID .'"]'); ?>
            </div>
        </div>

        <div class="box-text">
            <div class="box-headline box-headline-card">
                <h3 class="h4"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
                <h6><?php echo do_shortcode('[wps_products_pricing post_id="' . $post->ID . '"]'); ?></h6>
            </div>
            
            <?php // echo do_shortcode('[wps_products_description description_length="100" post_id="' . $post->ID . '"]'); ?>

            <div class="box-info">
                <?php echo do_shortcode('[wps_products_buy_button hide_quantity="true" post_id="' . $post->ID . '"]'); ?>
            </div>
            
            <?php /*
            <div class="box-buttons box-buttons-card">
                <a href="<?php the_permalink() ?>" class="button regular default font-primary font-weight-bold text-transform" style="width: 100%">
                    <span class="label">View Product</span>
                </a>
            </div>
            */ ?>
        </div>
    </div>
</div>