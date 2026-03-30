<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kahu
 */

?>

<footer id="colophon" class="site-footer">
	<div class="content-container align-container-center">

		<div class="margin-bottom-large">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo link-unstyled" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<?php echo file_get_contents( get_template_directory() . '/images/logo.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents ?>
			</a>
			<?php
			$kahu_description = get_bloginfo( 'description', 'display' );
			if ( $kahu_description || is_customize_preview() ) :
				?>
				<p class="footer-tagline"><?php echo esc_html( $kahu_description ); ?></p>
			<?php endif; ?>
		</div>

		<div class="footer-nav-columns margin-bottom-large">
			<?php
			$footer_menus = array(
				'footer-1' => __( 'Column 1', 'kahu' ),
				'footer-2' => __( 'Column 2', 'kahu' ),
				'footer-3' => __( 'Column 3', 'kahu' ),
				'footer-4' => __( 'Column 4', 'kahu' ),
			);

			foreach ( $footer_menus as $location => $label ) :
				if ( has_nav_menu( $location ) ) :
					?>
					<div class="footer-nav-column">
						<h3><?php echo esc_html( wp_get_nav_menu_name( $location ) ); ?></h3>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => $location,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</div>
					<?php
				endif;
			endforeach;
			?>
		</div>

		<div class="footer-copyright">
			&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'kahu' ); ?>
		</div>

	</div>
</footer><!-- #colophon -->
