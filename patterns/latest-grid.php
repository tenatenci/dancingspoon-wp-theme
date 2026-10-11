<?php
/**
 * Title: Latest recipes grid
 * Slug: dancingspoon/latest-grid
 * Categories: dancingspoon
 * Block Types: core/query
 * Description: Three-column recipe cards, newest first, skipping the hero post.
 */
?>
<!-- wp:group {"align":"wide","className":"ds-section","layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}}} -->
<div class="wp-block-group alignwide ds-section" style="margin-top:var(--wp--preset--spacing--60)">
	<!-- wp:group {"align":"wide","className":"ds-section-head","layout":{"type":"flex","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide ds-section-head">
		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading"><?php esc_html_e( 'Latest recipes', 'dancingspoon' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->
	<!-- wp:query {"queryId":2,"query":{"perPage":9,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","inherit":false,"sticky":"exclude"},"align":"wide"} -->
	<div class="wp-block-query alignwide">
		<!-- wp:post-template {"layout":{"type":"grid","columnCount":3},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
			<!-- wp:group {"tagName":"article","className":"ds-card","layout":{"type":"default"}} -->
			<article class="wp-block-group ds-card">
				<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"dancingspoon-card"} /-->
				<!-- wp:group {"className":"ds-card__body","layout":{"type":"default"}} -->
				<div class="wp-block-group ds-card__body">
					<!-- wp:post-terms {"term":"category","className":"is-style-pill"} /-->
					<!-- wp:post-title {"level":3,"isLink":true} /-->
					<!-- wp:post-excerpt {"excerptLength":22} /-->
					<!-- wp:post-date {"format":"F j, Y","style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} /-->
				</div>
				<!-- /wp:group -->
			</article>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
		<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:query -->
</div>
<!-- /wp:group -->
