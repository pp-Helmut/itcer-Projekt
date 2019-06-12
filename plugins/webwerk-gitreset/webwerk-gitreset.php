<?php
/*
Plugin Name:  Webwerk Git Reset
Description:  Setzt das Verzeichnis /wp-content auf den letzten Stand des Git Repositorys zurück.
Version:      1.0
Author:       Andreas Wagner
*/
defined( 'ABSPATH' ) or die();
function webwerkgr_options_page_html() {

	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "webwerkgr_options"
			settings_fields( 'webwerkgr' );
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections( 'webwerkgr' );
			// output save settings button
			submit_button( 'Anwenden' );
			?>
		</form>
	</div>
	<?php
}

function webwerkgr_options_page() {

	add_menu_page( 'Git Reset',
		'Git Reset',
		'manage_options',
		'webwerkgr',
		'webwerkgr_options_page_html',
		'dashicons-backup',
	20 );
}
add_action( 'admin_menu', 'webwerkgr_options_page' );

function webwerkgr_settings_init() {
	// register a new setting for "reading" page
	register_setting( 'webwerkgr', 'webwerkgr_setting_name' );

	// register a new section in the "reading" page
	add_settings_section(
		'webwerkgr_settings_section',
		'Git Reset bestätigen',
		'webwerkgr_settings_section_cb',
		'webwerkgr'
	);

	// register a new field in the "webwerkgr_settings_section" section, inside the "reading" page
	add_settings_field(
		'webwerkgr_settings_field',
		'hier "reset" eintippen:',
		'webwerkgr_settings_field_cb',
		'webwerkgr',
		'webwerkgr_settings_section'
	);
}

/**
 * register webwerkgr_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'webwerkgr_settings_init' );

/**
 * callback functions
 */

// section content cb
function webwerkgr_settings_section_cb() {
	echo '<p>Achtung! Lokale Änderungen an Dateien werden unwiderruflich gelöscht!</p>';
}

// field content cb
function webwerkgr_settings_field_cb() {
	$sh = '';
	// get the value of the setting we've registered with register_setting()
	$setting = get_option( 'webwerkgr_setting_name' );
	if ( 'reset' === $setting ) {
		$sh = exec( 'cd ' . escapeshellarg( ABSPATH ) . '/wp-content && git fetch origin && git reset --hard origin/master' );
	}
	update_option( 'webwerkgr_setting_name', null );
	$setting = get_option( 'webwerkgr_setting_name' );

	// output the field
	?>
	<input type="text" name="webwerkgr_setting_name" value="">
	<?php
	if ( $sh ) {
		echo '<pre>' . $sh . '</pre>';
	}
}
