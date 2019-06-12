<?php
/**
 * Aktuelles (Einzel)
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
		<div class="page-wrap news-single">
		<?php
		$a_days   = [ 'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag' ];
		$a_months = [ 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember' ];

		$img      = '';
		$date     = $a_days[ (int) get_the_date( 'w' ) ] . ', ' . get_the_date( 'd. ' ) . $a_months[ (int) get_the_date( 'n' ) - 1 ] . get_the_date( ' Y' );
		$media_id = get_post_thumbnail_id( $post->ID );
		if ( $media_id ) {
			$img = '<div class="n-img">' . get_picture_tag( $media_id, array( 0 => 'news' ) ) . '</div>';
		}

		the_post();

		$news_content .= '<div class="n-container">
		<div class="n-date">' . $date . '</div>
			<h1>' . get_the_title() . '</h1>
			' . $img . '
			<div class="n-content">
				<div class="n-text">' . get_the_content() . '</div>
			</div>
		</div>';

		echo $news_content;
	?>
	</div>
</div>
</main>

<?php
get_footer();
