<?php
/**
 * Title: Recipe carousel
 * Slug: dancingspoon/carousel
 * Categories: dancingspoon
 * Block Types: core/query
 * Description: A swipeable row of recipe cards — latest or random, see Customize → Dancing Spoon front page.
 */
$mode     = get_theme_mod( 'dancingspoon_carousel_mode', 'latest' );
$autoplay = get_theme_mod( 'dancingspoon_carousel_autoplay', true ) ? '1' : '0';
$title    = 'random' === $mode ? __( 'You might also like', 'dancingspoon' ) : __( 'Latest from the kitchen', 'dancingspoon' );
?>
<!-- wp:group {"align":"wide","className":"ds-section ds-carousel","layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignwide ds-section ds-carousel" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" style="margin-top:var(--wp--preset--spacing--60)">
	<!-- wp:group {"align":"wide","className":"ds-section-head","layout":{"type":"flex","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide ds-section-head">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading"><?php echo esc_html( $title ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:html -->
		<div class="ds-carousel__nav" role="group" aria-label="<?php esc_attr_e( 'Scroll the recipes', 'dancingspoon' ); ?>">
			<button type="button" class="ds-carousel__btn ds-carousel__prev" aria-label="<?php esc_attr_e( 'Previous recipes', 'dancingspoon' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg></button>
			<button type="button" class="ds-carousel__btn ds-carousel__next" aria-label="<?php esc_attr_e( 'Next recipes', 'dancingspoon' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg></button>
		</div>
		<!-- /wp:html -->
	</div>
	<!-- /wp:group -->
	<!-- wp:query {"queryId":8,"query":{"perPage":6,"pages":1,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false,"sticky":"exclude"},"align":"wide"} -->
	<div class="wp-block-query alignwide">
		<!-- wp:post-template {"className":"ds-carousel__track","layout":{"type":"default"}} -->
			<!-- wp:group {"tagName":"article","className":"ds-card","layout":{"type":"default"}} -->
			<article class="wp-block-group ds-card">
				<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"dancingspoon-card"} /-->
				<!-- wp:group {"className":"ds-card__body","layout":{"type":"default"}} -->
				<div class="wp-block-group ds-card__body">
					<!-- wp:post-terms {"term":"category","className":"is-style-pill"} /-->
					<!-- wp:post-title {"level":3,"isLink":true} /-->
					<!-- wp:post-date {"format":"F j, Y","style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} /-->
				</div>
				<!-- /wp:group -->
			</article>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
