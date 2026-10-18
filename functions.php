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
	if ( is_singular( 'post' ) ) {
		wp_enqueue_script( 'dancingspoon-share', get_theme_file_uri( 'assets/js/share.js' ), array(), wp_get_theme()->get( 'Version' ), true );
	}
	if ( is_page() ) {
		wp_enqueue_script( 'dancingspoon-contact', get_theme_file_uri( 'assets/js/contact.js' ), array(), wp_get_theme()->get( 'Version' ), true );
	}
	if ( is_home() || is_front_page() ) {
		wp_enqueue_script( 'dancingspoon-carousel', get_theme_file_uri( 'assets/js/carousel.js' ), array(), DANCINGSPOON_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
	if ( dancingspoon_xmas() && get_theme_mod( 'dancingspoon_xmas_snow', true ) ) {
		wp_enqueue_script( 'dancingspoon-snow', get_theme_file_uri( 'assets/js/snow.js' ), array(), DANCINGSPOON_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
}
add_action( 'wp_enqueue_scripts', 'dancingspoon_scripts' );

/**
 * The footer mark spins (SMIL in parts/footer.html); hold it still for reduced motion.
 */
function dancingspoon_logo_motion(): void {
	wp_print_inline_script_tag( "if(matchMedia('(prefers-reduced-motion: reduce)').matches){document.querySelectorAll('.ds-brand--footer .ds-brand__mark svg').forEach(function(s){s.pauseAnimations();s.setCurrentTime(0);});}" );
}
add_action( 'wp_footer', 'dancingspoon_logo_motion' );

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
	static $hero_ids = array();
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
		$festive = dancingspoon_festive_ids();
		if ( $festive ) {
			// the Christmas layout with tagged recipes: the top card slides through all of them, newest first
			$hero_ids                = $festive;
			$query['post__in']       = $festive;
			$query['orderby']        = 'post__in';
			$query['posts_per_page'] = count( $festive );
			$query['offset']         = 0;
			unset( $query['order'] );
			return $query;
		}
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
		$hero_ids = array_filter( array( (int) ( $pick[0] ?? 0 ) ) );
		if ( $hero_ids ) {
			$query['post__in'] = $hero_ids;
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
				'post__not_in'   => $hero_ids,
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
	$shown                   = array_filter( array_merge( $hero_ids, $carousel_ids ) );
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
	if ( dancingspoon_festive_ids() ) {
		$festive = trim( (string) get_theme_mod( 'dancingspoon_xmas_kicker', dancingspoon_xmas_defaults()['kicker'] ) );
		if ( '' !== $festive ) {
			return $festive;
		}
	}
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


/**
 * [ds_share] — share buttons for the current post (no plugin, no counters, no third-party script).
 */
function dancingspoon_share_shortcode(): string {
	if ( ! is_singular() ) {
		return '';
	}
	$url   = get_permalink();
	$title = get_the_title();
	$image = (string) get_the_post_thumbnail_url( null, 'large' );
	$u     = rawurlencode( $url );
	$t     = rawurlencode( $title );
	$icons = array(
		'facebook'  => '<path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z"/>',
		'x'         => '<path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>',
		'pinterest' => '<path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/>',
		'whatsapp'  => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>',
		'email'     => '<path d="M3 5h18v14H3z" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3 6l9 7 9-7" fill="none" stroke="currentColor" stroke-width="1.8"/>',
		'link'      => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
	);
	$links = array(
		'facebook'  => array( 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . $u ),
		'x'         => array( 'X', 'https://twitter.com/intent/tweet?url=' . $u . '&text=' . $t ),
		'pinterest' => array( 'Pinterest', 'https://pinterest.com/pin/create/button/?url=' . $u . '&media=' . rawurlencode( $image ) . '&description=' . $t ),
		'whatsapp'  => array( 'WhatsApp', 'https://api.whatsapp.com/send?text=' . $t . '%20' . $u ),
		'email'     => array( 'E-mail', 'mailto:?subject=' . $t . '&body=' . $u ),
	);
	$out = '<div class="ds-share"><span class="ds-share__label">' . esc_html__( 'Share', 'dancingspoon' ) . '</span><ul class="ds-share__list">';
	foreach ( $links as $key => $def ) {
		list( $label, $href ) = $def;
		/* translators: %s: network name */
		$aria = sprintf( __( 'Share on %s', 'dancingspoon' ), $label );
		$out .= '<li><a class="ds-share__btn ds-share__btn--' . $key . '" href="' . esc_url( $href ) . '" target="_blank" rel="noopener nofollow" aria-label="' . esc_attr( $aria ) . '" title="' . esc_attr( $label ) . '" data-pin-custom="true" data-pin-do="none"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">' . $icons[ $key ] . '</svg></a></li>';
	}
	$out .= '<li><button type="button" class="ds-share__btn ds-share__copy" data-url="' . esc_url( $url ) . '" data-copied="' . esc_attr__( 'Link copied', 'dancingspoon' ) . '" aria-label="' . esc_attr__( 'Copy link', 'dancingspoon' ) . '" title="' . esc_attr__( 'Copy link', 'dancingspoon' ) . '"><svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">' . $icons['link'] . '</svg></button></li></ul></div>';
	return $out;
}
add_shortcode( 'ds_share', 'dancingspoon_share_shortcode' );

/**
 * The share row sits right above the recipe card; posts without a card get it at the end of the text.
 */
function dancingspoon_share_placement( string $content ): string {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() || false !== strpos( $content, 'class="ds-share"' ) ) {
		return $content;
	}
	$row = '<div class="ds-single-share">' . dancingspoon_share_shortcode() . '</div>';
	$pos = preg_match( '#<div[^>]*\bwprm-recipe-container\b#i', $content, $m, PREG_OFFSET_CAPTURE ) ? (int) $m[0][1] : false;
	return false === $pos ? $content . $row : substr( $content, 0, $pos ) . $row . substr( $content, $pos );
}
add_filter( 'the_content', 'dancingspoon_share_placement', 20 );


/**
 * Favicons from the theme (assets/favicon/, built from assets/images/logo.svg): WordPress's own Site Icon
 * output is removed so the icon follows the theme release on every site instead of a per-site upload.
 */
function dancingspoon_favicons(): void {
	$base = get_theme_file_uri( 'assets/favicon/' );
	$v    = '?v=' . rawurlencode( (string) wp_get_theme()->get( 'Version' ) );
	echo '<link rel="icon" href="' . esc_url( $base . 'favicon.ico' . $v ) . '" sizes="32x32">' . "\n";
	echo '<link rel="icon" href="' . esc_url( $base . 'icon.svg' . $v ) . '" type="image/svg+xml">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $base . 'apple-touch-icon.png' . $v ) . '">' . "\n";
	echo '<link rel="manifest" href="' . esc_url( $base . 'site.webmanifest' . $v ) . '">' . "\n";
	echo '<meta name="theme-color" content="#f8f5ef">' . "\n";
}
foreach ( array( 'wp_head', 'login_head', 'admin_head' ) as $hook ) {
	remove_action( $hook, 'wp_site_icon', 99 );
	add_action( $hook, 'dancingspoon_favicons', 99 );
}
add_filter( 'get_site_icon_url', fn() => get_theme_file_uri( 'assets/favicon/icon-512.png' ) );

/**
 * The categories the site shows as sections: top level, with at least one post, never the default
 * "Uncategorized". Same rule as the footer's Categories block, so header, footer and the home row agree.
 *
 * @return WP_Term[]
 */
function dancingspoon_categories( string $orderby = 'name' ): array {
	$terms = get_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
			'parent'     => 0,
			'orderby'    => $orderby,
			'order'      => 'count' === $orderby ? 'DESC' : 'ASC',
			'exclude'    => array( (int) get_option( 'default_category' ) ),
		)
	);
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Header menu: the "Categories" placeholder link in parts/header.html (class ds-nav-categories) becomes one link
 * per category, so a new category shows up in the menu as soon as it has a recipe. Past five, all but four go into
 * a "More" dropdown so the one-line desktop menu keeps fitting.
 */
function dancingspoon_nav_categories( array $parsed_block ): array {
	if ( 'core/navigation' !== ( $parsed_block['blockName'] ?? '' ) || empty( $parsed_block['innerBlocks'] ) ) {
		return $parsed_block;
	}
	$is_slot = static fn( array $b ): bool => in_array( 'ds-nav-categories', explode( ' ', (string) ( $b['attrs']['className'] ?? '' ) ), true );
	if ( ! array_filter( $parsed_block['innerBlocks'], $is_slot ) ) {
		return $parsed_block;
	}
	$link  = static fn( WP_Term $t ): array => array(
		'blockName'    => 'core/navigation-link',
		'attrs'        => array( 'label' => $t->name, 'url' => get_category_link( $t ), 'type' => 'category', 'kind' => 'taxonomy', 'id' => $t->term_id ),
		'innerBlocks'  => array(),
		'innerHTML'    => '',
		'innerContent' => array(),
	);
	$links = array_map( $link, dancingspoon_categories() );
	$top   = 4;
	if ( count( $links ) > $top + 1 ) {
		$rest  = array_slice( $links, $top );
		$links = array_slice( $links, 0, $top );
		$links[] = array(
			'blockName'    => 'core/navigation-submenu',
			'attrs'        => array( 'label' => __( 'More', 'dancingspoon' ), 'url' => home_url( '/recipes/' ), 'className' => 'ds-nav-more' ),
			'innerBlocks'  => $rest,
			'innerHTML'    => '',
			'innerContent' => array_fill( 0, count( $rest ), null ),
		);
	}
	$inner = array();
	foreach ( $parsed_block['innerBlocks'] as $child ) {
		array_push( $inner, ...( $is_slot( $child ) ? $links : array( $child ) ) );
	}
	$parsed_block['innerBlocks']  = $inner;
	$parsed_block['innerContent'] = array_fill( 0, count( $inner ), null );
	return $parsed_block;
}
add_filter( 'render_block_data', 'dancingspoon_nav_categories' );

/**
 * Christmas layout (Appearance → Customize → Dancing Spoon seasonal). One switch, on by default, turns the whole
 * look on: body class ds-xmas drives the CSS (Santa hat on the mark, snow caps on the hero and the evergreen
 * footer, red kickers), a greeting bar sits above the header,
 * assets/js/snow.js draws the snow, and the recipes in the festive category turn the top card into a slider
 * ("Festive recipes", patterns/hero.php). Off = the ordinary cream-and-plum site, nothing else changes.
 */
function dancingspoon_xmas(): bool {
	return (bool) get_theme_mod( 'dancingspoon_xmas', true );
}

function dancingspoon_xmas_defaults(): array {
	return array(
		'greeting' => __( 'Merry Christmas from the Dancing Spoon kitchen', 'dancingspoon' ),
		'kicker'   => __( 'Festive recipes', 'dancingspoon' ),
		'category' => 'festive-recipes',
	);
}

function dancingspoon_customize_seasonal( WP_Customize_Manager $wp_customize ): void {
	$d = dancingspoon_xmas_defaults();
	$wp_customize->add_section(
		'dancingspoon_seasonal',
		array( 'title' => __( 'Dancing Spoon seasonal', 'dancingspoon' ), 'priority' => 33, 'description' => __( 'The Christmas look: the first switch turns all of it on or off, the rest tunes the details.', 'dancingspoon' ) )
	);
	$bool = fn( $v ) => (bool) $v;
	$wp_customize->add_setting( 'dancingspoon_xmas', array( 'default' => true, 'sanitize_callback' => $bool ) );
	$wp_customize->add_control(
		'dancingspoon_xmas',
		array(
			'section'     => 'dancingspoon_seasonal',
			'label'       => __( 'Christmas layout', 'dancingspoon' ),
			'description' => __( 'Santa hat on the logo, snow caps on the hero and an evergreen footer, the greeting bar, the festive slider.', 'dancingspoon' ),
			'type'        => 'checkbox',
		)
	);
	$wp_customize->add_setting( 'dancingspoon_xmas_snow', array( 'default' => true, 'sanitize_callback' => $bool ) );
	$wp_customize->add_control(
		'dancingspoon_xmas_snow',
		array(
			'section'     => 'dancingspoon_seasonal',
			'label'       => __( 'Falling snow', 'dancingspoon' ),
			'description' => __( 'Light, behind the header; held still for visitors who prefer reduced motion.', 'dancingspoon' ),
			'type'        => 'checkbox',
		)
	);
	$wp_customize->add_setting( 'dancingspoon_xmas_greeting', array( 'default' => $d['greeting'], 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'dancingspoon_xmas_greeting',
		array( 'section' => 'dancingspoon_seasonal', 'label' => __( 'Greeting bar above the header', 'dancingspoon' ), 'description' => __( 'Empty = no bar.', 'dancingspoon' ), 'type' => 'text' )
	);
	$choices = array( 0 => __( '— a category named "Festive recipes", when it exists —', 'dancingspoon' ) );
	$terms   = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'exclude' => array( (int) get_option( 'default_category' ) ) ) );
	foreach ( is_wp_error( $terms ) ? array() : $terms as $term ) {
		$choices[ $term->term_id ] = $term->name;
	}
	$wp_customize->add_setting( 'dancingspoon_xmas_category', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		'dancingspoon_xmas_category',
		array(
			'section'     => 'dancingspoon_seasonal',
			'label'       => __( 'The festive recipes category', 'dancingspoon' ),
			'description' => __( 'Every recipe in it joins the festive slider at the top of the front page, newest first. Create the category under Posts → Categories and file the recipes there. Empty category = the usual top card.', 'dancingspoon' ),
			'type'        => 'select',
			'choices'     => $choices,
		)
	);
	$wp_customize->add_setting( 'dancingspoon_xmas_kicker', array( 'default' => $d['kicker'], 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control(
		'dancingspoon_xmas_kicker',
		array( 'section' => 'dancingspoon_seasonal', 'label' => __( 'Label over the festive slider', 'dancingspoon' ), 'description' => __( 'Shown instead of "Recipe of the month" while tagged recipes exist. Empty = the usual label.', 'dancingspoon' ), 'type' => 'text' )
	);
	$wp_customize->add_setting(
		'dancingspoon_xmas_count',
		array( 'default' => 6, 'sanitize_callback' => fn( $v ) => in_array( (int) $v, array( 3, 6, 9 ), true ) ? (int) $v : 6 )
	);
	$wp_customize->add_control(
		'dancingspoon_xmas_count',
		array( 'section' => 'dancingspoon_seasonal', 'label' => __( 'Recipes in the festive slider (at most)', 'dancingspoon' ), 'type' => 'select', 'choices' => array( 3 => '3', 6 => '6', 9 => '9' ) )
	);
}
add_action( 'customize_register', 'dancingspoon_customize_seasonal' );

/**
 * body.ds-xmas carries the look.
 */
function dancingspoon_xmas_body_class( array $classes ): array {
	if ( dancingspoon_xmas() ) {
		$classes[] = 'ds-xmas';
	}
	return $classes;
}
add_filter( 'body_class', 'dancingspoon_xmas_body_class' );

/**
 * The greeting bar, rendered right before the header template part so it is the first thing in .wp-site-blocks
 * (above the sticky header, scrolls away with the page) on every template.
 */
function dancingspoon_xmas_bar( string $content, array $block ): string {
	if ( 'header' !== ( $block['attrs']['slug'] ?? '' ) || ! dancingspoon_xmas() ) {
		return $content;
	}
	$d        = dancingspoon_xmas_defaults();
	$greeting = trim( (string) get_theme_mod( 'dancingspoon_xmas_greeting', $d['greeting'] ) );
	if ( '' === $greeting ) {
		return $content;
	}
	$tree = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2.5 8 8.5h2.2L6.5 14h2.3L5 19.5h14L15.2 14h2.3L13.8 8.5H16Z"/><path d="M12 19.5V22"/></svg>';
	return '<div class="ds-xmas-bar"><p class="ds-xmas-bar__text"><span class="ds-xmas-bar__icon">' . $tree . '</span><span>' . esc_html( $greeting ) . '</span></p></div>' . $content;
}
add_filter( 'render_block_core/template-part', 'dancingspoon_xmas_bar', 10, 2 );

/**
 * The festive recipes: published posts in the festive category (the one chosen in the Customizer, else the
 * category with the slug festive-recipes or the name "Festive recipes"), newest first, capped by the Customizer
 * count. Empty unless the Christmas layout is on. Shared by the hero pattern, its query and the kicker.
 *
 * @return int[]
 */
function dancingspoon_festive_ids(): array {
	static $ids = null;
	if ( null !== $ids ) {
		return $ids;
	}
	$ids = array();
	if ( ! dancingspoon_xmas() ) {
		return $ids;
	}
	$chosen = (int) get_theme_mod( 'dancingspoon_xmas_category', 0 );
	$term   = $chosen ? get_term( $chosen, 'category' ) : null;
	if ( ! $term instanceof WP_Term ) {
		$slug = dancingspoon_xmas_defaults()['category'];
		$term = get_term_by( 'slug', $slug, 'category' ) ?: get_term_by( 'name', __( 'Festive recipes', 'dancingspoon' ), 'category' );
	}
	if ( ! $term instanceof WP_Term ) {
		return $ids;
	}
	$ids = array_map(
		'intval',
		get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => (int) get_theme_mod( 'dancingspoon_xmas_count', 6 ),
				'cat'            => $term->term_id,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		)
	);
	return $ids;
}

/**
 * The slider's photos carry their own ratio inline (aspect-ratio: W / H from the attachment's width and height),
 * so they show whole on every screen. The theme's 16:11 hero crop must not apply, but a plain "aspect-ratio: auto"
 * would also drop the ratio the browser derives from the width/height attributes, and WordPress's lazy-loading
 * markup (sizes="auto") puts such an image under size containment with a 3000 × 1500 placeholder: the photo
 * would render 1500 px tall. An explicit ratio sidesteps both.
 */
function dancingspoon_festive_image_ratio( string $content, array $block, WP_Block $instance ): string {
	// only the slider's images: the hero pattern asks for the "large" size when festive recipes exist, and the
	// post must be one of them (the single template's hero keeps its 16:9 crop)
	if ( 'large' !== ( $block['attrs']['sizeSlug'] ?? '' ) || ! in_array( (int) ( $instance->context['postId'] ?? 0 ), dancingspoon_festive_ids(), true ) ) {
		return $content;
	}
	$p = new WP_HTML_Tag_Processor( $content );
	if ( ! $p->next_tag( 'img' ) ) {
		return $content;
	}
	$w = (int) $p->get_attribute( 'width' );
	$h = (int) $p->get_attribute( 'height' );
	if ( $w > 0 && $h > 0 ) {
		$style = trim( (string) $p->get_attribute( 'style' ), "; \t" );
		$p->set_attribute( 'style', ( '' === $style ? '' : $style . ';' ) . 'aspect-ratio:' . $w . ' / ' . $h . ';' );
	}
	return $p->get_updated_html();
}
add_filter( 'render_block_core/post-featured-image', 'dancingspoon_festive_image_ratio', 10, 3 );
