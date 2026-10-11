<?php
/**
 * Title: Privacy policy (Datenschutzerklärung)
 * Slug: dancingspoon/privacy
 * Categories: dancingspoon
 * Block Types: core/post-content
 * Description: The minimum GDPR text for this site as it is actually built: self-hosted in Germany, cookieless analytics, comments, contact by e-mail, no ads, no Google Fonts, no Gravatar.
 */
$days      = (int) get_theme_mod( 'dancingspoon_id_log_days', 30 );
$authority = (string) get_theme_mod( 'dancingspoon_id_authority', '' );
?>
<!-- wp:group {"className":"ds-legal","layout":{"type":"constrained","contentSize":"720px"}} -->
<div class="wp-block-group ds-legal">
	<!-- wp:paragraph {"className":"ds-legal__lede"} -->
	<p class="ds-legal__lede"><?php esc_html_e( 'Short version: this site is hosted on my own server in Germany, sets no tracking cookies, shows no ads, loads no fonts or scripts from Google, and only processes personal data you give it yourself — when you comment or write to me. The details, as the GDPR requires them:', 'dancingspoon' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">1. <?php esc_html_e( 'Controller', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php echo dancingspoon_identity( 'owner_name' ); ?><br><?php echo dancingspoon_identity( 'address' ); ?><br><?php esc_html_e( 'E-mail:', 'dancingspoon' ); ?> <?php echo dancingspoon_identity( 'email' ); ?></p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">2. <?php esc_html_e( 'Hosting and server logs', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'The site runs on a server rented from Hetzner Online GmbH (Gunzenhausen, Germany) in their Nuremberg data centre and is operated by me. When you open a page, the web server records a log entry with your IP address, the time, the page requested, the referring page and your browser identification. These logs are needed to run the site securely and to diagnose faults (legal basis: Art. 6 (1) (f) GDPR, legitimate interest). They are kept for', 'dancingspoon' ); ?> <?php echo esc_html( $days ); ?> <?php esc_html_e( 'days and then deleted. Hetzner processes the data on my behalf under a data processing agreement.', 'dancingspoon' ); ?></p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">3. <?php esc_html_e( 'Cookies', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'The site itself sets no cookies. Two exceptions you trigger yourself: if you tick "Save my name, email, and website in this browser" under a comment form, WordPress stores those values in a cookie for one year so you need not retype them; and if you log in, WordPress sets session cookies. Neither is used for tracking, and no consent banner is therefore needed.', 'dancingspoon' ); ?></p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">4. <?php esc_html_e( 'Visitor statistics', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'To see which recipes are read, I use Rybbit, an open-source analytics tool that I host myself on the same infrastructure in Germany. It works without cookies and without a persistent identifier: your IP address and browser identification are hashed with a salt that changes every day, so visits cannot be linked across days or to a person, and no IP address is stored. What I see are aggregate numbers — page views, referrers, countries, device types (legal basis: Art. 6 (1) (f) GDPR). No data leaves my server.', 'dancingspoon' ); ?></p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">5. <?php esc_html_e( 'Comments and recipe ratings', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'When you comment or rate a recipe, the name and e-mail address you enter, the optional website, the comment text, your rating, the time and your IP address are stored with the comment. The IP address is kept to defend against spam and abuse (Art. 6 (1) (f) GDPR); everything else is published as you entered it, except the e-mail address, which stays private (Art. 6 (1) (a) GDPR, your consent, which you can withdraw by asking me to delete the comment). Spam filtering happens on my server; comments are not sent to third parties. Profile pictures from Gravatar are switched off, so your e-mail address is never transmitted to Automattic.', 'dancingspoon' ); ?></p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">6. <?php esc_html_e( 'Writing to me', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'If you e-mail me, I process your address and what you wrote in order to answer (Art. 6 (1) (b) and (f) GDPR). Messages are deleted once the matter is settled unless I have to keep them for legal reasons. Messages on Instagram, TikTok or Pinterest are processed by those platforms under their own privacy policies.', 'dancingspoon' ); ?></p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">7. <?php esc_html_e( 'Fonts, images, links to social networks', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'Fonts and images are delivered from my own server; nothing is loaded from Google Fonts or other content delivery networks. The Instagram, TikTok and Pinterest icons are plain links — the networks learn nothing about you until you click them. The "Pin" button on a recipe opens Pinterest in a new tab the same way. Should a post embed a video from a third-party platform, that platform receives your IP address when the page loads; I mention it in the post when that is the case.', 'dancingspoon' ); ?></p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">8. <?php esc_html_e( 'Your rights', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'Under the GDPR you may ask me at any time which personal data of yours I hold, have it corrected, deleted or restricted, receive it in a portable format, and object to processing based on legitimate interest. One e-mail to the address above is enough. You also have the right to complain to a data protection supervisory authority', 'dancingspoon' ); ?><?php if ( $authority ) : ?> (<?php echo esc_html( $authority ); ?>)<?php endif; ?>.</p><!-- /wp:paragraph -->

	<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">9. <?php esc_html_e( 'Changes', 'dancingspoon' ); ?></h2><!-- /wp:heading -->
	<!-- wp:paragraph --><p><?php esc_html_e( 'This policy describes the site as it is built today. If I add a newsletter, a shop or embedded content that changes what is processed, this page changes first.', 'dancingspoon' ); ?> <?php esc_html_e( 'Last updated:', 'dancingspoon' ); ?> <?php echo esc_html( get_the_modified_date( 'F Y' ) ?: wp_date( 'F Y' ) ); ?>.</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
