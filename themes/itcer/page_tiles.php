<?php
/**
 * Template Name: Kacheln
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
 <?php
 the_post();
 the_content();
 ?>
 </main>
 <?php
 get_footer(); ?>
