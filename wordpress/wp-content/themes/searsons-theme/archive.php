<?php
	get_header();
s?>


<?php // echo "Archive page"; ?>

<?php
 //echo do_shortcode('[wps_storefront items_per_row="2" sort_by="price"]');
?>

<?php // echo "End of Archive page"; ?>


<?php
    get_template_part('templates/template_archive_header');
    get_template_part('templates/template_archive_feed');
	get_footer();
?>