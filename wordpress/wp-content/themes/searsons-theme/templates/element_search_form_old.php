<?php /*
<form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
    <label><span>Search bar</span></label>
    <input type="search" class="field" placeholder="<?php if($args == true) : echo $args; else : echo the_field('search_placeholder', 'option'); endif; ?>" value="<?php echo get_search_query() ?>" name="s" title="Search for:" />
    <button type="submit" class="submit"><i class="far fa-search"></i></button>
</form>
*/ ?>

<form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
    <label><span>Search bar</span></label>
    <input type="search" class="field" placeholder="<?php if($args == true) : echo $args; else : echo the_field('search_placeholder', 'option'); endif; ?>" value="<?php echo get_search_query() ?>" name="s" title="Search for:" />
    <input name="post_type" type="hidden" value="wps_products" />
    <button type="submit" class="submit"><i class="far fa-search"></i></button>
</form>