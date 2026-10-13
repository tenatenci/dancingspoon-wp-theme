<?php
/**
 * Title: Browse by category
 * Slug: dancingspoon/category-tiles
 * Categories: dancingspoon
 * Description: A row of round category photos with the name underneath, every category that has recipes (biggest first).
 */
$covers = array(
	'breakfast'   => 'cat-breakfast.jpg',
	'dinner'      => 'cat-dinner.jpg',
	'dessert'     => 'cat-dessert.jpg',
	'soups'       => 'cat-soups.jpg',
	'salads'      => 'cat-salads.png',
	'main-course' => 'cat-main-course.jpg',
);
$cats = array();
foreach ( dancingspoon_categories( 'count' ) as $term ) {
	$url = '';
	if ( isset( $covers[ $term->slug ] ) ) {
		$url = get_theme_file_uri( 'assets/images/' . $covers[ $term->slug ] );
	} else {
		// no shipped cover: the newest recipe's photo in that category
		$latest = get_posts( array( 'numberposts' => 1, 'category' => $term->term_id, 'fields' => 'ids', 'meta_key' => '_thumbnail_id' ) );
		$url    = $latest ? (string) get_the_post_thumbnail_url( $latest[0], 'medium' ) : '';
	}
	// no photo at all: the circle shows the category's initial instead
	$cats[] = array( get_category_link( $term ), $term->name, $url );
}
if ( ! $cats ) {
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
	<!-- wp:html -->
	<ul class="ds-cats" role="list">
		<?php foreach ( $cats as $cat ) : list( $link, $label, $url ) = $cat; ?>
		<li class="ds-cat"><a class="ds-cat__link" href="<?php echo esc_url( $link ); ?>">
			<span class="ds-cat__img<?php echo $url ? '' : ' ds-cat__img--initial'; ?>" aria-hidden="true"><?php if ( $url ) : ?><img src="<?php echo esc_url( $url ); ?>" alt="" width="240" height="240" loading="lazy" decoding="async"/><?php else : ?><?php echo esc_html( mb_substr( $label, 0, 1 ) ); ?><?php endif; ?></span>
			<span class="ds-cat__name"><?php echo esc_html( $label ); ?></span>
		</a></li>
		<?php endforeach; ?>
	</ul>
	<!-- /wp:html -->
</div>
<!-- /wp:group -->
