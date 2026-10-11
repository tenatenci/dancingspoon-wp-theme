<?php
/**
 * Title: Contact page
 * Slug: dancingspoon/contact
 * Categories: dancingspoon
 * Block Types: core/post-content
 * Description: Ways to reach Tena — e-mail, social, collaborations, and what to expect.
 */
$email   = get_theme_mod( 'dancingspoon_id_email', '' );
$mail    = $email ? 'mailto:' . $email : home_url( '/contact/' );
$biz     = trim( (string) get_theme_mod( 'dancingspoon_id_email_business', '' ) ) ?: $email;
$mail_biz = $biz ? 'mailto:' . $biz . '?subject=' . rawurlencode( __( 'Working with Dancing Spoon', 'dancingspoon' ) ) : $mail;
$address = trim( (string) get_theme_mod( 'dancingspoon_id_address', '' ) );
$owner   = trim( (string) get_theme_mod( 'dancingspoon_id_owner_name', '' ) );
?>
<!-- wp:group {"className":"ds-contact","layout":{"type":"constrained","contentSize":"980px"}} -->
<div class="wp-block-group ds-contact">
	<!-- wp:paragraph {"align":"center","className":"ds-contact__lede"} -->
	<p class="has-text-align-center ds-contact__lede"><?php esc_html_e( 'A question about a recipe, a photo of what you cooked, an idea for a collaboration — I read everything and answer most of it within a few days.', 'dancingspoon' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:columns {"className":"ds-contact__cards","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40","top":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-columns ds-contact__cards">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"ds-card ds-contact__card","backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group ds-card ds-contact__card has-surface-background-color has-background">
				<!-- wp:html -->
				<span class="ds-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg></span>
				<!-- /wp:html -->
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading"><?php esc_html_e( 'Write me', 'dancingspoon' ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'The best way for anything longer than a sentence: recipe questions, corrections, requests.', 'dancingspoon' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"ds-contact__action"} -->
				<p class="ds-contact__action"><a href="<?php echo esc_url( $mail ); ?>"><?php echo $email ? esc_html( $email ) : dancingspoon_identity( 'email' ); ?></a></p>
				<!-- /wp:paragraph -->
				<?php if ( $address ) : ?>
				<!-- wp:paragraph {"className":"ds-contact__post"} -->
				<p class="ds-contact__post"><span class="ds-contact__post-label"><?php esc_html_e( 'By post', 'dancingspoon' ); ?></span><?php echo $owner ? esc_html( $owner ) . '<br>' : ''; ?><?php echo nl2br( esc_html( $address ) ); ?></p>
				<!-- /wp:paragraph -->
				<?php endif; ?>
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"ds-card ds-contact__card","backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group ds-card ds-contact__card has-surface-background-color has-background">
				<!-- wp:html -->
				<span class="ds-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg></span>
				<!-- /wp:html -->
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading"><?php esc_html_e( 'Show me what you made', 'dancingspoon' ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Tag @dancing.1spoon — your plates make my day. Quick questions work well as a DM, too.', 'dancingspoon' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:social-links {"iconColor":"primary","iconColorValue":"#542a35","size":"has-normal-icon-size","className":"is-style-logos-only ds-social"} -->
				<ul class="wp-block-social-links has-normal-icon-size has-icon-color is-style-logos-only ds-social"><!-- wp:social-link {"url":"https://www.instagram.com/dancing.1spoon/","service":"instagram","label":"Instagram"} /--><!-- wp:social-link {"url":"https://www.tiktok.com/@dancing.1spoon","service":"tiktok","label":"TikTok"} /--><!-- wp:social-link {"url":"https://de.pinterest.com/tenatomasevic/","service":"pinterest","label":"Pinterest"} /--></ul>
				<!-- /wp:social-links -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"ds-card ds-contact__card","backgroundColor":"surface","layout":{"type":"default"}} -->
			<div class="wp-block-group ds-card ds-contact__card has-surface-background-color has-background">
				<!-- wp:html -->
				<span class="ds-contact__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19h16"/><path d="M6 19V9a6 6 0 0 1 12 0v10"/><path d="M9 12h6"/></svg></span>
				<!-- /wp:html -->
				<!-- wp:heading {"level":3} -->
				<h3 class="wp-block-heading"><?php esc_html_e( 'Work with me', 'dancingspoon' ); ?></h3>
				<!-- /wp:heading -->
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Recipe development, food photography and honest product features that fit this kitchen. Tell me what you have in mind and I will tell you if it fits.', 'dancingspoon' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph {"className":"ds-contact__action"} -->
				<p class="ds-contact__action"><a href="<?php echo esc_url( $mail_biz ); ?>"><?php esc_html_e( 'Start the conversation →', 'dancingspoon' ); ?></a></p>
				<!-- /wp:paragraph -->
				<?php if ( $biz ) : ?>
				<!-- wp:paragraph {"className":"ds-contact__meta"} -->
				<p class="ds-contact__meta"><?php echo esc_html( $biz ); ?></p>
				<!-- /wp:paragraph -->
				<?php endif; ?>
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"className":"ds-contact__faq","layout":{"type":"constrained","contentSize":"720px"}} -->
	<div class="wp-block-group ds-contact__faq">
		<!-- wp:heading {"textAlign":"center","level":2} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Before you write', 'dancingspoon' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:details {"summary":"Can I use your photos or recipes?"} -->
		<details class="wp-block-details"><summary><?php esc_html_e( 'Can I use your photos or recipes?', 'dancingspoon' ); ?></summary><!-- wp:paragraph --><p><?php esc_html_e( 'Cook them, share them, link to them — always. Reposting the photos or the full text needs a quick yes from me first.', 'dancingspoon' ); ?></p><!-- /wp:paragraph --></details>
		<!-- /wp:details -->
		<!-- wp:details {"summary":"My recipe turned out differently. What went wrong?"} -->
		<details class="wp-block-details"><summary><?php esc_html_e( 'My recipe turned out differently. What went wrong?', 'dancingspoon' ); ?></summary><!-- wp:paragraph --><p><?php esc_html_e( 'Leave a comment under the recipe with what you changed and what happened — that helps the next reader too, and I answer there.', 'dancingspoon' ); ?></p><!-- /wp:paragraph --></details>
		<!-- /wp:details -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
