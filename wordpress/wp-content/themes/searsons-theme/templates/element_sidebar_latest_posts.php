<?php
    $icon = get_sub_field('icon');
    $label = get_sub_field('label');

    $number = get_sub_field('number');
    $categories = get_sub_field('categories');
?>

<div class="title-sidebar">
    <span class="icon"><?php echo $icon; ?></span>
    <span class="label"><?php echo $label; ?></span>
</div>

<ul class="list-sidebar inline">
<?php 
    $latest = new WP_Query(
        array(
            'post_type' => 'post',
            'posts_per_page' => $number,
            'order' => '',
            'orderby' => '',
            'cat' => $categories,
        )
    );

    while ($latest->have_posts()) : $latest->the_post(); ?>
        <li>
            <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
        </li>
<?php
    endwhile;
    wp_reset_query();
?>
</ul>