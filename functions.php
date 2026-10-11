<?php
/**
 * Dancing Spoon — block theme setup.
 *
 * @package dancingspoon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DANCINGSPOON_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Theme supports. A block theme gets most of this from theme.json; what is left is the
 * editor stylesheet, the card image size and the WP Recipe Maker hooks the templates rely on.
 */
function dancingspoon_setup(): void {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
	add_image_size( 'dancingspoon-card', 800, 600, true );
	add_image_size( 'dancingspoon-hero', 1600, 1000, true );
	load_theme_textdomain( 'dancingspoon', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'dancingspoon_setup' );

/**
 * Front-end stylesheet. Block themes do not enqueue style.css by themselves.
 */
function dancingspoon_styles(): void {
	wp_enqueue_style( 'dancingspoon', get_stylesheet_uri(), array(), DANCINGSPOON_VERSION );
}
add_action( 'wp_enqueue_scripts', 'dancingspoon_styles' );

/**
 * Carousel behaviour (front page only).
 */
function dancingspoon_scripts(): void {
	if ( is_page() ) {
		wp_enqueue_script( 'dancingspoon-contact', get_theme_file_uri( 'assets/js/contact.js' ), array(), wp_get_theme()->get( 'Version' ), true );
	}
	if ( is_home() || is_front_page() ) {
		wp_enqueue_script( 'dancingspoon-carousel', get_theme_file_uri( 'assets/js/carousel.js' ), array(), DANCINGSPOON_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
}
add_action( 'wp_enqueue_scripts', 'dancingspoon_scripts' );

/**
 * Pattern category so the home sections are findable in the inserter.
 */
function dancingspoon_patterns(): void {
	register_block_pattern_category( 'dancingspoon', array( 'label' => __( 'Dancing Spoon', 'dancingspoon' ) ) );
}
add_action( 'init', 'dancingspoon_patterns' );

/**
 * Block styles used by the templates.
 */
function dancingspoon_block_styles(): void {
	register_block_style( 'core/group', array( 'name' => 'card', 'label' => __( 'Card', 'dancingspoon' ) ) );
	register_block_style( 'core/button', array( 'name' => 'outline-pill', 'label' => __( 'Outline pill', 'dancingspoon' ) ) );
	register_block_style( 'core/post-terms', array( 'name' => 'pill', 'label' => __( 'Pill', 'dancingspoon' ) ) );
	register_block_style( 'core/separator', array( 'name' => 'spoon', 'label' => __( 'Spoon', 'dancingspoon' ) ) );
}
add_action( 'init', 'dancingspoon_block_styles' );

/**
 * Card excerpts: short, no "[…]".
 */
add_filter( 'excerpt_length', fn() => 22, 999 );
add_filter( 'excerpt_more', fn() => '…' );

/**
 * Image sizes list for the editor.
 */
add_filter(
	'image_size_names_choose',
	fn( array $sizes ) => array_merge( $sizes, array( 'dancingspoon-card' => __( 'Recipe card (4:3)', 'dancingspoon' ) ) )
);

/**
 * WP Recipe Maker: let the theme own the card typography (the plugin's template fonts
 * are overridden in style.css) and keep the jump-to-recipe button in the theme's voice.
 */
add_filter( 'wprm_recipe_jump_text', fn() => __( 'Jump to recipe', 'dancingspoon' ) );

/**
 * Front page options (Appearance → Customize → Dancing Spoon front page).
 * The hero is query 1 and the recipe grid query 2 in patterns/hero.php and patterns/latest-grid.php.
 */
function dancingspoon_customize( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'dancingspoon_front',
		array( 'title' => __( 'Dancing Spoon front page', 'dancingspoon' ), 'priority' => 30 )
	);
	$mode = fn( $v ) => in_array( $v, array( 'latest', 'random' ), true ) ? $v : 'latest';
	$hero_mode = fn( $v ) => in_array( $v, array( 'month', 'latest', 'random' ), true ) ? $v : 'month';
	$wp_customize->add_setting( 'dancingspoon_hero_mode', array( 'default' => 'month', 'sanitize_callback' => $hero_mode ) );
	$wp_customize->add_control(
		'dancingspoon_hero_mode',
		array(
			'section'     => 'dancingspoon_front',
			'label'       => __( 'Top card shows', 'dancingspoon' ),
			'description' => __( 'Recipe of the month falls back to the newest recipe until one is chosen below.', 'dancingspoon' ),
			'type'        => 'select',
			'choices'     => array(
				'month'  => __( 'Recipe of the month (chosen below)', 'dancingspoon' ),
				'latest' => __( 'The newest recipe', 'dancingspoon' ),
				'random' => __( 'A random recipe', 'dancingspoon' ),
			),
		)
	);
	$choices = array( 0 => __( '— none chosen —', 'dancingspoon' ) );
	foreach ( get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 200, 'orderby' => 'title', 'order' => 'ASC' ) ) as $post_item ) {
		$choices[ $post_item->ID ] = $post_item->post_title;
	}
	$wp_customize->add_setting( 'dancingspoon_hero_post', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		'dancingspoon_hero_post',
		array(
			'section' => 'dancingspoon_front',
			'label'   => __( 'Recipe of the month', 'dancingspoon' ),
			'type'    => 'select',
			'choices' => $choices,
		)
	);
	$wp_customize->add_setting( 'dancingspoon_grid_mode', array( 'default' => 'latest', 'sanitize_callback' => $mode ) );
	$wp_customize->add_control(
		'dancingspoon_grid_mode',
		array(
			'section' => 'dancingspoon_front',
			'label'   => __( 'Recipe grid shows', 'dancingspoon' ),
			'type'    => 'select',
			'choices' => array( 'latest' => __( 'The latest recipes', 'dancingspoon' ), 'random' => __( 'Random recipes', 'dancingspoon' ) ),
		)
	);
	$wp_customize->add_setting(
		'dancingspoon_grid_count',
		array( 'default' => 6, 'sanitize_callback' => fn( $v ) => in_array( (int) $v, array( 3, 6, 9 ), true ) ? (int) $v : 6 )
	);
	$wp_customize->add_control(
		'dancingspoon_grid_count',
		array(
			'section' => 'dancingspoon_front',
			'label'   => __( 'Recipes in the grid', 'dancingspoon' ),
			'type'    => 'select',
			'choices' => array( 3 => '3', 6 => '6', 9 => '9' ),
		)
	);
	$wp_customize->add_setting( 'dancingspoon_carousel_mode', array( 'default' => 'latest', 'sanitize_callback' => $mode ) );
	$wp_customize->add_control(
		'dancingspoon_carousel_mode',
		array(
			'section' => 'dancingspoon_front',
			'label'   => __( 'Carousel shows', 'dancingspoon' ),
			'type'    => 'select',
			'choices' => array( 'latest' => __( 'The latest recipes', 'dancingspoon' ), 'random' => __( 'Random recipes', 'dancingspoon' ) ),
		)
	);
	$wp_customize->add_setting(
		'dancingspoon_carousel_count',
		array( 'default' => 6, 'sanitize_callback' => fn( $v ) => in_array( (int) $v, array( 6, 9, 12 ), true ) ? (int) $v : 6 )
	);
	$wp_customize->add_control(
		'dancingspoon_carousel_count',
		array(
			'section' => 'dancingspoon_front',
			'label'   => __( 'Recipes in the carousel', 'dancingspoon' ),
			'type'    => 'select',
			'choices' => array( 6 => '6', 9 => '9', 12 => '12' ),
		)
	);
	$wp_customize->add_setting( 'dancingspoon_carousel_autoplay', array( 'default' => true, 'sanitize_callback' => fn( $v ) => (bool) $v ) );
	$wp_customize->add_control(
		'dancingspoon_carousel_autoplay',
		array( 'section' => 'dancingspoon_front', 'label' => __( 'Carousel scrolls by itself (every 5 s)', 'dancingspoon' ), 'type' => 'checkbox' )
	);
}
add_action( 'customize_register', 'dancingspoon_customize' );

/**
 * Apply the options to the two front-page queries. The grid never repeats the hero's post.
 */
function dancingspoon_front_queries( array $query, WP_Block $block ): array {
	static $hero_id = 0;
	static $carousel_ids = array();
	$query_id = (int) ( $block->context['queryId'] ?? 0 );
	if ( ! in_array( $query_id, array( 1, 2, 8 ), true ) || ! ( is_home() || is_front_page() ) ) {
		return $query;
	}
	$hero_mode  = get_theme_mod( 'dancingspoon_hero_mode', 'month' );
	$hero_post  = (int) get_theme_mod( 'dancingspoon_hero_post', 0 );
	if ( 'month' === $hero_mode && ( ! $hero_post || 'publish' !== get_post_status( $hero_post ) ) ) {
		$hero_mode = 'latest';   // nothing chosen (or it was unpublished): the newest recipe stands in
	}
	$grid_mode  = get_theme_mod( 'dancingspoon_grid_mode', 'latest' );
	$grid_count = (int) get_theme_mod( 'dancingspoon_grid_count', 6 );

	if ( 1 === $query_id ) {
		$pick = 'month' === $hero_mode ? array( $hero_post ) : get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'orderby'        => 'random' === $hero_mode ? 'rand' : 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		$hero_id = (int) ( $pick[0] ?? 0 );
		if ( $hero_id ) {
			$query['post__in'] = array( $hero_id );
			$query['offset']   = 0;
			$query['orderby']  = 'post__in';
		}
		return $query;
	}

	if ( 8 === $query_id ) {
		// the carousel: pick its posts now so the grid below can leave them out
		$carousel_ids = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => (int) get_theme_mod( 'dancingspoon_carousel_count', 6 ),
				'post__not_in'   => $hero_id ? array( $hero_id ) : array(),
				'orderby'        => 'random' === get_theme_mod( 'dancingspoon_carousel_mode', 'latest' ) ? 'rand' : 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		$query['offset'] = 0;
		if ( $carousel_ids ) {
			$query['post__in']       = $carousel_ids;
			$query['orderby']        = 'post__in';
			$query['posts_per_page'] = count( $carousel_ids );
			unset( $query['order'] );
		}
		return $query;
	}

	// query 2: the grid — never the hero, never what the carousel already shows
	$query['posts_per_page'] = $grid_count;
	$query['offset']         = 0;
	$shown                   = array_filter( array_merge( array( $hero_id ), $carousel_ids ) );
	if ( $shown ) {
		$query['post__not_in'] = array_values( $shown );
	}
	if ( 'random' === $grid_mode ) {
		$query['orderby'] = 'rand';
		unset( $query['order'] );
	} else {
		$query['orderby'] = 'date';
		$query['order']   = 'DESC';
	}
	return $query;
}
add_filter( 'query_loop_block_query_vars', 'dancingspoon_front_queries', 10, 2 );

/**
 * The hero kicker, in step with the Customizer choice.
 */
function dancingspoon_hero_kicker(): string {
	$mode = get_theme_mod( 'dancingspoon_hero_mode', 'month' );
	$post = (int) get_theme_mod( 'dancingspoon_hero_post', 0 );
	if ( 'month' === $mode && $post && 'publish' === get_post_status( $post ) ) {
		return __( 'Recipe of the month', 'dancingspoon' );
	}
	return 'random' === $mode ? __( 'Try this one', 'dancingspoon' ) : __( 'Fresh from the kitchen', 'dancingspoon' );
}

/**
 * Analytics and extra head/footer code (Appearance → Customize → Dancing Spoon analytics).
 * Rybbit is the house analytics (analytics.utrlabs.com); the raw boxes take anything else
 * that has to be pasted (cookie banner, verification tags). Admin-only settings, output verbatim.
 */
function dancingspoon_customize_analytics( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'dancingspoon_analytics',
		array( 'title' => __( 'Dancing Spoon analytics', 'dancingspoon' ), 'priority' => 31 )
	);
	$wp_customize->add_setting( 'dancingspoon_rybbit_script', array( 'default' => 'https://analytics.utrlabs.com/api/script.js', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		'dancingspoon_rybbit_script',
		array( 'section' => 'dancingspoon_analytics', 'label' => __( 'Rybbit script URL', 'dancingspoon' ), 'type' => 'url' )
	);
	$wp_customize->add_setting( 'dancingspoon_rybbit_site_id', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'dancingspoon_rybbit_site_id',
		array(
			'section'     => 'dancingspoon_analytics',
			'label'       => __( 'Rybbit site ID', 'dancingspoon' ),
			'description' => __( 'From the site\'s tracking snippet in Rybbit (data-site-id). Empty = no tracking.', 'dancingspoon' ),
			'type'        => 'text',
		)
	);
	$wp_customize->add_setting( 'dancingspoon_rybbit_skip_logged_in', array( 'default' => true, 'sanitize_callback' => fn( $v ) => (bool) $v ) );
	$wp_customize->add_control(
		'dancingspoon_rybbit_skip_logged_in',
		array( 'section' => 'dancingspoon_analytics', 'label' => __( 'Do not track logged-in users', 'dancingspoon' ), 'type' => 'checkbox' )
	);
	$raw = fn( $v ) => current_user_can( 'unfiltered_html' ) ? (string) $v : '';
	$wp_customize->add_setting( 'dancingspoon_head_code', array( 'default' => '', 'sanitize_callback' => $raw ) );
	$wp_customize->add_control(
		'dancingspoon_head_code',
		array(
			'section'     => 'dancingspoon_analytics',
			'label'       => __( 'Extra code in <head>', 'dancingspoon' ),
			'description' => __( 'Pasted verbatim before </head>: cookie banner, site verification, other analytics.', 'dancingspoon' ),
			'type'        => 'textarea',
		)
	);
	$wp_customize->add_setting( 'dancingspoon_footer_code', array( 'default' => '', 'sanitize_callback' => $raw ) );
	$wp_customize->add_control(
		'dancingspoon_footer_code',
		array( 'section' => 'dancingspoon_analytics', 'label' => __( 'Extra code before </body>', 'dancingspoon' ), 'type' => 'textarea' )
	);
}
add_action( 'customize_register', 'dancingspoon_customize_analytics' );

function dancingspoon_head_code(): void {
	$site_id = trim( (string) get_theme_mod( 'dancingspoon_rybbit_site_id', '' ) );
	$script  = (string) get_theme_mod( 'dancingspoon_rybbit_script', 'https://analytics.utrlabs.com/api/script.js' );
	$skip    = get_theme_mod( 'dancingspoon_rybbit_skip_logged_in', true ) && is_user_logged_in();
	if ( '' !== $site_id && '' !== $script && ! $skip && ! is_customize_preview() ) {
		printf( "<script src=\"%s\" data-site-id=\"%s\" defer></script>\n", esc_url( $script ), esc_attr( $site_id ) );
	}
	$extra = (string) get_theme_mod( 'dancingspoon_head_code', '' );
	if ( '' !== trim( $extra ) ) {
		echo $extra . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-authored snippet, by design
	}
}
add_action( 'wp_head', 'dancingspoon_head_code', 20 );

function dancingspoon_footer_code(): void {
	$extra = (string) get_theme_mod( 'dancingspoon_footer_code', '' );
	if ( '' !== trim( $extra ) ) {
		echo $extra . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-authored snippet, by design
	}
}
add_action( 'wp_footer', 'dancingspoon_footer_code', 50 );

/**
 * Site identity for the legal pages and the contact page (Appearance → Customize → Dancing Spoon identity).
 */
function dancingspoon_customize_identity( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'dancingspoon_identity',
		array( 'title' => __( 'Dancing Spoon identity', 'dancingspoon' ), 'priority' => 32, 'description' => __( 'Used by the Imprint, the privacy policy and the contact page. German law (§ 5 DDG, § 18 MStV) needs a real name and a postal address.', 'dancingspoon' ) )
	);
	$fields = array(
		'owner_name'  => array( __( 'Responsible person (full name)', 'dancingspoon' ), 'text' ),
		'address'     => array( __( 'Postal address (street, postcode, city)', 'dancingspoon' ), 'textarea' ),
		'email'       => array( __( 'Contact e-mail', 'dancingspoon' ), 'email' ),
		'email_business' => array( __( 'Business e-mail for collaborations (empty = contact e-mail)', 'dancingspoon' ), 'email' ),
		'phone'       => array( __( 'Phone (optional)', 'dancingspoon' ), 'text' ),
		'authority'   => array( __( 'Data protection authority for your state (name + URL, optional)', 'dancingspoon' ), 'text' ),
		'log_days'    => array( __( 'Server log retention in days', 'dancingspoon' ), 'number' ),
	);
	foreach ( $fields as $key => $def ) {
		list( $label, $type ) = $def;
		$wp_customize->add_setting( 'dancingspoon_id_' . $key, array( 'default' => 'log_days' === $key ? 30 : '', 'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : ( 'number' === $type ? 'absint' : 'sanitize_text_field' ) ) );
		$wp_customize->add_control( 'dancingspoon_id_' . $key, array( 'section' => 'dancingspoon_identity', 'label' => $label, 'type' => $type ) );
	}
}
add_action( 'customize_register', 'dancingspoon_customize_identity' );

/**
 * One identity value, or a visible placeholder so an unfilled legal page is never silently wrong.
 */
function dancingspoon_identity( string $key, bool $html = true ): string {
	$value = (string) get_theme_mod( 'dancingspoon_id_' . $key, '' );
	if ( '' === trim( $value ) ) {
		$labels = array(
			'owner_name' => __( 'full name', 'dancingspoon' ),
			'address'    => __( 'street, postcode, city', 'dancingspoon' ),
			'email'      => __( 'e-mail address', 'dancingspoon' ),
			'phone'      => __( 'phone', 'dancingspoon' ),
			'authority'  => __( 'data protection authority', 'dancingspoon' ),
			'log_days'   => '30',
		);
		$value = '[' . ( $labels[ $key ] ?? $key ) . ' — ' . __( 'set in Customize → Dancing Spoon identity', 'dancingspoon' ) . ']';
		return $html ? '<mark class="ds-todo">' . esc_html( $value ) . '</mark>' : $value;
	}
	return $html ? nl2br( esc_html( $value ) ) : $value;
}

/**
 * Outgoing mail. With DS_SMTP_HOST and DS_SMTP_PASS present (container env, a *_FILE secret mount, or
 * constants in wp-config.php) every wp_mail() — comment notifications, password resets — goes through that
 * mailbox over authenticated SMTP. Without them WordPress keeps PHP mail(), which the zone's SPF does not cover.
 */
function dancingspoon_smtp( string $key, string $default = '' ): string {
	$name = 'DS_SMTP_' . $key;
	if ( defined( $name ) ) {
		return (string) constant( $name );
	}
	$value = getenv( $name );
	if ( false !== $value && '' !== $value ) {
		return (string) $value;
	}
	$file = getenv( $name . '_FILE' );
	if ( $file && is_readable( $file ) ) {
		return trim( (string) file_get_contents( $file ) );
	}
	return $default;
}

function dancingspoon_smtp_active(): bool {
	return '' !== dancingspoon_smtp( 'HOST' ) && '' !== dancingspoon_smtp( 'PASS' );
}

function dancingspoon_phpmailer_init( $phpmailer ): void {
	if ( ! dancingspoon_smtp_active() ) {
		return;
	}
	$port = (int) dancingspoon_smtp( 'PORT', '587' );
	$phpmailer->isSMTP();
	$phpmailer->Host       = dancingspoon_smtp( 'HOST' );
	$phpmailer->Port       = $port;
	$phpmailer->SMTPAuth   = true;
	$phpmailer->Username   = dancingspoon_smtp( 'USER' );
	$phpmailer->Password   = dancingspoon_smtp( 'PASS' );
	$phpmailer->SMTPSecure = 465 === $port ? 'ssl' : 'tls';
	// The envelope sender must be the authenticated mailbox, so From defaults to the login.
	$phpmailer->setFrom( dancingspoon_smtp( 'FROM', $phpmailer->Username ), dancingspoon_smtp( 'FROM_NAME', get_bloginfo( 'name' ) ) );
	$reply = dancingspoon_smtp( 'REPLY_TO', (string) get_theme_mod( 'dancingspoon_id_email', '' ) );
	if ( '' !== $reply && empty( $phpmailer->getReplyToAddresses() ) ) {
		$phpmailer->addReplyTo( $reply );
	}
}
add_action( 'phpmailer_init', 'dancingspoon_phpmailer_init' );

function dancingspoon_mail_notice(): void {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'dashboard' !== $screen->id || ! current_user_can( 'manage_options' ) || dancingspoon_smtp_active() ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' . esc_html__( 'Dancing Spoon: outgoing mail (comment notifications, password resets) still uses PHP mail(), which the domain’s SPF record does not authorise. Set DS_SMTP_HOST, DS_SMTP_USER and DS_SMTP_PASS on the container to send through the Hetzner mailbox.', 'dancingspoon' ) . '</p></div>';
}
add_action( 'admin_notices', 'dancingspoon_mail_notice' );

/**
 * Most older posts open with the very photo that is also the featured image; the single template already shows
 * it wide above the text, so that leading copy is dropped. Runs at priority 5, i.e. on raw block markup: leading
 * spacers and empty paragraphs are skipped, the image is matched by attachment id or, failing that, by file name.
 */
function dancingspoon_drop_duplicate_hero( string $content ): string {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$thumb_id = (int) get_post_thumbnail_id();
	if ( ! $thumb_id ) {
		return $content;
	}
	$lead  = '(?:\s*<!-- wp:spacer\b[^>]*-->.*?<!-- /wp:spacer -->|\s*<!-- wp:paragraph\b[^>]*-->\s*<p[^>]*>\s*</p>\s*<!-- /wp:paragraph -->|\s*<p[^>]*>\s*(?:&nbsp;)?\s*</p>)*\s*';
	$image = '(<!-- wp:image\b[^>]*-->\s*<figure\b.*?</figure>\s*<!-- /wp:image -->|<figure\b[^>]*class="[^"]*wp-block-image[^"]*"[^>]*>.*?</figure>|<p[^>]*>\s*<img\b[^>]+>\s*</p>|<img\b[^>]+>)';
	if ( ! preg_match( '#^(' . $lead . ')' . $image . '#is', $content, $m ) ) {
		return $content;
	}
	$block = $m[2];
	$same  = (bool) preg_match( '#(?:"id":' . $thumb_id . '\b|wp-image-' . $thumb_id . '\b)#', $block );
	if ( ! $same && preg_match( '#src="([^"]+)"#i', $block, $src ) ) {
		$strip = static function ( string $url ): string {
			$base = strtolower( pathinfo( (string) wp_parse_url( $url, PHP_URL_PATH ), PATHINFO_FILENAME ) );
			return (string) preg_replace( '#(-\d+x\d+|-scaled)$#', '', $base );
		};
		$same = $strip( $src[1] ) === $strip( (string) wp_get_attachment_url( $thumb_id ) );
	}
	return $same ? $m[1] . substr( $content, strlen( $m[0] ) ) : $content;
}
add_filter( 'the_content', 'dancingspoon_drop_duplicate_hero', 5 );
