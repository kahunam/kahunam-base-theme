<?php
/**
 * The template for displaying all single posts
 *
 * @package kahu
 */

get_header();

$has_sidebar = is_active_sidebar( 'sidebar-1' );
?>

<?php if ( $has_sidebar ) : ?>
	<div class="content-container grid-layout-content-then-sidebar">
<?php endif; ?>

	<main id="main">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>

<?php if ( $has_sidebar ) : ?>
	<?php get_sidebar(); ?>
	</div>
<?php endif; ?>

<?php
get_footer();
