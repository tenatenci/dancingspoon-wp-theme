<?php
/**
 * Title: Hero — latest recipe
 * Slug: dancingspoon/hero
 * Categories: dancingspoon
 * Block Types: core/query
 * Description: The newest post as a split hero: photo left, kicker, title, excerpt and a button right.
 */
?>
<!-- wp:query {"queryId":1,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false,"sticky":""},"align":"wide","className":"ds-hero-query"} -->
<div class="wp-block-query alignwide ds-hero-query">
	<!-- wp:post-template {"layout":{"type":"default"}} -->
		<!-- wp:group {"className":"ds-hero ds-card","backgroundColor":"surface","layout":{"type":"default"}} -->
		<div class="wp-block-group ds-hero ds-card has-surface-background-color has-background">
			<!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"0"}}}} -->
			<div class="wp-block-columns are-vertically-aligned-center">
				<!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
					<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"dancingspoon-hero","style":{"border":{"radius":"0"}}} /-->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"verticalAlignment":"center","width":"42%","className":"ds-hero__copy"} -->
				<div class="wp-block-column is-vertically-aligned-center ds-hero__copy" style="flex-basis:42%">
					<!-- wp:paragraph {"className":"ds-kicker"} -->
					<p class="ds-kicker"><?php esc_html_e( 'Fresh from the kitchen', 'dancingspoon' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:post-terms {"term":"category","className":"is-style-pill"} /-->
					<!-- wp:post-title {"level":1,"isLink":true} /-->
					<!-- wp:post-excerpt {"excerptLength":36} /-->
					<!-- wp:read-more {"content":"Get the recipe","className":"wp-block-button__link wp-element-button","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} /-->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
