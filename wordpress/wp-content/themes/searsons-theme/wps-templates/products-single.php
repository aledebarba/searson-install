<?php
    defined('ABSPATH') ?: die();

    get_header('wpshopify');

    // MOUNT OBJECTS
    global $post;
    $Products = ShopWP\Factories\Render\Products\Products_Factory::build();
    $ProductsAPI = ShopWP\Factories\API\Items\Products_Factory::build();
    $DB =ShopWP\Factories\DB\Products_Factory::build();
    $DBobjIds = $DB->get_product_ids_from_post_ids([$post->ID]);
    
	while (have_posts()) : the_post();
    
        $pid = $DBobjIds[0];
        // run graphQL
        $result = $ProductsAPI->get_product([
            'product_id' => $pid,
            'schema' => '
                title
                description
                metafields(first: 30) {
                edges {
                    node {
                        key
                        value
                    }
                }
            }     
            '
        ]);
        // spread results
        $refObj = get_object_vars($result);
        $prodArr = get_object_vars($refObj['product']);
        // get metafields node array
        $metaEdgesArr = get_object_vars($prodArr['metafields']);      
?>

<!-- product -->
<section class="widget-article widget-product primary">
    <div class="hold element-left element-top element-full">
        <div class="box-text">
            <div class="box-headline">
                <h6 class="h6 font-primary font-weight-medium text-transform"><a href="/products">Products</a></h6>
                
                <h1 class="h2"><?php the_title(); ?></h1>
                <div id="product_pricing"></div>
            </div>

            <div id="product_description"></div>

            <?php
            // subscription
                if (get_field('subscription') == 1) :
                        echo do_shortcode('[wps_products_buy_button subscriptions="true" post_id="' . $post->ID . '"]');
                else :
                    echo do_shortcode('[wps_products_buy_button post_id="' . $post->ID . '"]');
                endif;
            ?>
            
            <!-- wine details -->
            <div class="box-featured">
                <h6 class="h4 font-secondary font-weight-bold text-default">Wine Details</h6>
                
                <div class="hold-featured"><?php foreach($metaEdgesArr['edges'] as $node) {
    
                        $nodeArr = get_object_vars($node);
                        $nodeRef = get_object_vars($nodeArr['node']);
                    ?><div class="item <?php echo $nodeRef['key']; ?>">
                            <div class="box-icon">
                                <?php if($nodeRef['key']=='Tasting_Notes') {
                                    echo '<i class="fas fa-music"></i>';
                                } ?>

                                <?php if($nodeRef['key']=='Region') {
                                    echo '<i class="fas fa-globe-stand"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Country') {
                                    echo '<i class="fas fa-flag"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Maturity') {
                                    echo '<i class="fas fa-wine-bottle"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Sweetness') {
                                    echo '<i class="fas fa-signal"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Unit_Volume') {
                                    echo '<i class="fas fa-wine-bottle"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Grape') {
                                    echo '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="grapes" class="svg-inline--fa fa-grapes" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M56 400C25.07 400 0 425.1 0 456S25.07 512 56 512s56-25.07 56-56S86.93 400 56 400zM112 231.9C142.1 231.9 168 206.9 168 176S142.9 120 112 120S56 145.1 56 176S81.1 231.9 112 231.9zM392.4 119.6c18.08-37.78 11.56-81.72-19.76-114.8c-6.009-6.352-16.8-6.354-22.8 .0097c-40.48 42.96-39.09 103.8 3.263 145.9l8.246 8.246c42.12 42.35 103 43.74 145.9 3.263c6.354-5.998 6.343-16.78-.0041-22.79C474.1 108.1 430.2 101.5 392.4 119.6zM248 208c30.93 0 56-25.07 56-56S278.9 96 248 96S192 121.1 192 152S217.1 208 248 208zM88 256C57.07 256 32 281.1 32 312s25.07 56 56 56S144 342.9 144 312S118.9 256 88 256zM200 368c-30.93 0-56 25.07-56 56S169.1 480 200 480S256 454.9 256 424S230.9 368 200 368zM336 344c-30.93 0-55.93 25.05-55.93 55.97S305.1 456 336 456s56-25.07 56-56S366.9 344 336 344zM360 208c-30.93 0-56 25.07-56 56S329.1 320 360 320S416 294.9 416 264S390.9 208 360 208zM224 232C193.1 232 168 257.1 168 288S193.1 344 224 344S280 318.9 280 288S254.9 232 224 232z"></path></svg>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Colour') {
                                    echo '<i class="fas fa-tint"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Style') {
                                    echo '<i class="fas fa-wine-glass-alt"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Sustainable_Certification') {
                                    echo '<i class="fas fa-badge-check"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Producer') {
                                    echo '<i class="fas fa-user-hard-hat"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='ABV') {
                                    echo '<i class="fas fa-percent"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Organic') {
                                    echo '<i class="fas fa-seedling"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Biodynamic') {
                                    echo '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="wheat" class="svg-inline--fa fa-wheat" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M481.7 113.5c25.38-27.37 32.37-71.1 29.75-112.1c-29-1.875-80.25-.2499-112.6 29.87c-37.12 37.37-30.1 104.4-29.75 112.1C410.7 146.3 454.1 139.3 481.7 113.5zM438.2 169.5c-42.88 10.3-72.32 5.803-97.12 4.125c-.9519-6.761-11.35-52.07 1-101.6c-12.87-31.87-41.06-55.37-45.81-58.99C268.4 37.4 245.5 69.02 244.4 102.5c1.125 28.5 18.62 56.49 40.87 78.99L243.7 223c1.625-5.1 2.874-12.12 3.124-18.5C246.7 157.8 200.7 119.5 194.5 114.9c-27.88 24.37-50.75 55.1-51.1 89.49C143.6 232.9 161.2 260.8 183.5 283.3l-41.5 41.49c1.625-5.996 2.75-11.99 3-18.37c-.125-46.87-46.13-84.1-52.38-89.75C64.75 241 41.87 272.6 40.75 306.1c1 28.5 18.62 56.37 40.87 78.99l-72.25 72.24c-12.5 12.5-12.5 32.75 0 45.25c12.51 12.51 32.74 12.51 45.25 0l72.25-72.25c22.75 22.75 50.37 40.12 79.37 41.12c33.25-1.25 65.75-24.87 89.75-52.24c-17-19.5-50.25-50.62-89.5-51.1c-6.625 0-13 1.125-19.25 2.75l41.5-41.5c22.62 22.87 50.25 40.25 79.37 41.25c33.12-1.25 65.62-24.88 89.75-52.37c-16.1-19.37-50.37-50.62-89.62-51.87c-6.625 0-13 1-19.12 2.625l41.38-41.38c22.75 22.75 50.37 40.12 79.37 41.12c33.25-1.25 65.75-24.87 89.75-52.25C486.9 201 464.1 179.8 438.2 169.5z"></path></svg>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Vegan') {
                                    echo '<i class="fas fa-leaf"></i>';
                                } ?>
                                
                                <?php if($nodeRef['key']=='Food_Matching') {
                                    echo '<i class="fas fa-utensils-alt"></i>';
                                } ?>
                            </div>
                            
                            <?php
                                echo '<div class="box-info">';
                                    echo '<strong id="key_'.$nodeRef['key'].'">'.$nodeRef['key'].'</strong>';
                                    echo '<span id="key_'.$nodeRef['key'].'_'.$nodeRef['value'].'">'.$nodeRef['value'].'</span>';
                                echo '</div>';
                            ?>
                        </div>
                    
                        <script>
                            <?php if($nodeRef['key']=='Tasting_Notes') {echo 'document.getElementById("key_Tasting_Notes").innerHTML = "Tasting Notes";';} ?>
                            <?php if($nodeRef['key']=='Food_Matching') {echo 'document.getElementById("key_Food_Matching").innerHTML = "Food Matching";';} ?>
                            <?php if($nodeRef['key']=='Unit_Volume') {echo 'document.getElementById("key_Unit_Volume").innerHTML = "Unit Volume";';} ?>
                            <?php if($nodeRef['key']=='Sustainable_Certification') {echo 'document.getElementById("key_Sustainable_Certification").innerHTML = "Sustainable Certification";';} ?>
                            <?php if($nodeRef['key']=='Organic') {echo 'document.getElementById("key_Organic_TRUE").innerHTML = "Yes";';} ?>
                            <?php if($nodeRef['key']=='Biodynamic') {echo 'document.getElementById("key_Biodynamic_TRUE").innerHTML = "Yes";';} ?>
                            <?php if($nodeRef['key']=='Vegan') {echo 'document.getElementById("key_Vegan_TRUE").innerHTML = "Yes";';} ?>
                        </script>
                    <?php } ?></div></div>
            <!-- /wine details -->    
        </div>

        <figure class="box-image">
            <div id="product_gallery"></div>
        </figure>
    </div>
</section>
<!-- /product -->

<script>
    // Hide Wine Details Div
    if($(".hold-featured").html().length ==0) {
        $(".box-featured").remove();
    }
</script>

<!-- upselling -->
<section class="widget-feed secondary-inverse alignment-content-center alignment-headlines-center alignment-buttons-center">
    <div class="hold">
        <div class="box-headline">
            <h6 class="h3 font-primary font-weight-bold text-transform">You May Also Like</h6>
        </div>
        
        <div class="hold-feed carousel col-25" id="hold-upselling">
            <?php
                $feed = new WP_Query(
                    array(
                        'post_type' => 'wps_products',
                        'orderby' => 'rand',
                        'posts_per_page'=>'12', 
                        'post__not_in'=> array(get_the_ID()),
                    )
                );
                while ($feed->have_posts()) : $feed->the_post();
            
                get_template_part('templates/element_card_products');
            
                endwhile;
                wp_reset_query();
            ?>
        </div>
        
        <script>
            ;(function(slide) {
                var gallery = slide.getElementById('hold-upselling');
                var count = gallery.getElementsByClassName('card').length;

                if (count > 1) {
                    var flkty = new Flickity(gallery, {
                        wrapAround: true,
                        adaptiveHeight: true,
                        
                        cellAlign: 'center',

                        draggable: true,

                        prevNextButtons: true,
                        pageDots: false,
                        
                        imagesLoaded: true,

                        //initialIndex: '.current-post',

                        arrowShape:  'M30.6,77.8l1.6-1.6c1-1,1-2.7,0-3.8L13.4,53.8h83.9c1.5,0,2.7-1.2,2.7-2.7v-2.2c0-1.5-1.2-2.7-2.7-2.7H13.4l18.7-18.6c1-1,1-2.7,0-3.8l-1.6-1.6c-1-1-2.7-1-3.8,0l-26,25.9c-1,1-1,2.7,0,3.8l26,25.9C27.8,78.8,29.5,78.8,30.6,77.8z'
                    });
                }
            })(document);
    </script>
    </div>
</section>
<!-- /upselling -->

<?php /*
<!-- reviews -->
<section class="widget-feed secondary-inverse alignment-content-center alignment-headlines-center alignment-buttons-center">
    <div class="hold">
        <div class="box-headline">
            <h1 class="h2 font-primary font-weight-bold text-default ">Reviews</h1>
        </div>
        
        <div class="hold-feed carousel col-50" id="hold-list-reviews">
            <!-- item -->
            <div class="card row regular transparent">
                <div class="hold-card">
                    <div class="box-text">
                        <p>Review 01 - Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                    </div>
                </div>
            </div>
            <!-- /item -->
            
            <!-- item -->
            <div class="card row regular transparent">
                <div class="hold-card">
                    <div class="box-text">
                        <p>Review 02 - Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                    </div>
                </div>
            </div>
            <!-- /item -->
            
            <!-- item -->
            <div class="card row regular transparent">
                <div class="hold-card">
                    <div class="box-text">
                        <p>Review 03 - Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                    </div>
                </div>
            </div>
            <!-- /item -->
        </div>
        <script>
            ;(function(slide) {
                var gallery = slide.getElementById('hold-list-reviews');
                var count = gallery.getElementsByClassName('card').length;

                if (count > 1) {
                    var flkty = new Flickity(gallery, {
                        wrapAround: true,
                        cellAlign: 'center',

                        draggable: true,

                        prevNextButtons: true,
                        pageDots: false,

                        initialIndex: '.current-post',

                        arrowShape:  'M30.6,77.8l1.6-1.6c1-1,1-2.7,0-3.8L13.4,53.8h83.9c1.5,0,2.7-1.2,2.7-2.7v-2.2c0-1.5-1.2-2.7-2.7-2.7H13.4l18.7-18.6c1-1,1-2.7,0-3.8l-1.6-1.6c-1-1-2.7-1-3.8,0l-26,25.9c-1,1-1,2.7,0,3.8l26,25.9C27.8,78.8,29.5,78.8,30.6,77.8z'
                    });
                }
            })(document);
    </script>
    </div>
</section>
<!-- /reviews -->
*/ ?>

<div style="display: none; visibility: hidden; opacity: 0; overflow: hidden; height: 0; position: absolute; z-index: -1; left: -200%;">
    <div id="product_title"></div>
    <div id="product_buy_button"></div>
</div>

<?php
	endwhile;
?>

<?php 
    $Products->products(
       apply_filters('shopwp_products_single_args', [
          'dropzone_product_buy_button' => '#product_buy_button',
          'dropzone_product_title' => '#product_title',
          'dropzone_product_description' => '#product_description',
          'dropzone_product_pricing' => '#product_pricing',
          'dropzone_product_gallery' => '#product_gallery',
          'title_type_font_size' => '32px',
          'link_to' => 'none',
          'excludes' => false,
          'post_id' => $post->ID,
          'pagination' => false,
          'limit' => 1,
          'skeleton' => 'components/skeletons/products-single',
       ])
    );
?>  

<?php
    get_footer('wpshopify');
?>