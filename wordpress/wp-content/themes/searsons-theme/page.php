<?php
	get_header();
	while (have_posts()) : the_post();
		get_template_part('templates/template_content');
	endwhile;
	
	get_footer();
?>