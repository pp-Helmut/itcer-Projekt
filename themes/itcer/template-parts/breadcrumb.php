<?php

// Zeile 66: Anpassung für BBSB!
function get_breadcrumb() {
	global $post;

	$breadcumb_markup = '<nav class="breadcrumb"><span class="show-for-xxlarge">Sie sind hier: </span>
    <ul id="breadcrumb-menu" itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
	$li1              = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
	$li2              = "</li>\n";

	$breadcrumb_ids = array();

	if ( ! is_home() && ! is_front_page() || is_paged() ) {
		$home_link         = get_option( 'home' );
		$breadcumb_markup .= $li1 . '<meta itemprop="position" content="1"><a itemprop="item" href="' . get_option( 'home' ) . '"><span itemprop="name">Startseite</span></a>' . $li2;
		if ( is_page() && ! $post->post_parent ) {
			$breadcrumb_ids[]  = $post->ID;
			$breadcumb_markup .= $li1 . '<meta itemprop="position" content="2"><span itemprop="name">' . get_the_title() . '</span>' . $li2;
		} elseif ( is_page() && $post->post_parent ) {
			$parent_id        = $post->post_parent;
			$breadcrumb_ids[] = $post->ID;
			$breadcrumbs      = array();
			while ( $parent_id ) {
				$page             = get_page( $parent_id );
				$breadcrumbs[]    = '<a itemprop="item" href="' . get_permalink( $page->ID ) . '"><span itemprop="name">' . get_the_title( $page->ID ) . '</span></a>';
				$breadcrumb_ids[] = $page->ID;
				$parent_id        = $page->post_parent;
			}
			$breadcrumbs = array_reverse( $breadcrumbs );
			global $i;
			$i = 2;
			foreach ( $breadcrumbs as $crumb ) {
				$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . ( $i++ ) . '">' . $crumb . $li2;
			}
			$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i . '"><span itemprop="name">' . get_the_title() . '</span>' . $li2;
		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$i                 = 2;
				$post_type         = get_post_type_object( get_post_type() );
				$slug              = $post_type->rewrite;
				$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . ( $i++ ) . '"><a itemprop="item" href="' . $home_link . '/' . $slug['slug'] . '"><span itemprop="name">' . $post_type->labels->singular_name . '</span></a>' . $li2;
				$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i . '"><span itemprop="name">' . get_the_title() . '</span>' . $li2;
			} else {
				$i = 2;
				// $cat        = get_the_category();
				// $cat        = $cat[0];
				// $this_cat   = get_category( $cat );
				// $parent_cat = get_category( $this_cat->parent );
				// if ( $this_cat->parent != 0 ) {
				// $categories = array();
				// while ( $this_cat->parent ) {
				// $parent_cat   = get_category( $this_cat->parent );
				// $categories[] = '<a itemprop="item" href="' . get_category_link( $parent_cat->term_id ) . '"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . '/img/icons.svg#arrow-right"></use></svg><span itemprop="name">' . $parent_cat->name . '</span></a>';
				// $this_cat     = $parent_cat;
				// }
				//
				// $categories = array_reverse( $categories );
				// foreach ( $categories as $category ) {
				// $breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i++ . '">' . $category . $li2;
				// }
				// }
				// $breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i++ . '"><a itemprop="item" href="' . get_category_link( $cat->term_id ) . '"><span itemprop="name"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . '/img/icons.svg#arrow-right"></use></svg>' . $cat->name . '</span></a>' . $li2;
				//
				//
				// Anpassung für BBSB!
				$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . ( $i++ ) . '"><a itemprop="item" href="' . esc_url( home_url( '/aktuelles/' ) ) . '"><span itemprop="name"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . '/img/icons.svg#arrow-right"></use></svg>Aktuelles</span></a>' . $li2;
				$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . ( $i++ ) . '"><a itemprop="item" href="' . esc_url( home_url( '/aktuelle-beitraege/' ) ) . '"><span itemprop="name"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . '/img/icons.svg#arrow-right"></use></svg>Aktuelle Beiträge</span></a>' . $li2;

				$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i . '"><span itemprop="name">' . get_the_title() . '</span>' . $li2;
			}
		} elseif ( is_category() ) {
			$i = 2;
			global $wp_query;
			$cat_obj    = $wp_query->get_queried_object();
			$this_cat   = $cat_obj->term_id;
			$this_cat   = get_category( $this_cat );
			$parent_cat = get_category( $this_cat->parent );
			if ( $this_cat->parent != 0 ) {
				$categories = array();
				while ( $this_cat->parent ) {
					$parent_cat   = get_category( $this_cat->parent );
					$categories[] = '<a itemprop="item" href="' . get_category_link( $parent_cat->term_id ) . '"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . '/img/icons.svg#arrow-right"></use></svg><span itemprop="name">' . $parent_cat->name . '</span></a>';
					$this_cat     = $parent_cat;
				}

				$categories = array_reverse( $categories );
				foreach ( $categories as $category ) {
					$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . ( $i++ ) . '">' . $category . $li2;
				}
			}
			$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i . '"><span itemprop="name">' . single_cat_title( '', false ) . '</span>' . $li2;
		} elseif ( is_search() ) {
			$i                 = 2;
			$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i . '"><span itemprop="name">Suchergebnisse für "' . get_search_query() . '"</span>' . $li2;
		} elseif ( is_404() ) {
			$i                 = 2;
			$breadcumb_markup .= $li1 . '<meta itemprop="position" content="' . $i . '"><span itemprop="name">Seite nicht gefunden...</span>' . $li2;
		}
	} elseif ( is_front_page() ) {
		$breadcumb_markup .= $li1 . '<meta itemprop="position" content="1"><span itemprop="name">Startseite</span>' . $li2;
	}
	$breadcumb_markup .= '</ul></nav>';

	return array(
		'markup' => $breadcumb_markup,
		'ids'    => $breadcrumb_ids,
	);
}

function the_breadcrumb( $markup = null ) {

	if ( $markup ) {
		echo $markup;
	} else {
		echo get_breadcrumb()['markup'];
	}

}
