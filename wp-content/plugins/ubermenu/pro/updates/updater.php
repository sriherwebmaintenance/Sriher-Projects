<?php

if( !defined( 'UBERMENU_UPDATES_URL' ) ){
	define( 'UBERMENU_UPDATES_URL' , 'https://updates.sevenspark.com/ubermenu' );	//TODO
}
if( !defined( 'UBERMENU_AUTO_UPDATES' ) ){
	define( 'UBERMENU_AUTO_UPDATES' , 1 );
}
if( !defined( 'UBERMENU_UPDATES_CHECK_PERIOD' ) ){
	define( 'UBERMENU_UPDATES_CHECK_PERIOD' , 24 );
}

require_once UBERMENU_DIR . 'pro/updates/backup.php';
require_once UBERMENU_DIR . 'pro/updates/updatechecker/plugin-update-checker.php';

// add_action( 'plugins_loaded' , 'ubermenu_load_extension_updates' );
// function ubermenu_load_extension_updates(){
	require_once UBERMENU_DIR . 'pro/updates/extensions.php';
//}



function ubermenu_update_checker(){

	//$username = ubermenu_op( 'envato_username' , 'updates' );
	//Too early to call ubermenu_op
	$settings = ubermenu_updates_get_account_settings();

	if( is_array( $settings ) && isset( $settings['envato_username'] ) ){

		//$uber_update_checker = new PluginUpdateChecker_1_6 (
		$uber_update_checker = new PluginUpdateChecker_3_2 (
			//$url,
			UBERMENU_UPDATES_URL,
		    UBERMENU_FILE,
		    'ubermenu',
		    UBERMENU_UPDATES_CHECK_PERIOD
		);

		//Just make sure we don't dump errors to the end user if the URL fails
		$uber_update_checker->debugMode = false;

		//Increase check interval if update alert is already present
		// $uber_update_checker->throttleRedundantChecks = true;
		// $uber_update_checker->throttledCheckPeriod = 120;

		$uber_update_checker->addQueryArgFilter( 'ubermenu_filter_update_checks' );
		$uber_update_checker->addResultFilter( 'ubermenu_filter_update_results' );

		//$uber_update_checker->checkForUpdates();	//Testing
	}
}
ubermenu_update_checker();


add_action( 'all_admin_notices' , 'ubermenu_updater_check_download_notice' );
function ubermenu_updater_check_download_notice(){
	global $pagenow;

	if( $pagenow == 'update-core.php' ){
		if( ubermenu_op( 'purchase_code' , 'updates' , '' ) ){
			if( !UBERMENU_AUTO_UPDATES ){
				//echo 'no ups';
				$plugin_updates = get_plugin_updates();
				//uberp( $plugin_updates , 3 );
				if( isset( $plugin_updates[ 'ubermenu/ubermenu.php' ] ) ): ?>
					<div class="notice notice-warning">
						<?php //<p><strong>UberMenu</strong> automatic updates are in beta.  To test them out, please see <a target="_blank" href="https://sevenspark.com/docs/ubermenu-3/updates/automatic">Automatic Updates</a>.  Make sure to run a backup first!</p> ?>
						<p><strong>UberMenu</strong> <?php echo sprintf( __( 'automatic updates have been disabled.  To re-enable them, set %s to %s in %s', 'ubermenu' ), '<code>UBERMENU_AUTO_UPDATES</code>', '<code>1</code>', 'wp-config.php' ); ?></p>

					</div>
				<?php endif;
			}
			else{
				$plugin_updates = get_plugin_updates();
				//uberp( $plugin_updates , 3 );
				if( isset( $plugin_updates[ 'ubermenu/ubermenu.php' ] ) ): ?>
					<div class="notice notice-success">
						<p><strong>UberMenu</strong> <?php _e( 'Automatic Update capability has been activated.', 'ubermenu' ); ?>  <a target="_blank" href="https://sevenspark.com/docs/ubermenu-3/updates/automatic"><?php _e( 'Make sure to run a backup first to be safe!', 'ubermenu' ); ?></a></p>
					</div>
				<?php endif;
			}
		}
    }
}

//Add the license key to query arguments.
function ubermenu_filter_update_checks( $queryArgs ) {

    $settings = ubermenu_updates_get_account_settings();
    //unset?

	foreach( $settings as $key => $val ){
		$queryArgs[$key] = urlencode( $val );
	}

	$queryArgs['site_url'] = get_site_url( null , '' , 'http' );
	$queryArgs['ubermenu_version'] = UBERMENU_VERSION;
	$queryArgs['auto_updates'] = UBERMENU_AUTO_UPDATES;

    return $queryArgs;
}

function ubermenu_filter_update_results( $pluginInfo, $result ){

	$notices = array();

	if( isset( $pluginInfo->error ) ){
		//$notices = get_option( UBERMENU_UPDATE_NOTICES_KEY , array() );
		if( !isset( $notices['errors'] ) ) $notices['errors'] = array();
		$notices['errors'][] = $pluginInfo->error;
	}

	update_option( UBERMENU_UPDATE_NOTICES_KEY , $notices );

	return $pluginInfo;

}

$file = basename( dirname( UBERMENU_FILE ) ) . '/' . basename( UBERMENU_FILE );
add_action( "after_plugin_row_$file" , 'ubermenu_plugin_display_notice' , 10 , 2 );

function ubermenu_plugin_display_notice( $file , $plguin_data ){
	$notices = get_option( UBERMENU_UPDATE_NOTICES_KEY , array() );
	if( isset( $notices['errors'] ) ){
		echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange">';
		foreach ( $notices['errors'] as $e ){
			if( is_string( $e ) ) echo "<div class='update-message'>".__('UberMenu update check error:', 'ubermenu' )." $e</div>";
			else uberp( $e,4);
		}
		echo '</td></tr>';
	}
}



function ubermenu_updates_get_account_settings(){
	return get_option( UBERMENU_PREFIX.'updates' , array() );
}

//updates.sevenspark.com/ubermenu




//ubermenu_op( '')

add_filter( 'ubermenu_settings_panel_sections' , 'ubermenu_updates_section' , 100 );
add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_updates_fields' , 100 );
function ubermenu_updates_section( $sections ){
	$prefix = UBERMENU_PREFIX;

	$update_section = array(
						'id' => $prefix.'updates',
						'title' => __( 'Updates', 'ubermenu' ),
						'sub_sections'	=> array(
							'backups'	=> array(
								'title' 	=> __( 'Backups' , 'ubermenu' ),
							),
							'updates'	=> array(
								'title' 	=> __( 'Account Info' , 'ubermenu' ),
							),
						)
					);

	$update_section = apply_filters( 'ubermenu_updates_section' , $update_section );

	$sections[] = $update_section;

	return $sections;
}
function ubermenu_updates_fields( $fields = array() ){
	$section = UBERMENU_PREFIX.'updates';
	$f = array();

	$f[] = array(
			'name'	=> 'backups_header',
			'label' => __( 'Custom Asset Backups' , 'ubermenu' ),
			'desc'	=> __( 'UberMenu will attempt to automatically backup and restore your custom.css and custom.js files when you update', 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'backups',
		);

	$f[] = array(
			'name'	=> 'backup_custom_assets',
			'label'	=> __( 'Backup custom assets' , 'ubermenu' ),
			'desc'	=> __( 'Automatically backup custom.css and custom.js so that they can be restored after updating the plugin', 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'on',
			'group'	=> 'backups',
		);

	$f[] = array(
			'name'	=> 'backup_notice',
			'label' => __( 'Automatic backups status' , 'ubermenu' ),
			'desc'	=> 'ubermenu_field_backup_notice',
			'type'	=> 'html',
			'group'	=> 'backups',
		);


	$f[] = array(
			'name'	=> 'update_settings',
			'label' => __( 'Updates' , 'ubermenu' ), //__( 'Automatic Updates' , 'ubermenu' ),
			'desc'	=> __( 'Enter your Envato info to receive automatic updates', 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'updates',
		);


	if( defined( 'UBERMENU_PACKAGED_THEME' ) ){
		$f[] = array(
				'name'	=> 'package_theme_updates_notice',
				'label'	=> __( 'Packaged Theme Updates Notice', 'ubermenu' ),
				'type'	=> 'html',
				'desc'	=> 'ubermenu_unlicensed_notice',
				'custom_row' => true,
				'group'	=> 'updates',
		);
	}

	$f[] = array(
			'name'	=> 'envato_username',
			'label'	=> __( 'CodeCanyon Username' , 'ubermenu' ),
			'desc'	=> __( 'Enter your Envato Username.  This must match the username on the account you used to purchase the UberMenu license.', 'ubermenu' ),
			'type'	=> 'text',
			'group'	=> 'updates',
		);


	$f[] = array(
			'name'	=> 'api_key',
			'label'	=> __( 'API Key' , 'ubermenu' ),
			'desc'	=> __( 'Enter your Envato API Key.  Create an API key at codecanyon.net by visiting your Settings Page and clicking "API Keys" in the left menu.', 'ubermenu' ),
			'type'	=> 'text',
			'group'	=> 'updates',
		);

	$f[] = array(
			'name'	=> 'purchase_code',
			'label'	=> __( 'Purchase Code' , 'ubermenu' ),
			'desc'	=> __( 'Enter your UberMenu Purchase Code from Envato', 'ubermenu' ) . '. <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-">Find your purchase code</a>',
			'type'	=> 'text',
			'group'	=> 'updates',
		);

	// $f[] = array(
			// 'name'	=> 'video_tutorials',
			// 'label' => __( 'Video Tutorials' , 'ubermenu' ),
			// 'desc'	=> ubermenu_video_tutorials_help(),
			// 'type'	=> 'html',
			// 'group'	=> 'video_tutorials',
		// );


	$fields[$section] = $f;
	return $fields;
}

function ubermenu_unlicensed_notice(){
	?>
	<div id="ubermenu-unlicensed-notice" class="ubermenu-settings-notice ubermenu-settings-notice-large">
		<i class="ubermenu-settings-notice-icon fas fa-exclamation-triangle"></i>
		<strong>UberMenu Plugin License Required</strong>
		<p>This section requires a valid UberMenu plugin license - as opposed to a theme license - which can be <a target="_blank" href="http://codecanyon.net/item/ubermenu-wordpress-mega-menu-plugin/154703?ref=sevenspark">purchased here</a> if you do not already have one.  An UberMenu license code entitles you to updates and support directly from the plugin author; otherwise, support and updates will be provided by <?php echo defined( 'UBERMENU_PACKAGED_THEME' ) ? UBERMENU_PACKAGED_THEME : 'the author of your theme'; ?></p>
		<br/>
		<hr/>
		<br/>

		<?php if( defined( 'UBERMENU_PACKAGED_THEME_UPDATES_NOTICE' ) ): ?>
			<p><strong>Note from <?php echo UBERMENU_PACKAGED_THEME; ?>: </strong><?php echo UBERMENU_PACKAGED_THEME_UPDATES_NOTICE; ?></p>
			<br/>
			<hr/>
			<br/>
		<?php endif; ?>

		<p><a class="ubermenu-reveal-license button button-tertiary">Enter UberMenu License Information</a> <a class="button button-secondary" target="_blank" href="http://codecanyon.net/item/ubermenu-wordpress-mega-menu-plugin/154703?ref=sevenspark">Purchase License</a></p>
	</div>
	<?php
}

function ubermenu_field_backup_notice(){

	$note = $msg = '';

	$custom_dir = trailingslashit( UBERMENU_DIR ).'custom/';

	//Find the Backups directory
	$uploads = wp_upload_dir();

	$uploads_dir = trailingslashit( $uploads['basedir'] );
	$backups_dir = $uploads_dir . 'ubermenu_backups/';

	$uploads_url = trailingslashit( $uploads['baseurl'] );
	$backups_url = $uploads_url . 'ubermenu_backups/';

	if( !is_writable( $uploads_dir ) ){
		//TODO - readd this: <strong>These files will be lost when updating if not backed up first</strong></p>
		$note = '<p>The uploads directory is not writable by the server ( <code>'.$uploads_dir.'</code> ).  </p><p>UberMenu will not automatically be able to back up your <strong><code>custom.css</code></strong> and <strong><code>custom.js</code></strong> if you create them.  Please make this directory writable if you wish to automatically back up these files, otherwise you can back them up and restore manually after plugin update. <p>(If you are not using <code>custom.css</code> or <code>custom.js</code>, you can safely ignore this message)</p>';

		$msg.= '<div id="setting-error-update-write" class="ubermenu-settings-notice ubermenu-settings-notice-large ubermenu-settings-error">' .
				'<i class="ubermenu-settings-notice-icon fas fa-exclamation-triangle"></i>'.
				'<strong>Automatic Backups Not Available</strong>'.
				'<p>'.$note.'</p></div>';
	}
	else{

		$backups_exist = false;

		$custom_css = $backups_dir . 'custom.css';
		$custom_css_url = $backups_url . 'custom.css';
		if( file_exists( $custom_css ) ){

			$backups_exist = true;

			$msg.= '<div class="ubermenu-settings-notice ubermenu-settings-success">' .
				'<i class="ubermenu-settings-notice-icon fas fa-check"></i>'.
				'<strong>custom.css '.__( 'backup available', 'ubermenu' ).'</strong>'.
				' <a href="'.$custom_css_url .'" target="_blank" download="custom.css"><i class="fas fa-download"></i></a>'.
				'</div>';
		}

		$custom_js = $backups_dir . 'custom.js';
		$custom_js_url = $backups_url . 'custom.js';
		if( file_exists( $custom_js ) ){

			$backups_exist = true;

			$msg.= '<div class="ubermenu-settings-notice ubermenu-settings-success">' .
				'<i class="ubermenu-settings-notice-icon fas fa-check"></i>'.
				'<strong>custom.js '.__( 'backup available', 'ubermenu' ).'</strong>'.
				' <a href="'.$custom_js_url .'" download="custom.js" target="_blank"><i class="fas fa-download"></i></a>'.
				'</div>';
		}



		if( file_exists( $backups_dir ) ){

			if( file_exists( $custom_dir . 'custom.css' ) && !is_writable( $backups_dir . 'css' ) ){
				$msg.= '<div class="ubermenu-settings-notice ubermenu-settings-error">' .
					'<i class="ubermenu-settings-notice-icon fas fa-exclamation-triangle"></i>'.
					'<strong>Daily CSS backups not writable</strong>'.
					' <p>'.__( 'UberMenu attempts to save daily backups, but this directory is not writable.', 'ubermenu' ).' <code>'.$backups_dir.'css/</code></p>'.
					'</div>';
			}

			if( file_exists( $custom_dir . 'custom.js' ) && !is_writable( $backups_dir . 'js' ) ){
				$msg.= '<div class="ubermenu-settings-notice ubermenu-settings-error">' .
					'<i class="ubermenu-settings-notice-icon fas fa-exclamation-triangle"></i>'.
					'<strong>'.__( 'Daily JS backups not writable', 'ubermenu' ).'</strong>'.
					' <p>'.__( 'UberMenu attempts to save daily backups, but this directory is not writable.', 'ubermenu' ).' <code>'.$backups_dir.'js/</code></p>'.
					'</div>';
			}

		}



		if( !$backups_exist ){

			if( file_exists( $custom_dir.'custom.css' ) ||
				file_exists( $custom_dir.'custom.js' )){
				$msg.= '<div class="ubermenu-settings-notice ubermenu-settings-success"><i class="fas fa-info-circle"></i> '. __( 'No backups found.  If this message is present after refreshing, please check that your /uploads directory is writable.', 'ubermenu' ).'</div>';
			}
			else{
				$msg.= '<div class="ubermenu-settings-notice ubermenu-settings-success"><i class="fas fa-info-circle"></i> '. __( 'No custom assets in use.', 'ubermenu' ).'</div>';
			}
		}

	}


	if( UBERMENU_AUTO_UPDATES && ubermenu_op( 'purchase_code' , 'updates' , '' ) ){
		$msg.= '<br/><br/><p class="notice notice-success clear">'.__( 'UberMenu Automatic Updates capability has been activated', 'ubermenu' ).'</p>';
	}






	return $msg;
}




//This only runs in the admin because this file is only loaded in the admin
function ubermenu_update_db_check() {
    if( get_site_option( UBERMENU_VERSION_KEY ) != UBERMENU_VERSION ){
        ubermenu_run_update();
    }
}
add_action( 'plugins_loaded', 'ubermenu_update_db_check' );

function ubermenu_run_update(){

	//Restore custom.css and custom.js if they exist
	ubermenu_restore_custom_assets();

	//Regenerate custom styles and custom prefix stylesheets
	//add_action( 'wp' , 'ubermenu_regenerate_custom_generated' ); //only runs on backend because this file is not loaded on front end
	add_action( 'in_admin_header' , 'ubermenu_regenerate_custom_generated' );

	//Update the version to the current version
	update_site_option( UBERMENU_VERSION_KEY , UBERMENU_VERSION );
}

function ubermenu_regenerate_custom_generated(){

	//Invalidate Customizer settings so they'll be regenerated
	ubermenu_reset_generated_styles();

	//Regenerate custom prefix files
	ubermenu_booster_prefix_generate_all();
}
//ubermenu_run_update(); //TODO REMOVE
