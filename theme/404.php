<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package kahu
 */

get_header();
?>

	<main id="main">
		<h1><?php esc_html_e( 'Page Not Found', 'kahu' ); ?></h1>
		<p><?php esc_html_e( 'This page could not be found.', 'kahu' ); ?></p>
		<?php get_search_form(); ?>
	</main>

<?php
get_footer();
