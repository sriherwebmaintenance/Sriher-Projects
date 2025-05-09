<?php

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function ubermenu_gb_block_init() {
	register_block_type( __DIR__ . '/build',
		array(
			'render_callback' => 'ubermenu_gb_render_menu',
			'attributes' => array(
					'menuId' => array(
							'type' => 'integer',
							'default' => 0,
					),
					'configId' => array(
							'type' => 'string',
							'default' => 'main',
					),
					'className' => array(
						'type'	=> 'string',
						'default' => '',
					)
			)
		)
	);
}
add_action( 'init', 'ubermenu_gb_block_init' );

function ubermenu_gb_render_menu( $attributes, $content ) {

	$menuId = $attributes['menuId'];
	$configId = $attributes['configId'];

	// print_r( $attributes );
	// echo $menuId;

	//$menu_ops = ubermenu_get_nav_menu_ops();

	// if( $menuId === 0 ){
	// 	$menuId =
	// }

	if( !$menuId ) return '<div class="ubermenu-admin-notice"><i class="ubermenu-admin-notice-icon fas fa-lightbulb"></i> <strong>Please select a menu</strong> in the block settings to the right</div>';

	if( function_exists( 'ubermenu' ) ){
		//Use output buffering because content printed before or after wp_nav_menu, such as notices, may echo rather than return
		ob_start();
		ubermenu( $configId , array( 'menu' => $menuId /*, 'echo' => false */ ) );
		$um = ob_get_clean();
		// $um.= '<script type="text/javascript">alert("come on now");</script>';
		return $um;
		//return $um . 'UberMenu ' . "MenuId: $menuId" . " | ConfigId: $configId"; //TODO
	}

//return 'UberMenu ' . "MenuId: $menuId" . " | ConfigId: $configId";
}



/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function ubermenu_gb_editor_assets() {
	// Scripts.
	// wp_enqueue_script(
	// 	'ubermenu-gb-block-js', // Handle.
	// 	plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
	// 	array( 'wp-blocks', 'wp-i18n', 'wp-element' , 'wp-components', 'wp-block-editor' ), // Dependencies, defined above.
	// 	// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
	// 	true // Enqueue the script in the footer.
	// );

	//Get list of menus, with 0 => select menu as the default
	$menus = wp_get_nav_menus( array('orderby' => 'name') );
	$menu_ops = array( 0 => 'Select Menu' );
	foreach( $menus as $menu ) $menu_ops[$menu->term_id] = $menu->name;

	//Get list of UberMenu Configurations, with Main as the default
	$config_ops = array( 'main' => 'Main Configuration' );
	$instances = ubermenu_get_menu_instances();
	foreach( $instances as $config_id ){
		$config_ops[$config_id] = $config_id;
	}

	wp_localize_script(
		// 'ubermenu-gb-block-js',
		'ubermenu-ubermenu-block-editor-script',
		'ubermenu_block',
		array(
			'menu_options' => $menu_ops,
			'config_options' => $config_ops
		)
	);

	// Styles.
	// wp_enqueue_style(
	// 	'ubermenu-block-editor-css', // Handle.
	// 	plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
	// 	array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
	// 	// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: filemtime — Gets file modification time.
	// );

	ubermenu_load_assets();
} 

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'ubermenu_gb_editor_assets' );