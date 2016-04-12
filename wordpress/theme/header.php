<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
 	<?php wp_head(); ?>
	<title><?php wp_title(); ?></title>
</head>
<body <?php body_class(); ?>>

	<nav class="mobile-menu">
		<?php
			// $nav_args = array(
			// 	'theme_location' => 'header-menu',
			// 	'fallback_cb'	 => false,
			// 	'container' 	 => false,
			// );
			// wp_nav_menu($nav_args);
		?>
	</nav>

	<header>
		<a href="<?php bloginfo('url'); ?>" class="logo">

		</a>

		<nav class="primary-menu">
			<?php
				// $nav_args = array(
				// 	'theme_location' => 'header-menu',
				// 	'fallback_cb'	 => false,
				// 	'container' 	 => false,
				// );
				// wp_nav_menu($nav_args);
			?>

			<div class="mobile-menu-toggler">
				<div class="tog top"></div>
				<div class="tog mid"></div>
				<div class="tog bot"></div>
			</div>
		</nav>
	</header>
