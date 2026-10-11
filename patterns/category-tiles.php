<?php
/**
 * Title: Browse by category
 * Slug: dancingspoon/category-tiles
 * Categories: dancingspoon
 * Description: Six photo tiles linking to the main recipe categories.
 */
$tiles = array(
	array( 'breakfast', __( 'Breakfast', 'dancingspoon' ), 'cat-breakfast.jpg' ),
	array( 'dinner', __( 'Dinner', 'dancingspoon' ), 'cat-dinner.jpg' ),
	array( 'dessert', __( 'Dessert', 'dancingspoon' ), 'cat-dessert.jpg' ),
	array( 'soups', __( 'Soups', 'dancingspoon' ), 'cat-soups.jpg' ),
	array( 'salads', __( 'Salads', 'dancingspoon' ), 'cat-salads.png' ),
	array( 'main-course', __( 'Main course', 'dancingspoon' ), 'cat-main-course.jpg' ),
);
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
		<?php foreach ( $tiles as $tile ) : list( $slug, $label, $file ) = $tile; $url = esc_url( get_theme_file_uri( 'assets/images/' . $file ) ); $link = esc_url( home_url( '/category/' . $slug . '/' ) ); ?>
		<!-- wp:cover {"url":"<?php echo $url; ?>","dimRatio":40,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":200,"contentPosition":"bottom left","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
		<div class="wp-block-cover has-custom-content-position is-position-bottom-left" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);min-height:200px"><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-40 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
			<!-- wp:heading {"level":3,"textColor":"base"} -->
			<h3 class="wp-block-heading has-base-color has-text-color"><a href="<?php echo $link; ?>"><?php echo esc_html( $label ); ?></a></h3>
			<!-- /wp:heading -->
		</div></div>
		<!-- /wp:cover -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
