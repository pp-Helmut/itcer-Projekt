<?php
/**
 * Fehlerseite 404
 *
 * @package BBSB
 **/

get_header();

$path_parts = explode( '-', trim( $_SERVER['REQUEST_URI'], '/' ), 2 );

if ( is_array( $path_parts ) && isset( $path_parts[1] ) ) {
	$posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type'   => 'post',
			'meta_key'    => 'id',
			'meta_value'  => str_replace( '_', '-', str_replace( '__', ' ', $path_parts[0] ) ),
		)
	);

	if ( $posts ) {

		wp_redirect( get_site_url() . '/' . $posts[0]->post_name . '/', 301 );

	}
}

?>



<main class="main" id="main" content="true">
	<h1>Es tut uns leid, ...</h1>
	<p>die von Ihnen gewünschte Seite wurde nicht gefunden!</p>

</main>

<?php get_footer(); ?>
