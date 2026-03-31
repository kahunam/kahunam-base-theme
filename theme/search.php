<?php
/**
 * The template for displaying search results pages
 *
 * @package kahu
 */

get_header();
?>

	<main id="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1><?php printf( esc_html__( 'Search results for: %s', 'kahu' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>

			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;

			the_posts_pagination();

		else :
			echo '<p>' . esc_html__( 'No results found.', 'kahu' ) . '</p>';
			get_search_form();
		endif;
		?>
	</main>

<?php
get_footer();
