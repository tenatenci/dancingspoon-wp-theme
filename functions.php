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
		// the carousel
		$query['posts_per_page'] = (int) get_theme_mod( 'dancingspoon_carousel_count', 6 );
		$query['offset']         = 0;
		if ( $hero_id ) {
			$query['post__not_in'] = array( $hero_id );
		}
		if ( 'random' === get_theme_mod( 'dancingspoon_carousel_mode', 'latest' ) ) {
			$query['orderby'] = 'rand';
			unset( $query['order'] );
		} else {
			$query['orderby'] = 'date';
			$query['order']   = 'DESC';
		}
		return $query;
	}

	// query 2: the grid
	$query['posts_per_page'] = $grid_count;
	$query['offset']         = 0;
	if ( $hero_id ) {
		$query['post__not_in'] = array( $hero_id );
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
