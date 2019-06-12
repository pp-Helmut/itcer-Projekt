<?php
/**
 * Template Name: Veranstaltungen
 *
 * @package BBSB
 **/

get_header();

$vars = filter_input_array(
	INPUT_POST, array(
		'month' => FILTER_SANITIZE_NUMBER_INT,
		'cat'   => FILTER_SANITIZE_NUMBER_INT,
		'venue' => FILTER_SANITIZE_NUMBER_INT,
		'tag'   => FILTER_SANITIZE_NUMBER_INT,
	)
);
?>
<main class="main content" id="main" content="true">
	<div class="section white">
		<div class="page-wrap">
		<?php
		the_breadcrumb();
		?>
		</div>
	</div>
	<div class="section white">
		<div class="page-wrap event-scheme">
		<?php
		the_title( '<h1>', '</h1>' );
		?>
		<form id="events-filter" class="events-filter" action="" method="post">
			<select class="select" name="month">

				<option value="">Monat</option>
				<?php
				$a_months = [ 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember' ];

				foreach ( $a_months as $key => $value ) {
					if ( $key + 1 < intval( date( 'n' ) ) ) {
						$year = date( 'Y', strtotime( '+1 year' ) );
					} else {
						$year = date( 'Y' );
					}
					echo '<option value="' . esc_attr( $key + 1 ) . '"' . ( (int) $vars['month'] === $key + 1 ? ' selected' : null ) . '>' . esc_html( $value . ' ' . $year ) . '</option>';
				}
					?>

		</select>
			<select class="select" name="cat">
			<option value="">Bezirksgruppe</option>
				<?php
				$terms_cat     = get_terms(
					array(
						'taxonomy' => 'tribe_events_cat',
					)
				);
				$terms_cat_arr = [];
				foreach ( $terms_cat as $term ) {
					$terms_cat_arr[ $term->term_id ] = $term->name;
				}
				asort( $terms_cat_arr );
				foreach ( $terms_cat_arr as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '"' . ( (int) $vars['cat'] === $key ? ' selected' : null ) . '>' . esc_html( $value ) . '</option>';
				}
					?>
			</select>
			<select class="select" name="tag">
				<option value="">Thema</option>
				<?php
				$terms_tag     = get_terms(
					array(
						'post_type' => 'tribe_events',
						'taxonomy'  => 'post_tag',
					)
				);
				$terms_tag_arr = [];
				foreach ( $terms_tag as $term ) {
					$terms_tag_arr[ $term->term_id ] = $term->name;
				}
				asort( $terms_tag_arr );
				foreach ( $terms_tag_arr as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '"' . ( (int) $vars['tag'] === $key ? ' selected' : null ) . '>' . esc_html( $value ) . '</option>';
				}
					?>
			</select>
			<select class="select r2" name="venue">
				<option value="">Ort</option>
				<?php
				$venues     = tribe_get_venues();
				$venues_arr = [];
				foreach ( $venues as $venue ) {
					$venues_arr[ $venue->ID ] = $venue->post_title;
				}
				asort( $venues_arr );
				foreach ( $venues_arr as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '"' . ( (int) $vars['venue'] === $key ? ' selected' : null ) . '>' . esc_html( $value ) . '</option>';
				}
					?>
			</select>
		</form>
	<?php
	global $post;

	if ( function_exists( 'tribe_get_events' ) ) {
		$args = array(
			'start_date'     => date( 'Y-m-d H:i' ),
			'eventDisplay'   => 'custom',
			'posts_per_page' => -1,
		);

		if ( $vars['month'] ) {
			if ( $vars['month'] < intval( date( 'n' ) ) ) {
				$year = date( 'Y', strtotime( '+1 year' ) );
			} else {
				$year = date( 'Y' );
			}
			$date               = $year . '-' . $vars['month'] . '-1';
			$args['start_date'] = $date;
		}

		if ( $vars['cat'] ) {
			$term                     = get_term( $vars['cat'], 'tribe_events_cat' );
			$args['tribe_events_cat'] = $term->slug;
		}

		if ( $vars['tag'] ) {
			$term             = get_term( $vars['tag'], 'post_tag' );
			$args['post_tag'] = $term->slug;
		}

		if ( $vars['venue'] ) {
			$args['venue'] = $vars['venue'];
		}
		$events = tribe_get_events( $args );
	}

	if ( $events ) {

		echo '<div class="event-items">';

		foreach ( $events as $post ) {
			$class = '';

			setup_postdata( $post );
			$media_id = get_post_thumbnail_id( $post->ID );
			$cats     = get_the_terms( $post->ID, 'tribe_events_cat' );
			$tags     = get_the_terms( $post->ID, 'post_tag' );
			$i        = 0;
			if ( $tags ) {
				$count = count( $tags );
				if ( 1 === $count ) {
					$tag_content = 'Thema: ';
				} elseif ( 1 < $count ) {
					$tag_content = 'Themen: ';
				}
				foreach ( $tags as $tag ) {
					$i++;
					if ( 1 < $i ) {
						if ( $i === $count ) {
							$tag_content .= ' und ';
						} else {
							$tag_content .= ', ';
						}
					}
					$tag_content .= $tag->name;
				}
			}

			echo '<article class="event-item"><a href="' . esc_url( get_permalink() ) . '" class="e-link">';
			echo '<h2 class="e-head">' . get_the_title() . '</h2>';
			echo '<div class="e-text">';
			echo '<div class="e-date"><strong>Wann:</strong>' . esc_html( tribe_get_start_date( $post, true, 'l' ) . ', den ' . tribe_get_start_date( $post, true, 'd.m.Y' ) . ' um ' . tribe_get_start_date( $post, true, 'H:i' ) . ' Uhr' ) . '</div>';
			echo '<div class="e-venue"><strong>Ort:</strong> ' . esc_html( tribe_get_venue() ) . '</div>';
			echo '<div class="e-category"><strong>Bezirksgruppe:</strong> ' . esc_html( $cats[0]->name ) . '</div>';
			echo '<div class="e-teaser">' . excerpt( 14 ) . '</div>';
			echo '<div class="e-tag">' . esc_html( $content ) . '</div>';
			echo '</div>';
			if ( $media_id ) {
				echo '<div class="e-img"><div>' . get_picture_tag( $media_id, array( 0 => 'events' ) ) . '</div></div>';
			}
			echo '</a></article>';
		}

		echo '</div>';

	} else {
		echo '<h2>Keine Veranstaltungen gefunden.</h2>';
	}
	?>
	</div>
</div>
</main>

<?php
get_footer();
