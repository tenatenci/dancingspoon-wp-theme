<?php
/**
 * Title: About Tena
 * Slug: dancingspoon/about-teaser
 * Categories: dancingspoon
 * Description: A short introduction with a link to the About page.
 */
?>
<!-- wp:group {"align":"wide","className":"ds-card","backgroundColor":"surface","layout":{"type":"constrained","contentSize":"760px"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"},"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}}} -->
<div class="wp-block-group alignwide ds-card has-surface-background-color has-background" style="margin-top:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--40)">
	<!-- wp:paragraph {"align":"center","className":"ds-kicker"} -->
	<p class="has-text-align-center ds-kicker"><?php esc_html_e( 'Hi, I’m Tena', 'dancingspoon' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"textAlign":"center","level":2} -->
	<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Simple recipes that look impressive', 'dancingspoon' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center"} -->
	<p class="has-text-align-center"><?php esc_html_e( 'A self-taught cook and baker. Mostly Croatian and Balkan home cooking, with the French, Italian and German dishes that shaped it — every recipe tested until it works on a weeknight.', 'dancingspoon' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-outline-pill"} -->
		<div class="wp-block-button is-style-outline-pill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'More about me', 'dancingspoon' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
