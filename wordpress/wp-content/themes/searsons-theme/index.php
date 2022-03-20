<?php get_header(); ?>
		<?php if (is_home()) : ?>
			<?php get_template_part('archive'); ?>
		<?php endif; ?>
<?php get_footer(); ?>