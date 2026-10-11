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
