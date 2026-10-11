<?php
/**
 * Title: Imprint (Impressum)
 * Slug: dancingspoon/imprint
 * Categories: dancingspoon
 * Block Types: core/post-content
 * Description: The information German law requires (§ 5 DDG, § 18 Abs. 2 MStV), filled from Customize → Dancing Spoon identity.
 */
?>
<!-- wp:group {"className":"ds-legal","layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group ds-legal">
	<!-- wp:heading {"level":2} -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Information according to § 5 DDG', 'dancingspoon' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php echo dancingspoon_identity( 'owner_name' ); ?><br><?php echo dancingspoon_identity( 'address' ); ?><br><?php esc_html_e( 'Germany', 'dancingspoon' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Contact', 'dancingspoon' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php esc_html_e( 'E-mail:', 'dancingspoon' ); ?> <?php echo dancingspoon_identity( 'email' ); ?><?php if ( get_theme_mod( 'dancingspoon_id_phone', '' ) ) : ?><br><?php esc_html_e( 'Phone:', 'dancingspoon' ); ?> <?php echo dancingspoon_identity( 'phone' ); ?><?php endif; ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Responsible for the content according to § 18 Abs. 2 MStV', 'dancingspoon' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php echo dancingspoon_identity( 'owner_name' ); ?><br><?php echo dancingspoon_identity( 'address' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Copyright', 'dancingspoon' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php esc_html_e( 'All recipes, texts and photographs on this site are my own work unless stated otherwise. You are welcome to cook, share and link; reproducing the photographs or the full texts requires my written permission.', 'dancingspoon' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"level":2} -->
	<h2 class="wp-block-heading"><?php esc_html_e( 'Liability for links', 'dancingspoon' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph -->
	<p><?php esc_html_e( 'This site links to external websites whose content I do not control. The respective provider or operator is responsible for the content of linked pages; at the time of linking no legal violations were apparent.', 'dancingspoon' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
