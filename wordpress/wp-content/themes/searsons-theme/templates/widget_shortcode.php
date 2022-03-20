<?php
    get_template_part('templates/element_headline');
	echo do_shortcode(get_sub_field('content'));
    get_template_part('templates/element_buttons');
?>