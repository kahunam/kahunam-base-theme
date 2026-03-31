<?php
/**
 * Template Name: Full Width
 *
 * No content container — blocks control their own width.
 *
 * @package kahu
 */

get_header();
?>

	<main id="main" class="template-full-width">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>

<?php
get_footer();
