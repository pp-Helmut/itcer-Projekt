<?php
/**
 * Funktionen um Bilder responsiv im Template auszugeben
 *
 * @package Pfennigparade Template Functions
 **/

/**
 * Lädt das Beitragsbild mit Vererbung und baut ein Responsives Picture Tag.
 *
 * @param array  $sizes    Breakpoints und Größen zur Anzeige des Bildes;
 *                         Format 'breakpoint' => 'size'.
 * @param string $alt_text Alternativtext für das Image Tag.
 * @param string $class      CSS class Angabe für das Image Tag.
 * @param bool   $crop     Bild zuschneiden.
 */
function the_thumbnail_picture( $sizes = array(), $alt_text = '', $class = '', $crop = false ) {

	$post    = get_post();
	$posts   = get_post_ancestors( $post->ID );
	$posts[] = get_option( 'page_on_front' );

	array_unshift( $posts, $post->ID );

	foreach ( $posts as $postid ) {

		$post_thumbnail_id = get_post_thumbnail_id( $postid );

		if ( ! empty( $post_thumbnail_id ) ) {

			the_picture_tag( $post_thumbnail_id, $sizes, $alt_text, $class );

			break;

		}
	}

}

/**
 * Gibt ein Responsives HTML5 Picture Tag aus.
 *
 * @param id     $media_id      Die ID des zu benutzenden Bildes.
 * @param array  $sizes         Breakpoints und Größen zur Anzeige des Bildes;
 *                              Format 'breakpoint' => 'size'.
 * @param string $alt_text      Alternativtext für das Image Tag.
 * @param string $class         CSS class Angabe für das Image Tag.
 * @param bool   $crop          Bild zuschneiden.
 */
function the_picture_tag( $media_id, $sizes, $alt_text = '', $class = '', $crop = false ) {

	echo get_picture_tag( $media_id, $sizes, $alt_text, $class, $crop );

}

/**
 * Erzeugt ein Responsives HTML5 Picture Tag.
 *
 * @param int    $media_id Die ID des zu benutzenden Bildes.
 * @param array  $sizes    Breakpoints und Größen zur Anzeige des Bildes;
 *                         Format 'breakpoint' => 'size',
 *                         Beispiel array( 0 => 'thumbnail').
 * @param string $alt_text Alternativtext für das Image Tag.
 * @param string $class    CSS class Angabe für das Image Tag.
 * @param bool   $crop     Bild zuschneiden.
 * @param array  $picture_data    Vordefinierter picture_data Array.
 */
function get_picture_tag( $media_id, $sizes, $alt_text = '', $class = '', $crop = false, $picture_data = false ) {

	$get_tag = new HtmlMaker();

	if ( ! $picture_data ) {
		$picture_data = get_picture_data( $media_id, $sizes, $alt_text, $class );
	}

	if ( 1 < count( $picture_data['sizes'] ) ) {

		$picture_content = '';

		foreach ( $picture_data['sizes'] as $query => $size ) {

			$picture_content .= $get_tag->tag( 'source', null, get_source_args_ppp( $picture_data, $query ), true );

		}

		$picture_content .= $get_tag->tag( 'img', null, get_img_args_ppp( $picture_data, 0 ), true );

		return $get_tag->tag( 'picture', $picture_content );

	} elseif ( 1 === count( $picture_data['sizes'] ) && array_key_exists( 'file', $picture_data['sizes'][0] ) ) {

		return $get_tag->tag( 'img', null, get_img_args_ppp( $picture_data, 0 ), true );

	} elseif ( false === $crop ) {

		return $get_tag->tag( 'img', null, get_img_args_ppp( $picture_data ), true );

	}

	return null;
}

/**
 * Liefert ein Array mit allen Daten für das Picture Tag zurück inklusive aller gewünschten Größen, auch in doppelter Auflösung.
 *
 * @param int    $media_id Die ID des zu benutzenden Bildes.
 * @param array  $sizes    Breakpoints und Größen zur Anzeige des Bildes;
 *                         Format 'breakpoint' => 'size'.
 * @param string $alt_text Alternativtext für das Image Tag.
 * @param string $class    CSS class Angabe für das Image Tag.
 */
function get_picture_data( $media_id, $sizes, $alt_text = '', $class = '' ) {

	$picture_data = array(
		'sizes' => array(),
		'class' => $class,
	);

	$media_meta = wp_get_attachment_metadata( $media_id );

	$picture_data['alt']       = get_alt_text_ppp( $media_id, $alt_text );
	$picture_data['base_path'] = get_base_path_ppp( $media_meta['file'] );
	$picture_data['base_url']  = trailingslashit( wp_upload_dir()['baseurl'] ) . $picture_data['base_path'];
	$picture_data['base_img']  = trailingslashit( wp_upload_dir()['baseurl'] ) . $media_meta['file'];

	if ( is_array( $sizes ) ) {

		krsort( $sizes );

		foreach ( $sizes as $query => $size ) {

			if ( array_key_exists( $size, $media_meta['sizes'] ) ) {

				$picture_data['sizes'][ $query ] = $media_meta['sizes'][ $size ];

				$picture_data['sizes'][ $query ]['file2x'] = get_2x_file_ppp( $media_meta['sizes'][ $size ]['file'], $picture_data['base_path'] );

			}
		}
	}

	return $picture_data;

}

/**
 * Gibt die benötigen Argumente für ein Image Tag zurück.
 *
 * @param array $picture_data Daten des verwendeten Bildes.
 * @param array $size_key     Verwendete Bildgröße.
 */
function get_img_args_ppp( $picture_data, $size_key = null ) {

	$args = array(
		'alt' => $picture_data['alt'],
	);

	if ( $picture_data['class'] ) {
		$args['class'] = $picture_data['class'];
	}

	if ( is_int( $size_key ) ) {

		$args['src'] = $picture_data['base_url'] . $picture_data['sizes'][ $size_key ]['file'];

		if ( array_key_exists( 'file2x', $picture_data['sizes'][ $size_key ] ) && $picture_data['sizes'][ $size_key ]['file2x'] ) {

			$args['srcset'] = $picture_data['base_url'] . $picture_data['sizes'][ $size_key ]['file2x'] . ' 2x';

		}
	} else {

		$args['src'] = $picture_data['base_img'];

	}

	return $args;

}

/**
 * Gibt die benötigen Argumente für ein Source Tag zurück.
 *
 * @param array $picture_data Daten des verwendeten Bildes.
 * @param array $size_key     Verwendete Bildgröße.
 */
function get_source_args_ppp( $picture_data, $size_key ) {

	$args = array(
		'media' => '(min-width: ' . $size_key . 'px)',
	);

	$args['srcset'] = $picture_data['base_url'] . $picture_data['sizes'][ $size_key ]['file'];

	if ( array_key_exists( 'file2x', $picture_data['sizes'][ $size_key ] ) && $picture_data['sizes'][ $size_key ]['file2x'] ) {

		$args['srcset'] .= ' 1x,' . $picture_data['base_url'] . $picture_data['sizes'][ $size_key ]['file2x'] . ' 2x';

	}

	return $args;

}

/**
 * Gibt den Jahres- und Monatspfad von Wordpressmedien zurück.
 *
 * @param string $file      Dateiname aus dem der Pfad ermittelt werden soll.
 * @param string $base_path Jahres- und Monatspfad.
 */
function get_2x_file_ppp( $file, $base_path = '' ) {

	$file   = pathinfo( $file );
	$file2x = false;

	if ( is_array( $file ) && array_key_exists( 'filename', $file ) && array_key_exists( 'extension', $file ) ) {
		$file2x = $file['filename'] . '@2x.' . $file['extension'];
	}

	if ( $file2x && file_exists( trailingslashit( wp_upload_dir()['basedir'] ) . $base_path . $file2x ) ) {
		return $file2x;
	}

	return false;

}

/**
 * Gibt den Jahres- und Monatspfad von Wordpressmedien zurück.
 *
 * @param string $file Dateiname aus dem der Pfad ermittelt werden soll.
 */
function get_base_path_ppp( $file ) {

	$file = pathinfo( $file );

	if ( is_array( $file ) && array_key_exists( 'dirname', $file ) ) {

		return trailingslashit( $file['dirname'] );

	}

	return '';
}

/**
 * Gibt Alt Text zurück, ermittelt aus Medienobjekt oder Fest angegeben
 *
 * @param int    $media_id      Die ID des zu benutzenden Bildes.
 * @param string $alt_text      Vorgegebener Alternativtext.
 */
function get_alt_text_ppp( $media_id, $alt_text = '' ) {

	if ( ! is_null( $alt_text ) && empty( $alt_text ) ) {
		  $alt_text = get_post_meta( $media_id, '_wp_attachment_image_alt', true );
	}

	return trim( esc_attr( $alt_text ) );

}

/**
 * Liefert die ID einer Media Datei zu einer URL
 *
 * @param string $url      URL der Mediendatei.
 */
function get_attachment_id_by_url_ppp( $url ) {

	$parsed_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

	if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
		return;
	}

	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );
	if ( isset( $attachment[0] ) ) {

		return $attachment[0];
	}

	return;

}

/**
 * Prüft ob kleinstes Hero-Bild auch als Mobile Version vorhanden ist
 *
 * @param int $image_id      ID des Herobildes.
 */
function get_mobile_hero_picturedata( $image_id ) {

	$image_url    = pathinfo( wp_get_attachment_image_url( $image_id, 'full' ) );
	$mobile_id    = false;
	$picture_data = get_picture_data(
		$image_id, array(
			641  => 'hero-medium',
			769  => 'hero-large',
			1025 => 'hero-max',
		)
	);

	if ( $image_url ) {

		if ( is_array( $image_url ) && array_key_exists( 'filename', $image_url ) && array_key_exists( 'extension', $image_url ) ) {
			$mobile    = $image_url['dirname'] . '/' . $image_url['filename'] . '-mobile.' . $image_url['extension'];
			$mobile_id = get_attachment_id_by_url_ppp( $mobile );

		}

		if ( $mobile_id ) {

			$mobile_picture_data = get_picture_data( $mobile_id, array( 0 => 'hero-small' ) );

			$picture_data['sizes'][0] = $mobile_picture_data['sizes'][0];

			return( $picture_data );

		}
	}

	return false;

}
