<?php
/**
 * Single Event Template
 *
 * A single event complete template, divided in smaller template parts.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/single-event-blocks.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7
 */

?>
<main class="main content" id="main" content="true">
	<?php
	$markup = '<div class="section white"><div class="page-wrap"><nav class="breadcrumb"><span class="show-for-xxlarge">Sie befinden sich hier: </span><ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList"><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="1"><a itemprop="item" href="' . get_option( 'home' ) . '"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . '/img/icons.svg#arrow-right"></use></svg><span itemprop="name">Startseite</span></a></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="2"><a itemprop="item" href="' . home_url( '/veranstaltungen/' ) . '"><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . esc_url( get_template_directory_uri() ) . '/img/icons.svg#arrow-right"></use></svg><span itemprop="name">Veranstaltungen</span></a></li><li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="3"><span itemprop="name">' . get_the_title() . '</span></li></ul></nav></div></div>';
	the_breadcrumb( $markup );
	$cats = get_the_terms( $post->ID, 'tribe_events_cat' );
	$tags = get_the_terms( $post->ID, 'post_tag' );
	$i    = 0;
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
	$media_id = get_post_thumbnail_id( $post->ID );
	?>
	<div class="section">
		<div class="page-wrap event-single">
		<div class="e-container">
		<h1 class="e-head"><?php echo get_the_title(); ?></h1>
		<div class="e-text">
			<div class="e-date"><strong>Wann:</strong><?php echo esc_html( tribe_get_start_date( $post, true, 'l' ) . ', den ' . tribe_get_start_date( $post, true, 'd.m.Y' ) . ' um ' . tribe_get_start_date( $post, true, 'H:i' ) . ' Uhr' ); ?></div>
			<div class="e-venue"><strong>Ort:</strong> <?php echo esc_html( tribe_get_venue() ); ?></div>
			<div class="e-category"><strong>Bezirksgruppe:</strong> <?php echo esc_html( $cats[0]->name ); ?></div>
			<div class="e-tag"><?php echo esc_html( $tag_content ); ?></div>
			<?php
			the_post();
			the_content();
			?>
			</div>
			<?php
			if ( $media_id ) {
				echo '<div class="e-img">' . get_picture_tag( $media_id, array( 0 => 'events' ) ) . '</div>';
			}
				?>
		</div>
	</div>
	</div>
</main>
