<?php
/**
 * Dancing Spoon — placeholder front page until the real theme lands.
 *
 * @package dancingspoon
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main>
	<h1><?php bloginfo( 'name' ); ?></h1>
	<p><?php bloginfo( 'description' ); ?></p>
	<p>Theme <?php echo esc_html( wp_get_theme()->get( 'Name' ) . ' ' . wp_get_theme()->get( 'Version' ) ); ?></p>
</main>
<?php wp_footer(); ?>
</body>
</html>
