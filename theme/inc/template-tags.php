<?php
/**
 * Custom template tags for this theme
 *
 * @package kahu
 */

if ( ! function_exists( 'kahu_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function kahu_posted_on() {
		$time_string = '<time class="published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			'<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
endif;

if ( ! function_exists( 'kahu_posted_by' ) ) :
	/**
	 * Prints HTML with meta information about theme author.
	 */
	function kahu_posted_by() {
		printf(
			'<span class="screen-reader-text">%1$s</span><span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span>',
			esc_html__( 'Posted by', 'kahu' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
endif;

if ( ! function_exists( 'kahu_comment_count' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function kahu_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'kahu' ), get_the_title() ) );
		}
	}
endif;

if ( ! function_exists( 'kahu_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 * This template tag is used in the entry header.
	 */
	function kahu_entry_meta() {

		if ( 'post' === get_post_type() ) {

			kahu_posted_by();
			kahu_posted_on();

			$categories_list = get_the_category_list( __( ', ', 'kahu' ) );
			if ( $categories_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Posted in', 'kahu' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			$tags_list = get_the_tag_list( '', __( ', ', 'kahu' ) );
			if ( $tags_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Tags:', 'kahu' ),
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		if ( ! is_singular() ) {
			kahu_comment_count();
		}

		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'kahu' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	}
endif;

if ( ! function_exists( 'kahu_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function kahu_entry_footer() {

		if ( 'post' === get_post_type() ) {

			kahu_posted_by();
			kahu_posted_on();

			$categories_list = get_the_category_list( __( ', ', 'kahu' ) );
			if ( $categories_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Posted in', 'kahu' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			$tags_list = get_the_tag_list( '', __( ', ', 'kahu' ) );
			if ( $tags_list ) {
				printf(
					'<span><span class="screen-reader-text">%1$s</span>%2$s</span>',
					esc_html__( 'Tags:', 'kahu' ),
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		if ( ! is_singular() ) {
			kahu_comment_count();
		}

		edit_post_link(
			sprintf(
				wp_kses(
					__( 'Edit <span class="screen-reader-text">%s</span>', 'kahu' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	}
endif;

if ( ! function_exists( 'kahu_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 */
	function kahu_post_thumbnail() {
		if ( ! kahu_can_show_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<figure>
				<?php the_post_thumbnail(); ?>
			</figure>
			<?php
		else :
			?>
			<figure>
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail(); ?>
				</a>
			</figure>
			<?php
		endif;
	}
endif;

if ( ! function_exists( 'kahu_comment_avatar' ) ) :
	/**
	 * Returns the HTML markup to generate a user avatar.
	 *
	 * @param mixed $id_or_email The Gravatar to retrieve.
	 */
	function kahu_get_user_avatar_markup( $id_or_email = null ) {
		if ( ! isset( $id_or_email ) ) {
			$id_or_email = get_current_user_id();
		}

		return sprintf( '<div class="vcard">%s</div>', get_avatar( $id_or_email, kahu_get_avatar_size() ) );
	}
endif;

if ( ! function_exists( 'kahu_discussion_avatars_list' ) ) :
	/**
	 * Displays a list of avatars involved in a discussion for a given post.
	 *
	 * @param array $comment_authors Comment authors to list as avatars.
	 */
	function kahu_discussion_avatars_list( $comment_authors ) {
		if ( empty( $comment_authors ) ) {
			return;
		}
		echo '<ol>', "\n";
		foreach ( $comment_authors as $id_or_email ) {
			printf(
				"<li>%s</li>\n",
				kahu_get_user_avatar_markup( $id_or_email ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		echo '</ol>', "\n";
	}
endif;

if ( ! function_exists( 'kahu_the_posts_navigation' ) ) :
	/**
	 * Wraps `the_posts_pagination` for use throughout the theme.
	 */
	function kahu_the_posts_navigation() {
		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => __( 'Newer posts', 'kahu' ),
				'next_text' => __( 'Older posts', 'kahu' ),
			)
		);
	}
endif;
