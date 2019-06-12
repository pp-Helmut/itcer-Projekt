<?php if ( has_post_thumbnail() ) : ?>

<?
	$thumbnail_id = get_post_thumbnail_id( );
?>

		<div class="hero">
			<?php
			  the_post_thumbnail();
			?>
			<!-- <?php echo get_picture_tag( $thumbnail_id, array( 0 => 'hero-small', 641 => 'hero-medium', 769 => 'hero-large', 1025 => 'hero-max' ), '', '', false, get_mobile_hero_picturedata( $thumbnail_id ) ); ?> -->
		</div>
<?php endif; ?>
