<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
 	<?php wp_head(); ?>
	<title><?php wp_title(); ?></title>
</head>
<body <?php body_class(); ?>>

	<div class="mobile-nav">
		<?php
			// $nav_args = array(
			// 	'theme_location' => 'header-menu',
			// 	'fallback_cb'	 => false,
			// 	'container' 	 => false,
			// );
			// wp_nav_menu($nav_args);
		?>
	</div>

	<div id="header">
		<a href="<?php bloginfo('url'); ?>" id="logo">

		</a>

		<div class="nav">
			<?php
				// $nav_args = array(
				// 	'theme_location' => 'header-menu',
				// 	'fallback_cb'	 => false,
				// 	'container' 	 => false,
				// );
				// wp_nav_menu($nav_args);
			?>

			<div class="toggle-menu">
				<div class="tog top"></div>
				<div class="tog mid"></div>
				<div class="tog bot"></div>
			</div>
		</div>
	</div>
