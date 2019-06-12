<?php
/**
 * Template Name: Aktuelles
 *
 * @package BBSB
 **/

get_header();
?>
<main class="main content" id="main" content="true">
	<div class="section white">
		<div class="page-wrap">
		<?php
		the_breadcrumb();
		?>
		</div>
	</div>
	<div class="section">
		<div class="page-wrap news-scheme">

		<?php
		the_title('<h1>', '</h1>');
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => 100,
		);

		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) {

			$a_days       = [ 'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag' ];
			$a_months     = [ 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember' ];
			$news_content = '<div class="news-box">';

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$img      = '';
				$date     = $a_days[ (int) get_the_date( 'w' ) ] . ', ' . get_the_date( 'd. ' ) . $a_months[ (int) get_the_date( 'n' ) - 1 ] . get_the_date( ' Y' );
				$media_id = get_post_thumbnail_id( $post->ID );
				if ( $media_id ) {
					$img = '<div class="n-img"><div>' . get_picture_tag( $media_id, array( 0 => 'news_small' ) ) . '</div></div>';
				}

				$news_content .= '<article class="news-item"><a class="n-link" href="' . esc_url( get_permalink() ) . '">';
				$news_content .= '<div class="n-date">' . esc_html( $date ) . '</div>';
				$news_content .= '<h2 class="n-head">' . get_the_title() . '</h2>';
				$news_content .= $img;
				$news_content .= '<div class="n-content">
					<p>' . get_the_excerpt() . '</p></div></a>';
			}

			wp_reset_postdata();
		} else {
			$news_content .= '<p>Keine Beiträge.</p>';
		}

		$news_content .= '</div>';

		echo $news_content;
	?>
	</div>
</div>
</main>

<?php
get_footer();
