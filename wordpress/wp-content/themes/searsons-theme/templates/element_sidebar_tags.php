<?php
    $icon = get_sub_field('icon');
    $label = get_sub_field('label');

    $number = get_sub_field('number');
    $order = get_sub_field('order');
    $order_by = get_sub_field('order_by');
    $count = get_sub_field('count');
?>

<div class="title-sidebar">
    <span class="icon"><?php echo $icon; ?></span>
    <span class="label"><?php echo $label; ?></span>
</div>

<ul class="list-sidebar">
<?php 
    $args = array(
        'taxonomy' => array('post_tag'),
        
        'number'     => $number,
        'order'      => $order,
        'orderby'    => $order_by,
        'show_count' => $count,
        
        'format'     => 'list',
        'smallest'   => 1,
        'largest'    => 1,
        'unit'       => 'em',
    ); 
 
    wp_tag_cloud($args);
?>
</ul>