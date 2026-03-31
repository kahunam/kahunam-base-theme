<?php
/**
 * The main template file
 *
 * @package kahu
 */

get_header();
?>

	<main id="main">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_content();
			}
			the_posts_pagination();
		} else {
			echo '<p>' . esc_html__( 'No posts found.', 'kahu' ) . '</p>';
		}
		?>
	</main>

<?php
get_footer();
