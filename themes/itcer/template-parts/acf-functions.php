<?php
/**
 * Gibt die Inhalte einer ACF Galerie zurück
 *
 * @param string $field   ACF Feld, das verwendet werden soll.
 */
function get_gallery( $field ) {
	$gallery = '';
	if ( $field ) {
		$get_tag = new HtmlMaker();
		$images  = $field;
		foreach ( $images as $image ) {
			$gallery_item = get_picture_tag( $image['ID'], array( 0 => 'gallery' ), $image['alt'], null, 'g_image' );
			$gallery     .= $get_tag->li( $gallery_item );
		}
		$gallery = $get_tag->ul( $gallery, 'gallery' );
	}
	return $gallery;
}
// downlaod
function get_acf_download( $flexible ) {
	$content  = false;
	$content .= '<section class="download-boxen">';
	$download = $flexible['download'];
	if ( $download ) {
		foreach ( $download as $download_item ) {
			$template_dir_path = get_template_directory_uri();
			$link              = false;
			$logo              = false;
				$datei         = $download_item['datei'];
				$name          = $download_item['name'];
				$content      .= '<article class="download-box">
					<a class="download-link" href="' . $datei['url'] . '">
						<div class="download-icon file-' . $file['subtype'] . '"></div>
						<div class="download-text"><span>' . $name . '</span></div></a></article>';
		}
	}
	$content .= '</section>';
	return $content;
}
	/**
	 * Lädt eine als HTML Datei gewählt in die Ausgabe
	 *
	 * @param string $flexible ACF Objekt des Feldes.
	 */
function get_acf_html( $flexible ) {
	if ( isset( $flexible['file'] ) && $flexible['file'] ) {
		$src_path = get_attached_file( $flexible['file'] );
		if ( file_exists( $src_path ) ) {
			$file_contents = file_get_contents( $src_path );
			if ( $file_contents !== strip_tags( $file_contents ) ) {
				return str_ireplace( '%%%path%%%', get_template_directory_uri(), $file_contents );
			}
		}
	}
	return false;
}
/**
 * Gibt den Banner zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_banner( $flexible ) {
	if ( isset( $flexible['headline'] ) ) {
		$content = '<header class="header" id="header"><h1>' . $flexible['headline'] . '</h1><span class="sub-title">' . $flexible['subline'] . '</span><img alt="" src="' . get_template_directory_uri() . '/img/aves-banner.svg"></header>';
		return $content;
	}
	return false;
}
/**
 * Gibt die Kacheln zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_tiles( $flexible ) {
	if ( isset( $flexible['tiles'] ) ) {
		$content = '<div class="section"><ul class="tile-items imageteaser">';
		foreach ( $flexible['tiles'] as $tile ) {
			$img      = get_picture_tag( $tile['img']['ID'], array( 0 => 'tiles' ), $tile['img']['alt'] );
			$content .= '<li class="column"><a href="' . $tile['url'] . '">' . $img . '<span>' . $tile['title'] . '</span></a></li>';
		}
		$content .= '</ul></div>';
		return $content;
	}
	return false;
}
/**
 * Gibt das Text Überschrift
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_headline( $flexible ) {
	$headline = '';
	if ( isset( $flexible['headline'] ) ) {
		$headline = $flexible['headline'];
		$content  = '<header class="header article-header" ><h2>' . $headline . ' </h2></header> ';
		return $content;
	}

	return false;
}
/**
 * Gibt das Text mit Bild Feld zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_text_and_image( $flexible ) {
	$text     = '';
	$img      = '';
	$headline = '';
	if ( isset( $flexible['headline'] ) ) {
		$headline = '<header><h2>' . $flexible['headline'] . '</header></h2>';
	}
	if ( isset( $flexible['text'] ) ) {
		$text = $flexible['text'];
		$text = '<div class="align-left">' . $headline . $text . ' </div> ';

		if ( isset( $flexible['img'] ) ) {
			$img = get_picture_tag( $flexible['img']['ID'], array( 0 => 'tiles' ), $flexible['img']['alt'] );
			$img = '<div class = "align-right" >' . $img . '</div>';
		}
		$content = '<div class="section text-and-image">' . $text . $img . '</div>';
		return $content;
	}
	return false;
}
/**
 * Gibt den Essensplan zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_casino_menu( $flexible ) {

	function get_meal( $date, $meal, $id ) {
		$title     = $meal['title'];
		$price     = number_format( $meal['price'], 2, ',', '.' );
		$detail_id = strtolower( substr( date( 'l', $date ), 0, 2 ) ) . '-menue-' . $id;
		$content   = '<td>
			<a href="#" data-detail="' . $detail_id . '">
			<span class="menu">' . $title . '</span>
			<span class="price">' . $price . ' €</span>
			<span class="additive">Zusatzstoffe / Allergene: ' . get_additives( $meal, 'all' ) . '</span>
			</a>
			<section class="detail-menu" id="' . $detail_id . '">
				<div class="close" id="close">
					<span>schließen</span>
					<svg role="img" class="symbol" aria-hidden="true" focusable="false">
						<use xlink:href="' . get_template_directory_uri() . '/img/icons.svg#closebuttton-green"></use>
					</svg>
				</div>
				<div class="detail-wrap">
					<div class="detail-item a">
						<h2>' . $title . '</h2>
					</div>
					<div class="detail-item b">
						<span>' . $price . ' €</span>
					</div>
					' . get_additives( $meal, 'additives' ) . get_additives( $meal, 'allergens' ) . '
				</div>
		</section>
		</td>';
		return $content;
	}

	function get_additives( $meal, $type = 'all' ) {
		if ( $meal['additives'] ) {
			$add_list = '<div class="detail-item c">
				<h3>Zusatzstoffe</h3>
				<ul>';

			foreach ( $meal['additives'] as $additive ) {
					$add_labels[] .= $additive['label'];
				$add_list         .= '<li><span>' . $additive['label'] . '</span> - ' . $additive['value'] . '</li>';
				$j++;
			}
			$add_list .= '</ul></div>';
		}
		if ( $meal['allergens'] ) {
			$allergen_list = '<div class="detail-item d">
				<h3>Allergene</h3>
				<ul>';

			foreach ( $meal['allergens'] as $allergen ) {
					$add_labels[] .= $allergen['label'];
				$allergen_list    .= '<li><span>' . $allergen['label'] . '</span> - ' . $allergen['value'] . '</li>';
				$j++;
			}
			$allergen_list .= '</ul></div>';
		}

		if ( 'all' === $type ) {
			$i   = 0;
			$len = count( $add_labels );
			foreach ( $add_labels as $add_label ) {
				$i++;
				$add_labels_serial .= $add_label;
				if ( $i !== $len ) {
					$add_labels_serial .= '/';
				}
			}
			return $add_labels_serial;
		} elseif ( 'additives' === $type ) {
			return $add_list;
		} elseif ( 'allergens' === $type ) {
			return $allergen_list;
		}
	}

	if ( isset( $flexible['week'] ) ) {
		$content   = '<table id="week-plan">
		<tr>
		 <th>Datum</th>
		 <th>Trend</th>
		 <th>Klassik</th>
		 <th>Heimat</th>
	 </tr>';
		$weeks     = $flexible['week'];
		$day_names = array( 'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag' );
		$i         = 0;
		foreach ( $weeks as $week ) {
			foreach ( $week['day'] as $day ) {
				if ( date( 'W' ) === date( 'W', $day['date'] ) ) {
					$i++;
					$content .= '<tr><td><span class="day">' . $day_names[ date( 'w', $day['date'] ) ] . '</span><span class="date">' . date( 'd.m.', $day['date'] ) . '</span></td>';

					if ( $day['trend'] ) {
						$content .= get_meal( $day['date'], $day['trend'], 1 );
					} else {
						$content .= '<td></td>';
					}
					if ( $day['classic'] ) {
						$content .= get_meal( $day['date'], $day['classic'], 2 );
					} else {
						$content .= '<td></td>';
					}
					if ( $day['home'] ) {
						$content .= get_meal( $day['date'], $day['home'], 3 );
					} else {
						$content .= '<td></td>';
					}

					$content .= '</tr>';
				}
			}
		}
		$content .= '</table>';
		return $content;
	}
	return false;
}
/**
 * Gibt die Inhalte eines Abschnitts der flexiblen ACF Felds zurück
 *
 * @param string $flexible ACF Objekt des Abschnitts.
 */
function get_flexible_row( $flexible ) {
	$get_tag = new HtmlMaker();
	$content = false;
	foreach ( $flexible as $row ) {
		if ( isset( $row['acf_fc_layout'] ) && function_exists( 'get_acf_' . $row['acf_fc_layout'] ) ) {
			$content .= call_user_func( 'get_acf_' . $row['acf_fc_layout'], $row );
		}
	}
	return $content;
}
	/**
	 * Gibt die Inhalte eines flexiblen ACF Felds zurück
	 *
	 * @param string $field ACF Feld, das verwendet werden soll.
	 */
function get_flexible_content( $field ) {
	$flexible = get_field_object( $field );
	$content  = '';
	if ( is_array( $flexible ) && isset( $flexible['value'] ) && is_array( $flexible['value'] ) ) {
			$n = 0;
		foreach ( $flexible['value'] as $section ) {
			if ( isset( $section['slide'] ) && is_array( $section['slide'] ) ) {
					$row = get_flexible_row( $section['slide'] );
				if ( $n === 0 ) {
					$row = '<header class="header" id="header"><h1>' . get_the_title() . '</h1><img alt="" src="' . get_template_directory_uri() . '/img/waves-title.svg"></header>' . $row;
					++ $n;
				}
			}
			$content .= '<article class="slide">' . $row . '</article>';
		}
		return $content;
	}
	return false;
}
	/**
	 * Gibt die Inhalte eines flexiblen ACF Felds aus
	 *
	 * @param string $field ACF Feld, das verwendet werden soll.
	 */
function the_flexible_content( $field ) {
	echo get_flexible_content( $field );
}
function hook_acf_css() {
	global $acf_css;
	echo '<style>' . $acf_css . '</style>';
	unset( $GLOBALS['acf_css'] );
}
function hook_acf_js() {
	global $acf_js;
	echo '<script>' . $acf_js . '</script>';
	unset( $GLOBALS['acf_js'] );
}
