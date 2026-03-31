<?php
/**
 * Template Name: Default
 *
 * Standard content width page template.
 *
 * @package kahu
 */

get_header();
?>

	<main id="main" class="template-default">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>

<?php
get_footer();
