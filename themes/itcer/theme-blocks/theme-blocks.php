<?php

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }

 add_action( 'enqueue_block_editor_assets', 'ww_enqueue_extend_block_assets' );

 function ww_enqueue_extend_block_assets() {
     // Enqueue our script
     wp_enqueue_script(
         'ww-extend-block-js',
         esc_url( get_theme_file_uri( 'theme-blocks/dist/ww-extend-block.js', __FILE__ ) ),
         array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
         '1.0.0',
         true // Enqueue the script in the footer.
     );
 }
