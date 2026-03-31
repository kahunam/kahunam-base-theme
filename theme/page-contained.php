<?php
/**
 * Template Name: Contained
 *
 * Narrow content width for text-heavy pages.
 *
 * @package kahu
 */

get_header();
?>

	<main id="main" class="template-contained">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>

<?php
get_footer();
