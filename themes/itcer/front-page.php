<?php
/**
 * Index
 *
 * @package BBSB
 **/

 get_header();
 ?>

 <main class="main content" id="main" content="true">
   <div class="section">
     <div class="page-wrap">
 		<?php
 		   the_breadcrumb();
 		?>
 <?php
 	// the_title( '<h1>', '</h1>' );
   the_post();
   the_content();
 ?>
 </div>
 </div>
 </main>
 <?php
 get_footer(); ?>
