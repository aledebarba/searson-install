<?php

	get_header();
	while (have_posts()) : the_post();

		if(have_rows('page_header')) :
			while(have_rows('page_header')) : the_row();
				get_template_part('templates/widget_intro');
			endwhile;
		endif;

		get_template_part('templates/template_content');

        if(is_singular('post')) :
            get_template_part('templates/template_footer_single');
        endif;
	endwhile;
	get_footer();
?>