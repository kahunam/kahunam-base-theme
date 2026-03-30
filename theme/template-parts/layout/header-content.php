<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kahu
 */

?>

<header id="masthead" class="site-header">
	<div class="content-container align-container-center header-inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo link-unstyled" rel="home" aria-label="<?php bloginfo( 'name' ); ?>">
			<?php echo file_get_contents( get_template_directory() . '/images/logo.svg' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents ?>
		</a>

		<nav id="site-navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'kahu' ); ?>">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'kahu' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="3" y1="6" x2="21" y2="6"/>
					<line x1="3" y1="12" x2="21" y2="12"/>
					<line x1="3" y1="18" x2="21" y2="18"/>
				</svg>
			</button>

			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => 'div',
					'container_class' => 'menu',
					'fallback_cb'    => false,
				)
			);
			?>
		</nav><!-- #site-navigation -->

	</div>
</header><!-- #masthead -->
