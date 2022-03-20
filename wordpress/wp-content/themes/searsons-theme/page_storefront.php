<?php
	/* Template Name: Storefront */

    /*
    card do produto - templates/element_card_products.php
    sidebar que abre qdo clica no filtro - templates/template_filter.php
    
    eu apenas editei o css, vou deixar o javascript pra vc
    
    vou criar uma opcao no painel q o cliente vai poder escolher ate 5 options de filtro pra mostrar antes do "see more button", vou fazer isso com o flexbible content com cada um dos itens 
    
    
    ###
    
    IMPORTANTE
    
    quando a sidebar tiver aberta, adiciona a classe active ao body
    
    ###
    
    CSS
    
    o css do accordion ja está adicionado
    
    edicao de css q precisar fazer, pode adicionar no arquovo _custom.scss, começa na linha 520, //--// Storefront //--//
    
    --- helo
    */

    $guts = new gutShopifyELT;
    $guts->get_products();

    // $filterData = $guts->filterData();

	get_header();

	while (have_posts()) : the_post();

    $style = get_field('style');
    $extra_styles = get_field('extra_styles');
    $field = get_field_object('style');
?>
<section class="widget-slideshow<?php
                echo ' '; echo $style;
                echo ' ';  echo implode(' ', $extra_styles);
                while(have_rows('alignment')): the_row();
                    if($headlines = get_sub_field('headlines')) : echo ' alignment-headlines-'; echo $headlines; endif;
                    if($buttons = get_sub_field('buttons')) : echo ' alignment-buttons-'; echo $buttons; endif;
                endwhile;
                echo ' widget-'.$field['ID'].'';
                echo get_row_index();
                ?>" id="header-widget-class">
    <div class="hold">
        <div class="slide-hold" id="slideshow-<?php echo $field['ID']; echo get_row_index();?>">
            <?php
                if(have_rows('slideshow')) :
                while(have_rows('slideshow')) : the_row();
                    get_template_part('templates/widget_slideshow');
                endwhile;
                endif;
                wp_reset_postdata();
            ?>
        </div>
    </div>

    <script>
        ;(function(slide) {
            var gallery = slide.getElementById('slideshow-<?php echo $field['ID']; echo get_row_index();?>');
            var count = gallery.getElementsByClassName('item').length;

            if (count > 1) {
                var flkty = new Flickity(gallery, {
                    wrapAround: true,
                    cellAlign: 'center',
                    draggable: true,

                    prevNextButtons: true,
                    pageDots: false,

                    autoPlay: 7000,
                    pauseAutoPlayOnHover: true,
                    
                    adaptiveHeight: true,

                    arrowShape:  'M30.6,77.8l1.6-1.6c1-1,1-2.7,0-3.8L13.4,53.8h83.9c1.5,0,2.7-1.2,2.7-2.7v-2.2c0-1.5-1.2-2.7-2.7-2.7H13.4l18.7-18.6c1-1,1-2.7,0-3.8l-1.6-1.6c-1-1-2.7-1-3.8,0l-26,25.9c-1,1-1,2.7,0,3.8l26,25.9C27.8,78.8,29.5,78.8,30.6,77.8z'
                });
            }
        })(document);
    </script>
</section>

<section class="widget-feed primary">
    <div class="hold">
        <!-- filter bar -->
        <aside class="filter-bar">
            <div class="filter-hold">
                <!-- headline -->
                <div class="item current-items-found-hold">
                    <?php 
                        // echo "<p><strong>"; 
                        // echo $filterData['info']['filtered']; 
                        // echo "</strong> of <strong>";
                        // echo $filterData['info']['total'];
                        // echo "</strong> products</p>"
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
        <div class="guts__product_list"></div> <!-- this is the js entry point reference DO NOT CHANGE THIS LINE' -->
    </div>
</section>
<div id="guts-js-render-filter-form"></div>
<?php
    // $guts->renderFilterForm();
?>

<?php
    endwhile;
	get_footer();
?>
