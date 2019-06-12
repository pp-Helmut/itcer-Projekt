<?php
/**
 * category
 *
 * @package BBSB
 **/

get_header();
?>

<main class="main content" id="main" content="true">
			<?php
			the_title( '<h1>', '</h1>' );
			the_post();
			the_content();
			?>

</main>
<?php
get_footer(); ?>
