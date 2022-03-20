<?php
    $guts = new gutShopifyELT;
    $guts->get_products();
    $filterData = $guts->filterData();

    defined('ABSPATH') ?: die();
    get_header('wpshopify');
?>

<?php
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

<section class="widget-feed primary">
    <div class="hold">
        <!-- filter bar -->
        <aside class="filter-bar">
            <div class="filter-hold">
                <!-- headline -->
                <div class="item current-items-found-hold">
                    <?php 
                        echo "<p><strong>"; 
                        echo $filterData['info']['filtered']; 
                        echo "</strong> of <strong>";
                        echo $filterData['info']['total'];
                        echo "</strong> products</p>"
                    ?>
                </div>
                <!-- /headline -->

                <!-- current filter buttons and selections - JS generated -->
                <nav class="item guts__current-applied-filters"> <!-- the code MOST have this nav item and class name to work DO NOT CHANGE -->
                    <ul id="guts__current-applied-filters__list"> <!-- the code MOST have this ul item and class name to work DO NOT CHANGE -->
                        <li class="title button small clean font-weight-bold text-transform" style="cursor: auto;">
                            <span class="label">Filters</span>
                        </li>
                    </ul>
                </nav>
                <!-- /current filter buttons and selections END -->
            </div>
                 
            <!-- current selected filter values -->
            <div class="guts__current-filter-values__hold">
                <ul class="current-filter-hold" id="guts__current-applied-filters__values">
                </ul>
            </div>
            
        </aside>
        <!-- /filter results -->
            <?php 
              // show redered results
              echo $guts->renderCards($filterData);

              // show pagination and transfer pagination data to javascript
              echo "
                <div id='guts__pagination'>
                    
                    <!-- the next DIV #guts__data-reference MOST HAVE BE PRESENT AS IS IN THE CODE don't change -->
                        <div id='guts__data-reference' data-total='{$filterData['info']['filtered']}' data-per-page='20' data-page='1'></div>
                    <!-- /the previous DIV #guts__data-reference MOST HAVE BE PRESENT AS IS IN THE CODE don't change -->

                    <div id='guts__pagination-hold'>
                    </div>
               ";
            ?>
    </div>
</section>

<?php
   // get_template_part('templates/template_filter');
   $guts->renderFilterForm();
?>

<?php get_footer('wpshopify');