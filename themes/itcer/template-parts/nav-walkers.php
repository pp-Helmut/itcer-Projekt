<?php
/**
 * Navigations-Funktionen
 *
 * @package BBSB
 **/



class Meta_Nav_Walker extends Walker {

	private function icon_id( $title ) {
		$icons = array(
			'search'   => 'Suchen',
			'contrast' => 'Kontrast',
			'view'     => 'Ansicht',
		);

		$key = array_search( $title, $icons, true );

		return $key;
	}

	public function walk( $elements, $max_depth ) {

		$list = array();

		foreach ( $elements as $item ) {

			$list[] = '<li><a href="' . $item->url . '"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . 'img/icons.svg#' . $this->icon_id( $item->title ) . '"></use></svg><span>' . $item->title . '</span></a></li>';
		}

			return join( $list );
	}
}

// class Main_Nav_Walker extends Walker_Nav_Menu
// {
// public function start_lvl( &$output, $depth = 0, $args = array() ) {
// $output .= "<ul>\n";
// }
// public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
// $has_sub = in_array( 'menu-item-has-children', $item->classes, true );
// $class = ( $item->current ? 'active current' : ( $item->current_item_ancestor ? 'active' : null ) ) .
// ( $has_sub ? 'has-sub' : null );
// $output .= sprintf( '<li%s><a href="%s">%s</a>',
// ( empty( $class ) ? '' : ' class="'.$class.'"' ),
// ( $has_sub ? '#' : $item->url ),
// $item->title
// );
// }
// public function end_el( &$output, $item, $depth = 0, $args = array() ) {
// $output .= "</li>\n";
// }
// public function end_lvl( &$output, $depth = 0, $args = array() ) {
// $output .= '</ul>';
// }
// }
class Main_Nav_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$has_sub = is_array( $item->classes ) && in_array( 'menu-item-has-children', $item->classes, true );
			$output .= sprintf(
				'<li class="%s%s%s"><a href="%s">%s</a>',
				( $item->current ? 'active current ' : ( $item->current_item_ancestor ? 'active ' : null ) ),
				$has_sub ? 'has-sub ' : null,
				'm' . $item->object_id,
				$item->url,
				$item->title
			);

	}
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( 'Hilfe für sehbehinderte Menschen in Bayern' === $item->title ) {
			$output .= '';
		} else {
			$output .= "</li>\n";
		}
	}
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 0 === $depth ) {
			$output .= '<ul class="sub-menu">';
		}
	}
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 0 === $depth ) {
			$output .= '</ul>';
		}
	}
}

class Footer_Nav_Walker extends Walker_Nav_Menu {

	public function walk( $elements, $max_depth ) {
		$list = array();
		foreach ( $elements as $item ) {
				$list[] = '<li><a href="' . $item->url . '"' . ( 'custom' === $item->type ? ' target="_blank" title="' . $item->title . ' in neuem Fenster öffnen."' : null ) . '>' . $item->title . '</a></li>';
		}
		return join( $list );
	}
}

// class Social_Nav_Walker extends Walker {
//
// public function walk( $elements, $max_depth ) {
//
// $list = array();
//
// foreach ( $elements as $item ) {
//
// $list[] = '<li class="' . sanitize_title( $item->title ) . '"><a href="' . $item->url . '" target="_blank" title="' . $item->title . ' in neuem Fenster öffnen."><img src="' . get_template_directory_uri() . '/img/' . sanitize_title( $item->title ) . '.svg" alt="' . $item->title . '"/></a></li>';
// }
//
// return join( $list );
// }
// }
// class service_Nav_Walker extends Walker_Nav_Menu {
// public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
// $output .= sprintf(
// '<li class="%s"><a href="%s" %s title="' . $item->title . '" >%s</a>',
// strtolower( $item->title ),
// $item->url,
// ( 'Facebook' === $item->title ? 'target="_blank"' : null ),
// '                <i class="s-icon">
// <svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . get_template_directory_uri() . '/img/icons.svg#' . strtolower( $item->title ) . '"></use></svg></i>'
// );
// }
// public function end_el( &$output, $item, $depth = 0, $args = array() ) {
// $output .= "</li>\n";
// }
// }
