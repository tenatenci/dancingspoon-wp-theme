<?php
/**
 * Dancing Spoon theme setup.
 *
 * @package dancingspoon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dancingspoon_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	load_theme_textdomain( 'dancingspoon', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'dancingspoon_setup' );

function dancingspoon_styles() {
	wp_enqueue_style( 'dancingspoon', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'dancingspoon_styles' );
