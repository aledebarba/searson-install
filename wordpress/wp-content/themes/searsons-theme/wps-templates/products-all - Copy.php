<?php
    defined('ABSPATH') ?: die();
    get_header('wpshopify');
?>

<?php
    // intro slideshow - start //
    // esses itens são editados em Site Managment //

    $products_header_appearance = get_field('products_header_appearance', 'option');
    while(have_rows('products_header_appearance', 'option')) : the_row();
        $extra_styles = get_sub_field('extra_styles');

        while(have_rows('alignment', 'option')): the_row();
            $headlines = get_sub_field('headlines');
        endwhile;
    endwhile;

    $products_header_image_display = get_field('products_header_image_display', 'option');

    $products_header_image = get_field('products_header_image', 'option');
    if($products_header_image_display == 'true') :
        while(have_rows('products_header_image', 'option')) : the_row();
            $styles = get_sub_field('styles');

            $image = get_sub_field('image');
            if($image) :
                $size = 'large';
                $thumb = $image['sizes'][$size];
            endif;

            $image_mobile = get_sub_field('image_mobile');
            if($image_mobile) :
                $size = 'large';
                $thumb_mobile = $image_mobile['sizes'][$size];
            endif;
        endwhile;
    endif;

    $products_tax_name = get_field('products_tax_name', 'option');
?>
<section class="widget-slideshow <?php
                echo ' '; echo esc_attr($products_header_appearance['style']);
                if($headlines) :
                    echo ' alignment-headlines-'; echo $headlines;
                endif;
                echo ' '; if($extra_styles) :
                    echo ' '; echo implode(' ', $extra_styles);
                endif;
                ?>" id="widget-slideshow-header">
    <div class="hold">
        <div class="item<?php echo ' '; echo esc_attr($products_header_image['position']); ?>">
            <div class="box-text<?php if($styles) : echo ' '; echo implode(' ', $styles); endif; ?>">
                <div class="box-headline">
                    <h2 class="<?php echo esc_attr($products_tax_name['heading']); echo ' '; echo esc_attr($products_tax_name['font_family']); echo ' '; echo esc_attr($products_tax_name['font_weight']); echo ' '; echo esc_attr($products_tax_name['text_transform']); ?>" style="margin-top: 0;">
                        Products
                    </h2>
                    
                    <?php /*
                    <div class="box-form">
                        <?php
                            $args = get_field('search_footer_placeholder', 'option');
                            get_template_part('templates/element_search_form', null, $args);
                        ?>
                    </div>
                    */ ?>
                </div>
            </div>
            
            <?php if($products_header_image_display == 'true') : ?>
                <figure class="box-image<?php if($styles) : echo ' '; echo implode(' ', $styles); endif; ?>" role="presentation">
                    <img src="<?php echo esc_url($thumb_mobile); ?>" alt="" class="display-mobile" class="no-lazy">
                    <img src="<?php echo esc_url($thumb); ?>" alt="" class="display-desktop" class="no-lazy">
                </figure>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
    // intro slideshow - end //
?>

<section class="widget-products primary">
    <div class="hold">
        <?php //echo do_shortcode('[wps_search dropzone_payload="#shopwp-storefront-payload"]'); ?>
        
        <?php echo do_shortcode('
        [wps_storefront available_for_sale="true" types_heading="Categories" link_to="wordpress" link_target="_self" sort_by="best_selling" page_size="12" items_per_row="3" filter_by_label_text="Filters" show_vendors="false" page_size_label_text="Products per Page:" link_with_buy_button="true" hide_quantity="true" subscriptions="true" subscriptions_select_on_load="true"]'); ?>
    </div>
</section>

<style>
    .css-ujhgt9-FilterCSS:last-of-type h3:before,
    .css-dtcq1d-FilterCSS:last-of-type h3:before {
        content: 'Categories';
        
        position: relative;
        z-index: 100;
    }
    
    .css-ujhgt9-FilterCSS:last-of-type h3:after,
    .css-dtcq1d-FilterCSS:last-of-type h3:after {
        content: '';
        position: absolute;
        z-index: 90;
        
        top: 0;
        right: 20%;
        bottom: 0;
        left: 0;
        
        background: #f7f7f7;
    }
</style>

<?php get_footer('wpshopify');