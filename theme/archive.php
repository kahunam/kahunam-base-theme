<?php
/**
 * The template for displaying archive pages
 *
 * @package kahu
 */

get_header();
?>

	<main id="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php the_archive_title( '<h1>', '</h1>' ); ?>
			</header>

			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;

			the_posts_pagination();

		else :
			echo '<p>' . esc_html__( 'No posts found.', 'kahu' ) . '</p>';
		endif;
		?>
	</main>

<?php
get_footer();
