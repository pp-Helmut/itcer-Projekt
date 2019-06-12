<?php
/**
 * Header
 *
 * @package BBSB
 **/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" data-path="<?php echo esc_url( get_template_directory_uri() ); ?>" itemscope itemtype="http://schema.org/WebPage">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

			<div class="outer">
		    <header class="header section">
		      <nav class="main-nav">
		        <button class="menu-button toggle" data-for="main-menu" title="Menü ein-/ausblenden" aria-haspopup="true" aria-expanded="false">
		            <span class="menu-button-box">
		             <span class="menu-button-inner"></span>
		           </span>
		           <span class="menu-button-label">Menü</span>
		       </button>
		        <ul id="main-menu">
								<?php
										wp_nav_menu(
											array(
												'theme_location' => 'main-menu',
												'walker' => new Main_Nav_Walker(),
												'container' => '',
												'items_wrap' => '%3$s',
												'depth'  => 3,
											)
										);
								?>
							</ul>
						</nav>
						<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/Logo_Seniorenstift_Neuhausen.svg" alt="">
			      </a>

						<?php
							if ( has_post_thumbnail() ) {
									the_post_thumbnail();
							}
						?>
			    </header>
