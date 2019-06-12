<?php
/**
 * HTML Maker Funktionen
 *
 * @package BBSB
 **/

/**
 * Klasse zur Erstellung von HTML Tags
 **/
class HtmlMaker
{
	/**
	 * Gibt String mit alphabetisch sortierten Tag Attributen generiert aus einem Array zurück.
	 *
	 * @param array $attributes Attribute als Array im Format Attribut => Wert.
	 */
	private function get_attributes( $attributes = array() ) {

		$return = '';

		if ( is_array( $attributes ) ) {

			ksort( $attributes );

			foreach ( $attributes as $attribute => $value ) {
					$return .= ' ' . $attribute . ( $value ? '="' . $value . '"' : null );
			}

			return $return;
		}

		return null;
	}

	/**
	 * Gibt ein HTML Tag zurück
	 *
	 * @param string $tag Bezeichner des Elements.
	 * @param string $content Inhalt des Tags.
	 * @param array  $attributes Attribute als Array im Format Attribut => Wert.
	 * @param bool   $void_element Handelt es sich um ein Void Element?.
	 */
	public function tag( $tag, $content = null, $attributes = array(), $void_element = false ) {

		return '<' . $tag . $this->get_attributes( $attributes ) . '>' .
			($void_element ? null : $content . '</' . $tag . '>');

	}

	/**
	 * Gibt ein HTML A Tag zurück
	 *
	 * @param string $content Inhalt des Tags.
	 * @param string $href Link URI.
	 * @param string $class CLASS Attribut.
	 * @param string $title TITLE Attribut.
	 * @param string $target TARGET Attribut.
	 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
	 */
	public function a( $content, $href, $class = null, $title = null, $target = null, $attributes = array() ) {

		$attributes['href'] = $href;
		if ( $class ) {
			$attributes['class'] = $class;
		}
		if ( $title ) {
			$attributes['title'] = $title;
		}
		if ( $target ) {
			$attributes['target'] = $target;
		}

		return $this->tag( 'a', $content, $attributes );

	}

	/**
	 * Gibt ein HTML DIV Tag zurück
	 *
	 * @param string $content Inhalt des Tags.
	 * @param string $class CLASS Attribut.
	 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
	 */
	public function div( $content, $class = null, $attributes = array() ) {

		if ( $class ) {
			$attributes['class'] = $class;
		}

		return $this->tag( 'div', $content, $attributes );

	}

	/**
	 * Gibt ein HTML ARTICLE Tag zurück
	 *
	 * @param string $content Inhalt des Tags.
	 * @param string $class CLASS Attribut.
	 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
	 */
	public function article( $content, $class = null, $attributes = array() ) {

		if ( $class ) {
			$attributes['class'] = $class;
		}

		return $this->tag( 'article', $content, $attributes );

	}

	/**
	 * Gibt ein HTML Image Tag zurück
	 *
	 * @param string $src        SRC Attribut.
	 * @param string $alt        ALT Attribut.
	 * @param string $class      CLASS Attribut.
	 * @param string $title      TITLE Attribut.
	 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
	 */
	public function img( $src, $alt, $class = null, $title = null, $attributes = array() ) {

		if ( $class ) {
			$attributes['class'] = $class;
		}
		if ( $title ) {
			$attributes['title'] = $title;
		}
		$attributes['src'] = $src;
		$attributes['alt'] = $alt;

		return $this->tag( 'img', null, $attributes, true );

	}

	/**
	 * Gibt ein HTML SPAN Tag zurück
	 *
	 * @param string $content Inhalt des Tags.
	 * @param string $class CLASS Attribut.
	 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
	 */
	public function span( $content, $class = null, $attributes = array() ) {

		if ( $class ) {
			$attributes['class'] = $class;
		}

		return $this->tag( 'span', $content, $attributes );

	}

	/**
	 * Gibt ein HTML SECTION Tag zurück
	 *
	 * @param string $content Inhalt des Tags.
	 * @param string $class CLASS Attribut.
	 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
	 */
	public function section( $content, $class = null, $attributes = array() ) {

		if ( $class ) {
			$attributes['class'] = $class;
		}

		return $this->tag( 'section', $content, $attributes );

	}

		/**
		 * Gibt ein HTML UL Tag zurück
		 *
		 * @param string $content Inhalt des Tags.
		 * @param string $class CLASS Attribut.
		 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
		 */
	public function ul( $content, $class = null, $attributes = array() ) {

		if ( $class ) {
			$attributes['class'] = $class;
		}

		return $this->tag( 'ul', $content, $attributes );

	}
		/**
		 * Gibt ein HTML LI Tag zurück
		 *
		 * @param string $content Inhalt des Tags.
		 * @param string $class CLASS Attribut.
		 * @param array  $attributes Weitere Attribute im Format Attribut => Wert.
		 */
	public function li( $content, $class = null, $attributes = array() ) {

		if ( $class ) {
			$attributes['class'] = $class;
		}

		return $this->tag( 'li', $content, $attributes );

	}
}
?>
