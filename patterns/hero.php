<?php
/**
 * Title: Hero — latest recipe
 * Slug: dancingspoon/hero
 * Categories: dancingspoon
 * Block Types: core/query
 * Description: The recipe of the month (or the newest / a random one) as a split hero: photo left, kicker, title, excerpt and a button right. With the Christmas layout on and recipes in the festive category, the card becomes a slider through them.
 */
$festive  = dancingspoon_festive_ids();
$slider   = count( $festive ) > 1;
$extra    = $slider ? ' ds-carousel ds-hero-slider' : '';   // scrolled by the reader only: no autoplay
?>
<!-- wp:query {"queryId":1,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false,"sticky":""},"align":"wide","className":"ds-hero-query<?php echo esc_attr( $extra ); ?>"} -->
<div class="wp-block-query alignwide ds-hero-query<?php echo esc_attr( $extra ); ?>">
	<!-- wp:post-template {"layout":{"type":"default"}<?php echo $slider ? ',"className":"ds-carousel__track"' : ''; ?>} -->
		<!-- wp:group {"className":"ds-hero ds-card","backgroundColor":"surface","layout":{"type":"default"}} -->
		<div class="wp-block-group ds-hero ds-card has-surface-background-color has-background">
			<!-- wp:columns {"verticalAlignment":"center","isStackedOnMobile":true,"style":{"spacing":{"blockGap":{"left":"0"}}}} -->
			<div class="wp-block-columns are-vertically-aligned-center">
				<!-- wp:column {"verticalAlignment":"center","width":"58%"} -->
				<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:58%">
					<?php // the slider serves the uncropped photo, shown whole at its own ratio; the single hero keeps the 16:10 crop ?>
					<!-- wp:post-featured-image {"isLink":true,"sizeSlug":"<?php echo $festive ? 'large' : 'dancingspoon-hero'; ?>","style":{"border":{"radius":"0"}}} /-->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"verticalAlignment":"center","width":"42%","className":"ds-hero__copy"} -->
				<div class="wp-block-column is-vertically-aligned-center ds-hero__copy" style="flex-basis:42%">
					<!-- wp:paragraph {"className":"ds-kicker"} -->
					<p class="ds-kicker"><?php echo esc_html( dancingspoon_hero_kicker() ); ?></p>
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
	<?php if ( $slider ) : ?>
	<!-- wp:html -->
	<div class="ds-hero-slider__nav" role="group" aria-label="<?php esc_attr_e( 'Scroll the festive recipes', 'dancingspoon' ); ?>">
		<button type="button" class="ds-carousel__btn ds-carousel__prev" aria-label="<?php esc_attr_e( 'Previous festive recipe', 'dancingspoon' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg></button>
		<div class="ds-carousel__dots" data-label="<?php esc_attr_e( 'Festive recipe', 'dancingspoon' ); ?>"></div>
		<button type="button" class="ds-carousel__btn ds-carousel__next" aria-label="<?php esc_attr_e( 'Next festive recipe', 'dancingspoon' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg></button>
	</div>
	<!-- /wp:html -->
	<?php endif; ?>
</div>
<!-- /wp:query -->
