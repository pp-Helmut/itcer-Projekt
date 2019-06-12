<?php
/**
 * Allgemeine Theme Funktionen
 *
 * @package BBSB
 **/


 // ACF.
// require_once 'template-parts/acf-definitions.php';
// require_once 'template-parts/acf-functions.php';
// Breadcrumb.
require_once 'template-parts/breadcrumb.php';
// HTML Maker laden.
require_once 'template-parts/html-maker.php';
// Kommentare deaktivieren.
require_once 'template-parts/disable-comments.php';
// Navigations-Funktionen bereitstellen.
require_once 'template-parts/nav-walkers.php';
// Funktionen für responsive Bilder.
require_once 'template-parts/picture-functions.php';
// Funktionen für Gutenberg Editor.
require_once 'theme-blocks/theme-blocks.php';
// Title Tag aktiveren.
add_theme_support( 'title-tag' );
// Beitragsbild aktiveren.
add_theme_support( 'post-thumbnails' );
// Bildgrößen registrieren.
add_image_size( 'tiles', 500, 304, true );
add_image_size( 'imageteaser', 720, 828, true );
function ww_custom_sizes( $sizes ) {
	return array_merge(
		$sizes, array(
			'imageteaser' => 'Teaser-Bild',
		)
	);
}
add_filter( 'image_size_names_choose', 'ww_custom_sizes' );
/**
 * Disable Gutenberg style in front
 **/
function wps_deregister_styles() {
	wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
/**
 * Max-width for Gutenberg style in back
 **/
function custom_admin_css() {
	echo '<style type="text/css">
.wp-block { max-width: 1280px; }
</style>';
}
add_action( 'admin_head', 'custom_admin_css' );

/**
 * Define Gutenberg color palette
 **/
function ww_setup_theme_supported_features() {
    add_theme_support( 'editor-color-palette', array(
        array(
            'name' => __( 'peach', 'themeLangDomain' ),
            'slug' => 'peach',
            'color' => '#FEA674',
        ),
        array(
            'name' => __( 'white', 'themeLangDomain' ),
            'slug' => 'white',
            'color' => '#ffffff',
        ),
        array(
            'name' => __( 'very light gray', 'themeLangDomain' ),
            'slug' => 'very-light-gray',
            'color' => '#eee',
        ),
        array(
            'name' => __( 'very dark gray', 'themeLangDomain' ),
            'slug' => 'very-dark-gray',
            'color' => '#444',
        ),
    ) );

		add_theme_support( 'disable-custom-colors' );
}

add_action( 'after_setup_theme', 'ww_setup_theme_supported_features' );

/**
 * Entfernt die WP Versionsangaben
 *
 * @param str $src Pfad zur Datei.
 * @param str $handle Handle der Datei.
 */
function ww_remove_ver_css_js( $src, $handle ) {
	$handles_with_version = [ 'ww-script', 'ww-layout' ];

	if ( strpos( $src, 'ver=' ) && ! in_array( $handle, $handles_with_version, true ) ) {
		$src = remove_query_arg( 'ver', $src );
	}

	return $src;
}
add_filter( 'style_loader_src', 'ww_remove_ver_css_js', 9999, 2 );
add_filter( 'script_loader_src', 'ww_remove_ver_css_js', 9999, 2 );

// XML-RPC API für Fernzugriff deaktivieren.
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Emoji aus dem header entfernen
 **/
function ww_disable_emoji_dequeue_script() {
	wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'ww_disable_emoji_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Head Links entfernen
 **/
function ww_remove_headlinks() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_shortlink_header', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
}
add_action( 'init', 'ww_remove_headlinks' );

/**
 * Registriert die Menüs
 **/
function ww_register_menus() {
	register_nav_menu( 'main-menu', 'Hauptmenü' );
	register_nav_menu( 'service-menu', 'Servicemenü' );
	register_nav_menu( 'meta-menu', 'Metamenü' );
	register_nav_menu( 'social-menu', 'Socialmenü' );
	register_nav_menu( 'footer-main', 'Footermenü Main' );
	register_nav_menu( 'footer-service', 'Footermenü Service' );
}
add_action( 'init', 'ww_register_menus' );

/**
 * Liefert die Body Klassen mit Menü-Index
 *
 * @param string  $menu_position Menü-Position des Hauptmenüs.
 * @param integer $post_id Beitrags-ID optional, Standard aktueller Post.
 */
function get_body_class_index( $menu_position, $post_id = false ) {

	$return    = '';
	$locations = get_nav_menu_locations();

	if ( array_key_exists( $menu_position, $locations ) ) {
		$menu       = wp_get_nav_menu_object( $locations[ $menu_position ] );
		$menu_items = wp_get_nav_menu_items( $menu->term_id );
		$post_id    = $post_id ? $post_id : get_the_ID();

		foreach ( $menu_items as $menu_item ) {
			if ( intval( $menu_item->object_id ) === $post_id ) {
				$return = 'p' . esc_attr( $menu_item->menu_order ) . ' ';
				break;
			}
		}
	}

	$return .= is_front_page() ? 'home' : basename( get_permalink() );

	return $return;

}

/**
 * Gibt die Body Klassen mit Menü-Index aus
 *
 * @param string  $menu_position Menü-Position des Hauptmenüs.
 * @param integer $post_id Beitrags-ID optional, Standard aktueller Post.
 */
function the_body_class_index( $menu_position, $post_id = false ) {

		echo esc_attr( get_body_class_index( $menu_position, $post_id = false ) );

}

/**
 * Liefert Array mit Datei inklusive Template Pfad und Änderungsdatum.
 *
 * @param array $src Pfad zur Datei innerhalb des WordPress Verzeichnisses.
 */
function get_src_path_uri_version( $src ) {
	$src      = '/' . rtrim( $src, '/' );
	$src_path = get_template_directory() . $src;
	$src_uri  = get_template_directory_uri() . $src;

	if ( file_exists( $src_path ) ) {
		return array(
			'uri'     => $src_uri,
			'version' => filemtime( $src_path ),
		);
	}

	return false;
}

/**
 * Ruft wp_enqueue_script setzt das Änderungsdatum der Datei als Version.
 *
 * @param array $handle    Name des Scripts.
 * @param array $src       Pfad zum Script innerhalb des aktuellen Template Verzeichnisses.
 * @param array $deps      Abhängigkeiten zu anderen Scripts.
 * @param array $in_footer True wenn Script vor /body statt vor /head ausgegeben werden soll, default false.
 */
function enqueue_script_with_timestamp( $handle, $src, $deps = array(), $in_footer = false ) {
	$src = get_src_path_uri_version( $src );

	if ( $src ) {
		wp_enqueue_script( $handle, $src['uri'], $deps, $src['version'], $in_footer );
	}
}

/**
 * Ruft wp_enqueue_style setzt das Änderungsdatum der Datei als Version.
 *
 * @param array $handle Name des Styles.
 * @param array $src    Pfad zum Style innerhalb des aktuellen Template Verzeichnisses.
 * @param array $deps   Abhängigkeiten zu anderen Styles.
 * @param array $media  Medien, für die das Style gedacht ist, default all.
 */
function enqueue_style_with_timestamp( $handle, $src, $deps = array(), $media = 'all' ) {
	$src = get_src_path_uri_version( $src );

	if ( $src ) {
		wp_enqueue_style( $handle, $src['uri'], $deps, $src['version'], $media );
	}
}

/**
 * Lädt Skripte und Styles.
 */
function enqueue_styles_scripts() {

	// WordPress jQuery entfernen.
	wp_deregister_script( 'jquery' );

	// Aktuelles jQuery registrieren.
	wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), false, true );

	// Aktuelles jQuery laden.
	wp_enqueue_script( 'jquery' );

	// Haupt-Skript laden.
	enqueue_script_with_timestamp( 'ww-script', 'js/app.min.js', array( 'jquery' ), true );

	// Haupt-Style laden.
	if ( current_user_can( 'administrator' ) ) {
		enqueue_style_with_timestamp( 'ww-layout', 'css/template.css' );
	} else {
		enqueue_style_with_timestamp( 'ww-layout', 'css/template.min.css' );
	}

	wp_enqueue_style( 'typekit', '//use.typekit.net/bug8dtx.css' );

	// Slider laden.
	wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array( 'jquery' ), true );

	// SVG-Unterstützung für IE laden.
	wp_enqueue_script( 'svg4everybody', get_template_directory_uri() . '/js/svg4everybody.min.js', array( 'jquery' ), true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_styles_scripts' );

/**
 * Setzt die WP SEO Metabox nach unten.
 */
function filter_yoast_seo_metabox() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'filter_yoast_seo_metabox' );

// Allow SVG
add_filter(
	'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {

		global $wp_version;
		if ( $wp_version !== '4.7.1' ) {
			return $data;
		}

		$filetype = wp_check_filetype( $filename, $mimes );

		return [
			'ext'             => $filetype['ext'],
			'type'            => $filetype['type'],
			'proper_filename' => $data['proper_filename'],
		];

	}, 10, 4
);

function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
	echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

/**
 * Kurzinhalt individuelle Wortanzahl
 *
 * @param  integer $limit Wortanzahl.
 */
function excerpt( $limit ) {
	$excerpt = explode( ' ', get_the_excerpt(), $limit );
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( ' ', $excerpt ) . '...';
	} else {
		$excerpt = implode( ' ', $excerpt );
	}
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
	return $excerpt;
}

if ( ! function_exists( 'write_log' ) ) {
	/**
	 * Schreibt individuellen Inhalt nach wp-content/debug.log
	 *
	 * @param misc $log Inhalt zur Ausgabe.
	 **/
	function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}

	}
}
