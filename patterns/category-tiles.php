<?php
/**
 * Title: Browse by category
 * Slug: dancingspoon/category-tiles
 * Categories: dancingspoon
 * Description: Photo tiles for the categories that have recipes (up to six, biggest first).
 */
$covers = array(
	'breakfast'   => 'cat-breakfast.jpg',
	'dinner'      => 'cat-dinner.jpg',
	'dessert'     => 'cat-dessert.jpg',
	'soups'       => 'cat-soups.jpg',
	'salads'      => 'cat-salads.png',
	'main-course' => 'cat-main-course.jpg',
);
$terms = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC', 'number' => 6, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
$tiles = array();
foreach ( is_wp_error( $terms ) ? array() : $terms as $term ) {
	$url = '';
	if ( isset( $covers[ $term->slug ] ) ) {
		$url = get_theme_file_uri( 'assets/images/' . $covers[ $term->slug ] );
	} else {
		// no shipped cover: the newest recipe's photo in that category
		$latest = get_posts( array( 'numberposts' => 1, 'category' => $term->term_id, 'fields' => 'ids', 'meta_key' => '_thumbnail_id' ) );
		$url    = $latest ? (string) get_the_post_thumbnail_url( $latest[0], 'large' ) : '';
	}
	if ( $url ) {
		$tiles[] = array( get_category_link( $term ), $term->name, $url );
	}
}
if ( ! $tiles ) {
	return;
}
?>
<!-- wp:group {"align":"wide","className":"ds-section","layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignwide ds-section" style="margin-top:var(--wp--preset--spacing--60)">
	<!-- wp:group {"align":"wide","className":"ds-section-head","layout":{"type":"flex","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide ds-section-head">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Browse by category', 'dancingspoon' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><a href="<?php echo esc_url( home_url( '/recipes/' ) ); ?>"><?php esc_html_e( 'All recipes →', 'dancingspoon' ); ?></a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"align":"wide","className":"ds-tiles","layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
	<div class="wp-block-group alignwide ds-tiles">
		<?php foreach ( $tiles as $tile ) : list( $link, $label, $url ) = $tile; $url = esc_url( $url ); $link = esc_url( $link ); ?>
		<!-- wp:cover {"url":"<?php echo $url; ?>","dimRatio":40,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":200,"contentPosition":"bottom left","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
		<div class="wp-block-cover has-custom-content-position is-position-bottom-left" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);min-height:200px"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-40 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
			<!-- wp:html -->
			<a class="ds-tile__link" href="<?php echo $link; ?>"><span class="screen-reader-text"><?php echo esc_html( $label ); ?></span></a>
			<!-- /wp:html -->
			<!-- wp:heading {"level":3,"textColor":"base"} -->
			<h3 class="wp-block-heading has-base-color has-text-color"><?php echo esc_html( $label ); ?></h3>
			<!-- /wp:heading -->
		</div></div>
		<!-- /wp:cover -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
