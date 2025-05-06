<?php
/*------- Theme Supports ---------*/
add_action( 'after_setup_theme', 'res_theme_support' );
function res_theme_support() {
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    // add_theme_support( 'post-formats' );
    add_theme_support('post-thumbnails');
    add_post_type_support('page', 'excerpt');
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    // Woocommerce Support
    add_theme_support( 'woocommerce' );
    //Woocommerce gallery support
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    /*** end ***/
}
@ini_set( 'upload_max_size' , '1G' );
@ini_set( 'post_max_size', '1G');
@ini_set( 'max_execution_time', '3000' );
/* Flush rewrite rules for custom post types. */
add_action( 'after_switch_theme', 'awpr_flush_rewrite_rules' );
function awpr_flush_rewrite_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/*------------- Disallow Backend Editting ----------------*/
define( 'DISALLOW_FILE_EDIT', true );

/*-------------- Disable XMLRPC ---------------------*/
add_filter('xmlrpc_enabled', '__return_false');

/*------------- Hide Wordpress Version Generator ---------------*/
add_filter('the_generator', 'version');
function version() {
  return '';
}

/*----------- Remove WP-Embed script ---------------*/
function disable_embeds_init() {
    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');
    // Turn off oEmbed auto discovery.
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
add_action('init', 'disable_embeds_init', 9999);

/*------- Remove emoji script and css ------*/
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );





/*---------------acf Settings ----------*/
include_once( 'includes/acf-pro/acf.php' );

add_filter('acf/settings/path', 'my_acf_settings_path');
function my_acf_settings_path( $path ) {
	// update path
	$path = get_bloginfo('stylesheet_directory') . '/includes/acf-pro/';
	// return
	return $path;
}

add_filter('acf/settings/dir', 'my_acf_settings_dir');
function my_acf_settings_dir( $dir ) {
	$dir = get_stylesheet_directory_uri() . '/includes/acf-pro/';

	return $dir;
}

/**
 * acf options page
*/
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title'  => 'Theme General Settings',
		'menu_title'  => 'Theme Options',
		'menu_slug'   => 'theme-general-settings',
		'capability'  => 'edit_posts',
		'redirect'    => false
	));
    acf_add_options_sub_page(array(
		'page_title'  => 'Common Page titles',
		'menu_title'  => 'Common Page titles',
		'parent_slug' => 'theme-general-settings',
   ));
	acf_add_options_sub_page(array(
		'page_title'  => 'Career detail page',
		'menu_title'  => 'Career detail page',
		'parent_slug' => 'theme-general-settings',
   ));
	acf_add_options_sub_page(array(
	 	'page_title'  => 'Social Media',
	 	'menu_title'  => 'Social Media',
	 	'parent_slug' => 'theme-general-settings',
    ));
    acf_add_options_sub_page(array(
		'page_title'  => 'Miscellaneous',
		'menu_title'  => 'Miscellaneous',
		'parent_slug' => 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title'  => 'Footer',
		'menu_title'  => 'Footer',
		'parent_slug' => 'theme-general-settings',
	));
	acf_add_options_sub_page(array(
		'page_title'  => 'Gallery',
		'menu_title'  => 'Gallery',
		'parent_slug' => 'theme-general-settings',
	));
}

/**
 * Remove acf sttings from backend
*/
//add_filter( 'acf/settings/show_admin', '__return_false' );

/*---------- Faculty Section---------------*/

include_once('includes/faculty-module-functions/compare-image.php');
include_once('includes/faculty-module-functions/notification.php');
include_once('includes/faculty-module-functions/hod-vc-disapproval.php');
include_once('includes/faculty-module-functions/dashboard-styling.php');
include_once('includes/faculty-module-functions/user-taxonomy.php');
include_once('includes/faculty-module-functions/role-selector.php');
include_once('includes/faculty-module-functions/profile-check.php');
include_once('includes/faculty-module-functions/overall-approval.php');
// include_once('includes/faculty-module-functions/users-list.php');
// include_once('includes/faculty-module-functions/department.php');


/*------- Excerpt -------*/
function new_excerpt_length($length) {
    return 200;  // length used for media press release
}
add_filter('excerpt_length', 'new_excerpt_length');

function trim_excerpt($more) {
    return '...';
}
add_filter('excerpt_more', 'trim_excerpt');
/*** end ***/

/*----- svg support -----*/
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/*----- custom post archive -----*/
add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
  if(is_category() || is_tag() || is_month() || is_day() || is_year()) {
    $post_type = get_query_var('post_type');
    if($post_type)
        $post_type = $post_type;
    else
        $post_type = get_post_types();
    $query->set('post_type',$post_type);
    return $query;
  }
}

/*----- Wp Login Page Logo Link ------*/
add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
	return get_home_url();
}

/*------ Wp login Page Logo Change ---------*/
add_action( 'login_enqueue_scripts', 'my_login_logo' );
function my_login_logo() { 
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url('<?php echo get_theme_file_uri(); ?>/images/logo.svg');
            padding-bottom: 0;
            background-size: 311px auto;
            height: 74px;
            width: 311px;
        }
		body.login{
			background: #105C8E;
    background: linear-gradient(90deg, rgb(32 136 187) 0%, rgb(180 223 241 / 44%) 44%, rgb(18 103 145 / 75%) 100%);
		}
    </style>
<?php }

/*------- Enqueue Scripts & Styles --------*/
add_action('wp_enqueue_scripts', 'theme_custom_styles');
function theme_custom_styles() {
    wp_register_style('tabs', get_template_directory_uri() . '/css/tabs.css', array(), '1.0', 'all');
}

add_action('wp_enqueue_scripts', 'theme_scripts_styles');
function theme_scripts_styles() {
    wp_deregister_script('jquery');
    wp_deregister_script('jquery-migrate');
    wp_register_script('jquery', get_template_directory_uri() . '/js/jquery.js');
    wp_register_script('jquery-migrate', get_template_directory_uri() . '/js/migrate.js');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-migrate');

    //Custom
	wp_register_script('Swiper', get_template_directory_uri() . '/js/swiper-bundle.min.js', array('jquery'), '', true);
	wp_register_script('sticky', get_template_directory_uri() . '/js/sticky.js', array('jquery'), '', true);
    wp_register_script( 'common_scripts', get_template_directory_uri() . '/js/common.js?version=1.5', array('jquery'), '', true );
    wp_register_script('magnificPopup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array('jquery'), '', true);
    wp_register_script('validate', get_template_directory_uri() . '/js/validate.js', array('jquery'), '', true);
    wp_register_script('aos', get_template_directory_uri() . '/js/aos.js', array('jquery'), '', true);
    wp_register_script('ticker', get_template_directory_uri() . '/js/jquery.simpleTicker.js', array('jquery'), '', true);
	wp_register_script('gsap', get_template_directory_uri() . '/js/gsap.min.js', array('jquery'), '', true);
    wp_register_script('tabs', get_template_directory_uri() . '/js/responsiveTabs.js', array('jquery'), '', true);
	wp_register_script('easyResponsiveTabs', get_template_directory_uri() . '/js/easyResponsiveTabs.js', array('jquery'), '', true);
	wp_register_script('ScrollTrigger', get_template_directory_uri() . '/js/ScrollTrigger.min.js', array('jquery'), '', true);
    wp_register_script('split', get_template_directory_uri() . '/js/split.min.js', array('jquery'), '', true);
    wp_register_script('slick', get_template_directory_uri() . '/js/slick.min.js', array('jquery'), '', true);

	wp_register_script('gsap-animate', get_template_directory_uri() . '/js/gsap-animate.js', array('jquery'), '', true);

	
	wp_enqueue_script('gsap');
	wp_enqueue_script('ScrollTrigger');
    wp_enqueue_script('split');

    wp_enqueue_script('magnificPopup');
    wp_enqueue_script('aos');
    wp_enqueue_script('common_scripts');

}

/*------ Remove script attributes -------*/
add_filter('style_loader_tag', 'mtheme_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'mtheme_remove_type_attr', 10, 2);
function mtheme_remove_type_attr($tag, $handle) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}

/**
 * Adding Defer attribute to scripts
*/
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);
function add_defer_attribute($tag, $handle) {
    // add script handles to the array below
    $scripts_to_defer = array('google_map');
    
    foreach($scripts_to_defer as $defer_script) {
       if ($defer_script === $handle) {
          return str_replace(' src', ' defer="defer" src', $tag);
       }
    }
    return $tag;
}

/*------- remove css and js versions --------*/
function vc_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'vc_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'vc_remove_wp_ver_css_js', 9999 );

/*-------- Move header scripts to footer ------*/
function remove_head_scripts() { 
    remove_action('wp_head', 'wp_print_scripts'); 
    remove_action('wp_head', 'wp_print_head_scripts', 9); 
    remove_action('wp_head', 'wp_enqueue_scripts', 1);

    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5); 
} 
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );

/*------ Register Navigation Menu -------*/
register_nav_menus( 
    array(
        'header_menu' => 'Header Menu',
        'footer_menu'=> 'Footer Menu',
		'top_menu'=> 'Top Menu',
		'footer_bottom_menu'=> 'Footer Bottom Menu',
		'resource_menu' => 'Footer Resources Menu',
		'other_menu' => 'Other Menu',
		'about_side_bar_menu' => 'About sidebar menu',
		'about_research_menu' => 'About research menu',
		'news_menu' => 'News menu',
		'admission_menu' => 'Admission menu',
		'research_thematic_menu' => 'Research Thematic Menu',
		'about_sriic_menu' => 'About SRIIC Menu',
		'international_menu' => 'International Menu',
		'iqac_menu' => 'IQAC Menu',
		'alumni_menu' => 'Alumni Menu',
		'ftr_left_menu1' => 'footer_left Menu1',
		'ftr_left_menu2' => 'footer_left Menu2',
		'mobilemenu' => 'Mobile Menu',
	)
);

/*---------- Register Widgets Area ---------*/
add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
	register_sidebar(array(
        'id' => 'footer_widgets',
		'name' => 'Footer Widgets',
		'before_widget' => '<div class="fw-inner-col col-sm-3">',
		'after_widget' => '</div></div>',
		'before_title' => '<h5 class="fw-title">',
		'after_title' => '</h5><div class="fw-content">',
	));

	// Share
	register_sidebar( array(
		'name'          => 'Share Widget',
		'id'            => 'share_widget',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}




/************* gallery loadmore ajax */
add_action('wp_ajax_gallery_loadmore' , 'fn_gallery_loadmore');
add_action('wp_ajax_nopriv_gallery_loadmore','fn_gallery_loadmore');
function fn_gallery_loadmore(){
    $start=$_POST['start'];
    $end=$_POST['end'];
?>

	<?php ob_start();?>
	<?php if(have_rows('gallery_sec','option')){while(have_rows('gallery_sec','option')){the_row()?>
		<?php $count=count(get_sub_field('gallery'));?>
	<?php if(have_rows('gallery')){$j=0;$i=$start+1;while(have_rows('gallery')){the_row()?>
		
		<?php if($start<=$end && $j>=$start){?>
		<div class="box">
			
			<?php if(get_sub_field('select_popup_type')){$popuptype=get_sub_field('select_popup_type');}?>
			<?php if($popuptype=='image'){?>
				<?php if($popupimg=get_sub_field('imgae')){?>
					<div class="imgbox">
						<img class="gallery-img" src="<?php echo esc_url($popupimg['url']); ?>" alt="<?php if(esc_attr($popupimg['alt'])){echo esc_attr($popupimg['alt']);}else{?>image<?php }?>">
					</div>
				<div class="gallerypopup">
					<a href="#imgpopup<?php echo $i?>">
						<img class="icon" src="<?php echo get_template_directory_uri(); ?>/images/search.svg" alt="search.svg">
						<div class="mfp-hide" id="imgpopup<?php echo $i?>">
							<img  src="<?php echo esc_url($popupimg['url']); ?>" alt="<?php if(esc_attr($popupimg['alt'])){echo esc_attr($popupimg['alt']);}else{?>image<?php }?>">
						</div>                                   
					</a>
				</div>
			<?php }}?>
			<?php if($popuptype=='video'){?>
				<?php if($variable=get_sub_field('thumbnail_image')){?>
					<div class="imgbox">
						<img class="gallery-img" src="<?php echo esc_url($variable['url']); ?>" alt="<?php if(esc_attr($variable['alt'])){echo esc_attr($variable['alt']);}else{?>image<?php }?>">
					</div>
				<?php }?>
				<?php if($variable=get_sub_field('video')){?>
					<div class="gallerypopup">
						<a href="#videopopup<?php echo $i?>">
							<img class="icon" src="<?php echo get_template_directory_uri(); ?>/images/play.svg" alt="play icon">
							<div class="mfp-hide" id="videopopup<?php echo $i?>">
								<video width="80%" controls>
									<source src="<?php echo $variable?>" type="video/mp4">
								</video>
							</div>
						</a>
					</div>
				<?php }?>
			<?php }?>
			<?php if($popuptype=='youtube'){?>
				<?php if($variable=get_sub_field('thumbnail_image')){?>
					<img class="gallery-img" src="<?php echo esc_url($variable['url']); ?>" alt="<?php if(esc_attr($variable['alt'])){echo esc_attr($variable['alt']);}else{?>image<?php }?>">
				<?php }?>
				<?php if($variable=get_sub_field('youtube_embed_code')){?>
					<div class="gallerypopup">
						<a href="#youtubepupup<?php echo $i?>">
							<img class="icon" src="<?php echo get_template_directory_uri(); ?>/images/play.svg" alt="play icon">
							<div class="mfp-hide" id="youtubepupup<?php echo $i?>">
								<?php echo $variable?>
							</div>
						</a>
					</div>
				<?php }?>
			<?php }?>
			<?php if($variable=get_sub_field('title')){?>
				<h3><?php echo $variable?></h3>
			<?php }?>
		</div>
		<?php }?>
	<?php if($j>=$start){$start++;$i++;}$j++;}}}}?>
	<?php $postscontent= ob_get_contents();
   	ob_end_clean();
	$r = array( "postscontent" => $postscontent,"total_row" => $count);
 	echo json_encode($r);
	die();
}


// Remove category archives
add_action('template_redirect', 'jltwp_adminify_remove_archives_category');
function jltwp_adminify_remove_archives_category()
{
    if (is_category()){
        $target = get_option('siteurl');
        $status = '301';
        wp_redirect($target, 301);
        die();
    }
}

// Register Custom Post Type
function custom_post_type()
{ 
	//testimonials post creation
	$labelstest = array(
		'name'                  => _x( 'testimonials', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'testimonial', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Testimonials', 'sriher' ),
		'name_admin_bar'        => __( 'testimonials', 'sriher' ),
		'archives'              => __( 'testimonials Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent testimonials:', 'sriher' ),
		'all_items'             => __( 'All testimonials', 'sriher' ),
		'add_new_item'          => __( 'Add New testimonials', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New testimonials', 'sriher' ),
		'edit_item'             => __( 'Edit testimonials', 'sriher' ),
		'update_item'           => __( 'Update testimonials', 'sriher' ),
		'view_item'             => __( 'View testimonials', 'sriher' ),
		'search_items'          => __( 'Search testimonials', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into testimonials', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this testimonials', 'sriher' ),
		'items_list'            => __( 'testimonials list', 'sriher' ),
		'items_list_navigation' => __( 'testimonials list navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter testimonials list', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'testimonials', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'menu_icon'             => 'dashicons-testimonial',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'testimonials', $argstest );
	/*----------end register testimonials taxonomys--------------*/ 

	//news post creation
	$labelstest = array(
		'name'                  => _x( 'News', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'News', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'News', 'sriher' ),
		'name_admin_bar'        => __( 'News', 'sriher' ),
		'archives'              => __( 'News Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent News:', 'sriher' ),
		'all_items'             => __( 'All News', 'sriher' ),
		'add_new_item'          => __( 'Add New News', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New News', 'sriher' ),
		'edit_item'             => __( 'Edit News', 'sriher' ),
		'update_item'           => __( 'Update News', 'sriher' ),
		'view_item'             => __( 'View News', 'sriher' ),
		'search_items'          => __( 'Search News', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into News', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this News', 'sriher' ),
		'items_list'            => __( 'News list', 'sriher' ),
		'items_list_navigation' => __( 'News list navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter News list', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'news', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		//'menu_icon'             => 'dashicons-products',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'news', $argstest );
	/*----------end register news taxonomys--------------*/ 

	//events post creation
	$labelstest = array(
		'name'                  => _x( 'Events', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Events', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Events', 'sriher' ),
		'name_admin_bar'        => __( 'Events', 'sriher' ),
		'archives'              => __( 'Events Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent Events:', 'sriher' ),
		'all_items'             => __( 'All Events', 'sriher' ),
		'add_new_item'          => __( 'Add New Events', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Events', 'sriher' ),
		'edit_item'             => __( 'Edit Events', 'sriher' ),
		'update_item'           => __( 'Update Events', 'sriher' ),
		'view_item'             => __( 'View Events', 'sriher' ),
		'search_items'          => __( 'Search Events', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Events', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Events', 'sriher' ),
		'items_list'            => __( 'Events list', 'sriher' ),
		'items_list_navigation' => __( 'Events list navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Events list', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'events', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		//'menu_icon'             => 'dashicons-products',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'events', $argstest );
	/*----------end register events taxonomys--------------*/ 

	//Press Release post creation
	// $labelstest = array(
	// 	'name'                  => _x( 'Press Release', 'Post Type General Name', 'sriher' ),
	// 	'singular_name'         => _x( 'Press Release', 'Post Type Singular Name', 'sriher' ),
	// 	'menu_name'             => __( 'Press Release', 'sriher' ),
	// 	'name_admin_bar'        => __( 'Press Release', 'sriher' ),
	// 	'archives'              => __( 'Press Release Archives', 'sriher' ),
	// 	'parent_item_colon'     => __( 'Parent Press Release:', 'sriher' ),
	// 	'all_items'             => __( 'All Press Release', 'sriher' ),
	// 	'add_new_item'          => __( 'Add New Press Release', 'sriher' ),
	// 	'add_new'               => __( 'Add New', 'sriher' ),
	// 	'new_item'              => __( 'New Press Release', 'sriher' ),
	// 	'edit_item'             => __( 'Edit Press Release', 'sriher' ),
	// 	'update_item'           => __( 'Update Press Release', 'sriher' ),
	// 	'view_item'             => __( 'View Press Release', 'sriher' ),
	// 	'search_items'          => __( 'Search Press Release', 'sriher' ),
	// 	'not_found'             => __( 'Not found', 'sriher' ),
	// 	'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
	// 	'featured_image'        => __( 'Featured Image', 'sriher' ),
	// 	'set_featured_image'    => __( 'Set featured image', 'sriher' ),
	// 	'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
	// 	'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
	// 	'insert_into_item'      => __( 'Insert into Press Release', 'sriher' ),
	// 	'uploaded_to_this_item' => __( 'Uploaded to this Press Release', 'sriher' ),
	// 	'items_list'            => __( 'Press Release list', 'sriher' ),
	// 	'items_list_navigation' => __( 'Press Release navigation', 'sriher' ),
	// 	'filter_items_list'     => __( 'Filter Press Release', 'sriher' ),
	// );
	// $argstest = array(
	// 	'label'                 => __( 'press-release', 'sriher' ),
	// 	'labels'                => $labelstest,
	// 	'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
	// 	'hierarchical'          => false,
	// 	'public'                => false,
	// 	'show_ui'               => true,
	// 	'show_in_menu'          => true,
	// 	'menu_position'         => 5,
	// 	'show_in_admin_bar'     => true,
	// 	'show_in_nav_menus'     => true,
	// 	'can_export'            => true,
	// 	'has_archive'           => false,
	// 	'exclude_from_search'   => false,
	// 	'publicly_queryable'    => true,
	// 	//'menu_icon'             => 'dashicons-products',
	// 	'show_in_rest'          => true,
	// 	'public'    => true,
	// );
	// register_post_type( 'press-release', $argstest );
	/*----------end register press release taxonomys--------------*/ 

	//Newsletter post creation
	$labelstest = array(
		'name'                  => _x( 'Newsletter', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Newsletter', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Newsletter', 'sriher' ),
		'name_admin_bar'        => __( 'Newsletter', 'sriher' ),
		'archives'              => __( 'Newsletter Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent Newsletter:', 'sriher' ),
		'all_items'             => __( 'All Newsletter', 'sriher' ),
		'add_new_item'          => __( 'Add New Newsletter', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Newsletter', 'sriher' ),
		'edit_item'             => __( 'Edit Newsletter', 'sriher' ),
		'update_item'           => __( 'Update Newsletter', 'sriher' ),
		'view_item'             => __( 'View Newsletter', 'sriher' ),
		'search_items'          => __( 'Search Newsletter', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Newsletter', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Newsletter', 'sriher' ),
		'items_list'            => __( 'Newsletter list', 'sriher' ),
		'items_list_navigation' => __( 'Newsletter navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Newsletter', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'newsletter', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		//'menu_icon'             => 'dashicons-products',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'newsletter', $argstest );
	/*----------end register newsletter taxonomys--------------*/

	//Programme post creation
	$labelstest = array(
		'name'                  => _x( 'Programme', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Programme', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Programme', 'sriher' ),
		'name_admin_bar'        => __( 'Programme', 'sriher' ),
		'archives'              => __( 'Programme Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent Programme:', 'sriher' ),
		'all_items'             => __( 'All Programme', 'sriher' ),
		'add_new_item'          => __( 'Add New Programme', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Programme', 'sriher' ),
		'edit_item'             => __( 'Edit Programme', 'sriher' ),
		'update_item'           => __( 'Update Programme', 'sriher' ),
		'view_item'             => __( 'View Programme', 'sriher' ),
		'search_items'          => __( 'Search Programme', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Newsletter', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Programme', 'sriher' ),
		'items_list'            => __( 'Programme list', 'sriher' ),
		'items_list_navigation' => __( 'Programme navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Programme', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'programme', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 9,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'menu_icon'             => 'dashicons-welcome-learn-more',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'programme', $argstest );
	/*----------end register Programme taxonomys--------------*/

	//Career post creation
	$labelstest = array(
		'name'                  => _x( 'Career', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Career', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Career', 'sriher' ),
		'name_admin_bar'        => __( 'Career', 'sriher' ),
		'archives'              => __( 'Career Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent Career:', 'sriher' ),
		'all_items'             => __( 'All Career', 'sriher' ),
		'add_new_item'          => __( 'Add New Career', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Career', 'sriher' ),
		'edit_item'             => __( 'Edit Career', 'sriher' ),
		'update_item'           => __( 'Update Career', 'sriher' ),
		'view_item'             => __( 'View Career', 'sriher' ),
		'search_items'          => __( 'Search Career', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Newsletter', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Career', 'sriher' ),
		'items_list'            => __( 'Career list', 'sriher' ),
		'items_list_navigation' => __( 'Career navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Career', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'career', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 10,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		//'menu_icon'             => 'dashicons-products',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'career', $argstest );
	/*----------end register Career taxonomys--------------*/


	//Gallery post creation
	$labelstest = array(
		'name'                  => _x( 'Gallery', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Gallery', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Gallery', 'sriher' ),
		'name_admin_bar'        => __( 'Gallery', 'sriher' ),
		'archives'              => __( 'Gallery Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Collections:', 'sriher' ),
		'all_items'             => __( 'All Collections', 'sriher' ),
		'add_new_item'          => __( 'Add New Collection', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Collection', 'sriher' ),
		'edit_item'             => __( 'Edit Collection', 'sriher' ),
		'update_item'           => __( 'Update Collection', 'sriher' ),
		'view_item'             => __( 'View Collection', 'sriher' ),
		'search_items'          => __( 'Search Collection', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Collection', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Collection', 'sriher' ),
		'items_list'            => __( 'Collection list', 'sriher' ),
		'items_list_navigation' => __( 'Collection list navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Collection list', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'gallery', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'menu_icon'             => 'dashicons-format-image',
		'show_in_rest'          => true,
		'rewrite' => array( 'slug' => 'photo-gallery',
				'with_front'            => true,
				'pages'                 => true,
				'feeds'                 => true),
		'public'    => true,
	);
	register_post_type( 'gallery', $argstest );
	/*----------end register Gallery taxonomys--------------*/

	//Awards post creation
	$labelstest = array(
		'name'                  => _x( 'Awards', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Awards', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Awards', 'sriher' ),
		'name_admin_bar'        => __( 'Awards', 'sriher' ),
		'archives'              => __( 'Awards Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent Awards:', 'sriher' ),
		'all_items'             => __( 'All Awards', 'sriher' ),
		'add_new_item'          => __( 'Add New Awards', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Awards', 'sriher' ),
		'edit_item'             => __( 'Edit Awards', 'sriher' ),
		'update_item'           => __( 'Update Awards', 'sriher' ),
		'view_item'             => __( 'View Awards', 'sriher' ),
		'search_items'          => __( 'Search Awards', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image (maximum image upload size 190 X 100)', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Newsletter', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Awards', 'sriher' ),
		'items_list'            => __( 'Awards list', 'sriher' ),
		'items_list_navigation' => __( 'Awards navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Awards', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'awards', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 10,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'menu_icon'             => 'dashicons-awards',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'awards', $argstest );
	/*----------end register awards taxonomys--------------*/
	//leaders post creation
	$labelstest = array(
		'name'                  => _x( 'leaders', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'leaders', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'leaders', 'sriher' ),
		'name_admin_bar'        => __( 'leaders', 'sriher' ),
		'archives'              => __( 'leaders Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent leaders:', 'sriher' ),
		'all_items'             => __( 'All leaders', 'sriher' ),
		'add_new_item'          => __( 'Add New leaders', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New leaders', 'sriher' ),
		'edit_item'             => __( 'Edit leaders', 'sriher' ),
		'update_item'           => __( 'Update leaders', 'sriher' ),
		'view_item'             => __( 'View leaders', 'sriher' ),
		'search_items'          => __( 'Search leaders', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Newsletter', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this leaders', 'sriher' ),
		'items_list'            => __( 'leaders list', 'sriher' ),
		'items_list_navigation' => __( 'leaders navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter leaders', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'leaders', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 10,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'menu_icon'             => 'dashicons-businessperson',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'leaders', $argstest );
	/*----------end register leaders taxonomys--------------*/

	//Accreditation post creation
	$labelstest = array(
		'name'                  => _x( 'Accreditation', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Accreditation', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Accreditation', 'sriher' ),
		'name_admin_bar'        => __( 'Accreditation', 'sriher' ),
		'archives'              => __( 'Accreditation Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Parent Accreditation:', 'sriher' ),
		'all_items'             => __( 'All Accreditation', 'sriher' ),
		'add_new_item'          => __( 'Add New Accreditation', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Accreditation', 'sriher' ),
		'edit_item'             => __( 'Edit Accreditation', 'sriher' ),
		'update_item'           => __( 'Update Accreditation', 'sriher' ),
		'view_item'             => __( 'View Accreditation', 'sriher' ),
		'search_items'          => __( 'Search Accreditation', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Featured Image', 'sriher' ),
		'set_featured_image'    => __( 'Set featured image', 'sriher' ),
		'remove_featured_image' => __( 'Remove featured image', 'sriher' ),
		'use_featured_image'    => __( 'Use as featured image', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Newsletter', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Accreditation', 'sriher' ),
		'items_list'            => __( 'Accreditation list', 'sriher' ),
		'items_list_navigation' => __( 'Accreditation navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Accreditation', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'accreditation', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 10,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'menu_icon'             => 'dashicons-shield',
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'accreditation', $argstest );
	/*----------end register accreditation taxonomys--------------*/

	//publication post creation
	$labelstest = array(
		'name'                  => _x( 'Publications', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Publications', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Publications', 'sriher' ),
		'name_admin_bar'        => __( 'Publications', 'sriher' ),
		'archives'              => __( 'Publications Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Publications:', 'sriher' ),
		'all_items'             => __( 'All Publications', 'sriher' ),
		'add_new_item'          => __( 'Add New publication', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Publication', 'sriher' ),
		'edit_item'             => __( 'Edit Publication', 'sriher' ),
		'update_item'           => __( 'Update Publication', 'sriher' ),
		'view_item'             => __( 'View Publication', 'sriher' ),
		'search_items'          => __( 'Search Publication', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Publication', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Publication', 'sriher' ),
		'items_list'            => __( 'Publications', 'sriher' ),
		'items_list_navigation' => __( 'Publications navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Publications', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'publication', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 10,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'public'    => true,
	);
	register_post_type( 'publication', $argstest );
	/*----------end register publication taxonomys--------------*/
	// register_taxonomy('degree', array('programme'), array(
    //     'hierarchical' => true,
    //     'has_archive' => true,
    //     'public' => true,
    //     'show_ui' => true,
    //     'show_admin_column' => true,
    //     'show_in_rest' => true,
    //     'labels' => array(
    //         'name' => _x('Degree', 'taxonomy general name'),
    //         'singular_name' => _x('Degree', 'taxonomy singular name'),
    //         'search_items' => __('Search Degree'),
    //         'all_items' => __('All Degree'),
    //         'parent_item' => __('Parent Degree'),
    //         'parent_item_colon' => __('Parent Degree:'),
    //         'edit_item' => __('Edit Degree'),
    //         'update_item' => __('Update Degree'),
    //         'add_new_item' => __('Add New Degree'),
    //         'new_item_name' => __('New Degree Name'),
    //         'menu_name' => __('Degree'),
    //     ),
    // ));
	register_taxonomy('college', array('programme','career'), array(
        'hierarchical' => true,
        'has_archive' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => _x('College', 'taxonomy general name'),
            'singular_name' => _x('College', 'taxonomy singular name'),
            'search_items' => __('Search College'),
            'all_items' => __('All College'),
            'parent_item' => __('Parent College'),
            'parent_item_colon' => __('Parent College:'),
            'edit_item' => __('Edit College'),
            'update_item' => __('Update College'),
            'add_new_item' => __('Add New College'),
            'new_item_name' => __('New College Name'),
            'menu_name' => __('College'),
        ),
    ));
	//Centres of Excellence post creation
	$labelstest = array(
		'name'                  => _x( 'Centres of Excellence', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Centres of Excellence', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Centres of Excellence', 'sriher' ),
		'name_admin_bar'        => __( 'Centres of Excellence', 'sriher' ),
		'archives'              => __( 'Centres of Excellence Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Centres of Excellence:', 'sriher' ),
		'all_items'             => __( 'All Centres of Excellence', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Centres of Excellence', 'sriher' ),
		'edit_item'             => __( 'Edit Page', 'sriher' ),
		'update_item'           => __( 'Update Page', 'sriher' ),
		'view_item'             => __( 'View Page', 'sriher' ),
		'search_items'          => __( 'Search Centres of Excellence', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Centres of Excellence', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Centres of Excellence', 'sriher' ),
		'items_list'            => __( 'Centres of Excellence', 'sriher' ),
		'items_list_navigation' => __( 'Centres of Excellence navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Centres of Excellence', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'coe', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 12,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'menu_icon'             => 'dashicons-bank',
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'rewrite' => array('slug' => 'centres-of-excellence'),
		'public'    => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	);
	register_post_type( 'coe', $argstest );
	/*----------end register Centres of Excellence taxonomys--------------*/

	//Thematic Cluster post creation
	$labelstest = array(
		'name'                  => _x( 'Thematic Clusters', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Thematic Cluster', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Thematic Cluster', 'sriher' ),
		'name_admin_bar'        => __( 'Thematic Cluster', 'sriher' ),
		'archives'              => __( 'Thematic Cluster Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Thematic Cluster:', 'sriher' ),
		'all_items'             => __( 'All Thematic Clusters', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Thematic Cluster', 'sriher' ),
		'edit_item'             => __( 'Edit Page', 'sriher' ),
		'update_item'           => __( 'Update Page', 'sriher' ),
		'view_item'             => __( 'View Page', 'sriher' ),
		'search_items'          => __( 'Search Thematic Cluster', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Thematic Cluster', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Thematic Cluster', 'sriher' ),
		'items_list'            => __( 'Thematic Cluster', 'sriher' ),
		'items_list_navigation' => __( 'Thematic Cluster navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Thematic Cluster', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'thematic-cluster', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 11,
		'menu_icon'             => 'dashicons-category',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'rewrite' => array('slug' => 'thematic-cluster'),
		'capability_type'    => 'page',
		'query_var'          => true,
	);
	register_post_type( 'thematic-cluster', $argstest );
	/*----------end register Thematic Cluster taxonomys--------------*/

	//Focal Area post creation
	$labelstest = array(
		'name'                  => _x( 'Focal Areas', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Focal Area', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Focal Areas', 'sriher' ),
		'name_admin_bar'        => __( 'Focal Area', 'sriher' ),
		'archives'              => __( 'Focal Area Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Focal Area:', 'sriher' ),
		'all_items'             => __( 'All Focal Areas', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Focal Area', 'sriher' ),
		'edit_item'             => __( 'Edit Page', 'sriher' ),
		'update_item'           => __( 'Update Page', 'sriher' ),
		'view_item'             => __( 'View Page', 'sriher' ),
		'search_items'          => __( 'Search Focal Area', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Focal Area', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Focal Area', 'sriher' ),
		'items_list'            => __( 'Focal Area', 'sriher' ),
		'items_list_navigation' => __( 'Focal Area navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Focal Area', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'focal-area', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=thematic-cluster',
		'menu_position'         => 11,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'rewrite' => array('slug' => 'focal-area'),
		'public'    => true,
		'capability_type'    => 'page',
		'query_var'          => true,
	);
	register_post_type( 'focal-area', $argstest );
	/*----------end register Focal Area taxonomys--------------*/

	//Laboratory Facility post creation
	$labelstest = array(
		'name'                  => _x( 'Lab Facilities', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Lab Facility', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Lab Facilities', 'sriher' ),
		'name_admin_bar'        => __( 'Lab Facilities', 'sriher' ),
		'archives'              => __( 'Lab Facilities Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Lab Facilities:', 'sriher' ),
		'all_items'             => __( 'All Lab Facilities', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Lab Facility', 'sriher' ),
		'edit_item'             => __( 'Edit Page', 'sriher' ),
		'update_item'           => __( 'Update Page', 'sriher' ),
		'view_item'             => __( 'View Page', 'sriher' ),
		'search_items'          => __( 'Search Lab Facility', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Lab Facilities', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Lab Facilities', 'sriher' ),
		'items_list'            => __( 'Lab Facilities', 'sriher' ),
		'items_list_navigation' => __( 'Lab Facilities navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Lab Facilities', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'lab-facilities', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=thematic-cluster',
		'menu_position'         => 11,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'rewrite' => array('slug' => 'laboratory-facilities'),
		'public'    => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	);
	register_post_type( 'lab-facilities', $argstest );
	/*----------end register Lab Facilities taxonomys--------------*/

	//SRIIC Facilities post creation
	$labelstest = array(
		'name'                  => _x( 'SRIIC Facilities', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'SRIIC Facility', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'SRIIC Facilities', 'sriher' ),
		'name_admin_bar'        => __( 'SRIIC Facilities', 'sriher' ),
		'archives'              => __( 'SRIIC Facilities Archives', 'sriher' ),
		'parent_item_colon'     => __( 'SRIIC Facilities:', 'sriher' ),
		'all_items'             => __( 'All Facilities', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New SRIIC Facility', 'sriher' ),
		'edit_item'             => __( 'Edit Page', 'sriher' ),
		'update_item'           => __( 'Update Page', 'sriher' ),
		'view_item'             => __( 'View Page', 'sriher' ),
		'search_items'          => __( 'Search SRIIC Facility', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into SRIIC Facilities', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this SRIIC Facilities', 'sriher' ),
		'items_list'            => __( 'SRIIC Facilities', 'sriher' ),
		'items_list_navigation' => __( 'SRIIC Facilities navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter SRIIC Facilities', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'facilities', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'sriic',
		'menu_position'         => 11,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'rewrite' => array('slug' => 'facilities'),
		'public'    => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	);
	register_post_type( 'facilities', $argstest );
	/*----------end register SRIIC Facilities taxonomys--------------*/

	//SRIIC Incubatee post creation
	$labelstest = array(
		'name'                  => _x( 'SRIIC Incubatees', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'SRIIC Incubatee', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'SRIIC Incubatees', 'sriher' ),
		'name_admin_bar'        => __( 'SRIIC Incubatees', 'sriher' ),
		'archives'              => __( 'SRIIC Incubatees Archives', 'sriher' ),
		'parent_item_colon'     => __( 'SRIIC Incubatees:', 'sriher' ),
		'all_items'             => __( 'All Incubatees', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New SRIIC Incubatees', 'sriher' ),
		'edit_item'             => __( 'Edit Page', 'sriher' ),
		'update_item'           => __( 'Update Page', 'sriher' ),
		'view_item'             => __( 'View Page', 'sriher' ),
		'search_items'          => __( 'Search SRIIC Incubatees', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into SRIIC Incubatees', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this SRIIC Incubatees', 'sriher' ),
		'items_list'            => __( 'SRIIC Incubatees', 'sriher' ),
		'items_list_navigation' => __( 'SRIIC Incubatees navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter SRIIC Incubatees', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'incubatee', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'taxonomies'            => array('incubatees'),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'sriic',
		'menu_position'         => 11,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'rewrite' => array('slug' => 'incubatee'),
		'public'    => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	);
	register_post_type( 'incubatee', $argstest );
	/*----------end register SRIIC Incubatee taxonomys--------------*/

	//Students Corner post creation
	$labelstest = array(
		'name'                  => _x( 'Students Corner', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Students Corner', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Students Corner', 'sriher' ),
		'name_admin_bar'        => __( 'Students Corner', 'sriher' ),
		'archives'              => __( 'Students Corner Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Students Corner:', 'sriher' ),
		'all_items'             => __( 'Students Corner', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Post', 'sriher' ),
		'edit_item'             => __( 'Edit Post', 'sriher' ),
		'update_item'           => __( 'Update Post', 'sriher' ),
		'view_item'             => __( 'View Post', 'sriher' ),
		'search_items'          => __( 'Search Post', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Students Corner', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Students Corner', 'sriher' ),
		'items_list'            => __( 'Students Corner', 'sriher' ),
		'items_list_navigation' => __( 'Students Corner navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Students Corner', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'srmcblog', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'taxonomies'            => array('feeds'),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'menu_position'         => 11,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'menu_icon'             => 'dashicons-edit-page',
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'public'    => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	);
	register_post_type( 'srmcblog', $argstest );
	/*----------end register Students Corner taxonomys--------------*/

	//IQAC Impact Ranking post creation
	$labelstest = array(
		'name'                  => _x( 'Impact ranking', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Impact ranking', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Impact ranking', 'sriher' ),
		'name_admin_bar'        => __( 'Impact ranking', 'sriher' ),
		'archives'              => __( 'Impact ranking Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Impact ranking:', 'sriher' ),
		'all_items'             => __( 'Impact ranking', 'sriher' ),
		'add_new_item'          => __( 'Add New', 'sriher' ),
		'add_new'               => __( 'Add New', 'sriher' ),
		'new_item'              => __( 'New Impact Ranking', 'sriher' ),
		'edit_item'             => __( 'Edit', 'sriher' ),
		'update_item'           => __( 'Update', 'sriher' ),
		'view_item'             => __( 'View', 'sriher' ),
		'search_items'          => __( 'Search', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Impact ranking', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Impact ranking', 'sriher' ),
		'items_list'            => __( 'Impact ranking', 'sriher' ),
		'items_list_navigation' => __( 'Impact ranking navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Impact ranking', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'impact-ranking', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'taxonomies'            => array('project-category'),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'menu_position'         => 11,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_menu'          => 'iqac',
		'menu_icon'             => 'dashicons-portfolio',
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'public'    => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	);
	register_post_type( 'impact-ranking', $argstest );
	/*----------end register IQAC Impact Ranking taxonomys--------------*/

	//IQAC Projects post creation
	$labelstest = array(
		'name'                  => _x( 'Projects', 'Post Type General Name', 'sriher' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'sriher' ),
		'menu_name'             => __( 'Projects', 'sriher' ),
		'name_admin_bar'        => __( 'Projects', 'sriher' ),
		'archives'              => __( 'Projects Archives', 'sriher' ),
		'parent_item_colon'     => __( 'Projects:', 'sriher' ),
		'all_items'             => __( 'Projects', 'sriher' ),
		'add_new_item'          => __( 'Add New Project', 'sriher' ),
		'add_new'               => __( 'Add New Project', 'sriher' ),
		'new_item'              => __( 'New Project', 'sriher' ),
		'edit_item'             => __( 'Edit Project', 'sriher' ),
		'update_item'           => __( 'Update Project', 'sriher' ),
		'view_item'             => __( 'View Project', 'sriher' ),
		'search_items'          => __( 'Search Project', 'sriher' ),
		'not_found'             => __( 'Not found', 'sriher' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'sriher' ),
		'featured_image'        => __( 'Thumbnail', 'sriher' ),
		'set_featured_image'    => __( 'Set Thumbnail', 'sriher' ),
		'remove_featured_image' => __( 'Remove Thumbnail', 'sriher' ),
		'use_featured_image'    => __( 'Use as Thumbnail', 'sriher' ),
		'insert_into_item'      => __( 'Insert into Projects', 'sriher' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Projects', 'sriher' ),
		'items_list'            => __( 'Projects', 'sriher' ),
		'items_list_navigation' => __( 'Projects navigation', 'sriher' ),
		'filter_items_list'     => __( 'Filter Projects', 'sriher' ),
	);
	$argstest = array(
		'label'                 => __( 'project', 'sriher' ),
		'labels'                => $labelstest,
		'supports'              => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields', ),
		'taxonomies'            => array('project-category'),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'menu_position'         => 11,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'show_in_menu'          => 'iqac',
		'menu_icon'             => 'dashicons-portfolio',
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest'          => true,
		'public'    => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	);
	register_post_type( 'project', $argstest );
	/*----------end register IQAC Projects taxonomys--------------*/

	


}
add_action('init', 'custom_post_type', 0);


/*------------------------ Register taxonomies --------------------------*/
function add_custom_taxonomies() {
    register_taxonomy('skillset', array('career'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => _x('Skillset', 'taxonomy general name'),
            'singular_name' => _x('Skillset', 'taxonomy singular name'),
            'search_items' => __('Search Skillset'),
            'all_items' => __('All Skillset'),
            'parent_item' => __('Parent Skillset'),
            'parent_item_colon' => __('Parent Skillset:'),
            'edit_item' => __('Edit Skillset'),
            'update_item' => __('Update Skillset'),
            'add_new_item' => __('Add New Skillset'),
            'new_item_name' => __('New Skillset Name'),
            'menu_name' => __('Skillset'),
        ),
    ));

	register_taxonomy('cluster', array('thematic-cluster', 'focal-area', 'lab-facilities', 'publication'), array(
		'labels' => array(
            'name' => _x('Thematic Cluster', 'taxonomy general name'),
            'singular_name' => _x('Thematic Cluster', 'taxonomy singular name'),
            'search_items' => __('Thematic Cluster'),
            'all_items' => __('All Thematic Cluster'),
            'parent_item' => __('Parent Thematic Cluster'),
            'parent_item_colon' => __('Parent Thematic Cluster:'),
            'edit_item' => __('Edit Thematic Cluster'),
            'update_item' => __('Update Thematic Cluster'),
            'add_new_item' => __('Add New Thematic Cluster'),
            'new_item_name' => __('New Thematic Cluster'),
            'menu_name' => __('Cluster'),
        ),
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'show_in_menu' => 'edit.php?post_type=thematic-cluster',
		'menu_position' => 5,
		//'supports' => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields' ),
    ));

	register_taxonomy('incubatees', array('incubatee'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'capability_type'    => 'post',
		'labels' => array(
            'name' => _x('Incubatees', 'taxonomy general name'),
            'singular_name' => _x('Incubatee', 'taxonomy singular name'),
            'search_items' => __('Incubatees'),
            'all_items' => __('All Incubatees'),
            'parent_item' => __('Parent Incubatees'),
            'parent_item_colon' => __('Parent Incubatees:'),
            'edit_item' => __('Edit Incubatees'),
            'update_item' => __('Update Incubatees'),
            'add_new_item' => __('Add New Incubatee'),
            'new_item_name' => __('New Incubatee'),
            'menu_name' => __('Incubatees Categories'),
        ),
    ));

	register_taxonomy('srmcblog-category', array('srmcblog'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'capability_type'    => 'post',
		'supports' => array( 'title', 'editor','excerpt', 'thumbnail', 'custom-fields' ),
		'labels' => array(
            'name' => _x('SRMCBlog Categories', 'taxonomy general name'),
            'singular_name' => _x('SRMCBlog Category', 'taxonomy singular name'),
            'search_items' => __('SRMCBlog Categories'),
            'all_items' => __('All SRMCBlog Categories'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Categories'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category'),
            'menu_name' => __('SRMCBlog Category'),
        ),
    ));

	register_taxonomy('gallery-category', array('gallery'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'capability_type'    => 'post',
		'labels' => array(
            'name' => _x('Gallery Categories', 'taxonomy general name'),
            'singular_name' => _x('Gallery Category', 'taxonomy singular name'),
            'search_items' => __('Gallery Categories'),
            'all_items' => __('All Gallery Categories'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Categories'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category'),
            'menu_name' => __('Gallery Category'),
        ),
    ));

	register_taxonomy('media-category', array('news', 'events'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'capability_type'    => 'post',
		'labels' => array(
            'name' => _x('Media Categories', 'taxonomy general name'),
            'singular_name' => _x('Media Category', 'taxonomy singular name'),
            'search_items' => __('Media Categories'),
            'all_items' => __('All Media Categories'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Categories'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category'),
            'menu_name' => __('Categories'),
        ),
    ));

	register_taxonomy('testimony-category', array('testimonials'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'capability_type'    => 'post',
		'labels' => array(
            'name' => _x('Testimony Categories', 'taxonomy general name'),
            'singular_name' => _x('Testimony Category', 'taxonomy singular name'),
            'search_items' => __('Testimony Categories'),
            'all_items' => __('All Testimony Categories'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Categories'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category'),
            'menu_name' => __('Categories'),
        ),
    ));

	register_taxonomy('project-category', array('project', 'impact-ranking'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'capability_type'    => 'post',
		'labels' => array(
            'name' => _x('Project Categories', 'taxonomy general name'),
            'singular_name' => _x('Project Category', 'taxonomy singular name'),
            'search_items' => __('Project Categories'),
            'all_items' => __('All Project Categories'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Categories'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category'),
            'menu_name' => __('Categories'),
        ),
    ));

	register_taxonomy('leaders-category', array('leaders'), array(
        'hierarchical' => true,
        'has_archive' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
		'capability_type'    => 'post',
		'labels' => array(
            'name' => _x('Leaders Categories', 'taxonomy general name'),
            'singular_name' => _x('Leaders Category', 'taxonomy singular name'),
            'search_items' => __('Leaders Categories'),
            'all_items' => __('All Leaders Categories'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Categories'),
            'add_new_item' => __('Add New Category'),
            'new_item_name' => __('New Category'),
            'menu_name' => __('Categories'),
        ),
    ));

}
add_action('init', 'add_custom_taxonomies');

function register_common_menu() {
    add_menu_page(
        'SRIIC',
        'SRIIC',
        'manage_options',
        'sriic', // URL to manage the "Incubatees" taxonomy
        '', // No callback function needed
        'dashicons-bank', // Icon for the menu item
        15 // Menu position
    );
	add_submenu_page(
        'sriic', // Parent menu slug
        'Incubatees', // Submenu page title
        'Incubatees', // Submenu title
        'manage_options',
        'edit-tags.php?taxonomy=incubatees&post_type=incubatee', // URL to manage custom taxonomy terms
        '', // No callback function needed
        '', // Icon (optional)
        10 // Submenu position
    );
}
function register_iqac_menu() {
    add_menu_page(
        'IQAC',
        'IQAC',
        'manage_options',
        'iqac',
        '',
        'dashicons-bank',
        15
    );
	add_submenu_page(
        'iqac', // Parent menu slug
        'Project Category', // Submenu page title
        'Project Category', // Submenu title
        'manage_options',
        'edit-tags.php?taxonomy=project-category&post_type=project', // URL to manage custom taxonomy terms
        '', // No callback function needed
        '', // Icon (optional)
        10 // Submenu position
    );
}
add_action( 'admin_menu', 'register_common_menu');
add_action( 'admin_menu', 'register_iqac_menu');

function custom_breadcrumbs() {
    $breadcrumbs_id = 'breadcrumbs';
    $breadcrumbs_class = 'breadcrumbs';
    $home_title = 'Home';

    // Get the current post and query information
    global $post, $wp_query;

    // Start building the breadcrumbs
    echo '<ul id="' . $breadcrumbs_id . '" class="' . $breadcrumbs_class . '">';

    // Home page breadcrumb
    echo '<li><a href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';

    // Check if the current page is not the front page
    if (!is_front_page()) {
        // Check if it's an archive page (including custom post type archives)
        if (is_archive()) {
            // Check if it's a taxonomy archive
            if (is_tax()) {
				if (is_tax('srmcblog-category')) {
					echo '<li><a href="' . get_home_url() . '/students-corner/">Students Corner</a></li>';
				}
                $term = get_queried_object();
                echo '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a></li>';
            } elseif (is_post_type_archive()) {
                echo '<li>' . post_type_archive_title('', false) . '</li>';
            } elseif (is_date()) {
                echo '<li>' . get_the_date() . '</li>';
            } else {
                echo '<li>' . single_cat_title('', false) . '</li>';
            }
        }

        // Check if it's a singular page (single post, page, or custom post type)
        if (is_singular()) {
            // Get the post type
            $post_type = get_post_type();
            // Check if it's a standard post or a custom post type
            if ($post_type === 'post') {
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            } elseif ($post_type === 'page') {
                $ancestors = get_post_ancestors($post->ID);
                if ($ancestors) {
                    $ancestors = array_reverse($ancestors);
                    foreach ($ancestors as $ancestor) {
                        echo '<li><a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    }
                }
                echo '<li><a href="">' . get_the_title() . '</a></li>';
            } elseif ($post_type === 'gallery') {
				$terms = get_the_terms(get_the_ID(), 'gallery-category');
				if ($terms && !is_wp_error($terms)) {
					foreach ($terms as $term) {
						if ($term->slug === 'nri') {
							echo '<li><a href="' . get_home_url() . '/nri-about-us/">International Relation / Students</a></li>';
							echo '<li><a>Gallery</a></li>';
							break;
						} else {
							echo '<li><a href="' . get_home_url() . '/about/">About</a></li>';
							echo '<li><a>Photo Gallery</a></li>';
						}
					}
				}
			} elseif ($post_type === 'career') {
				$banner_title = get_banner_title();
				echo '<li><a href="' . get_home_url() . '/careers/">Careers</a></li>';
				echo '<li><a>' . $banner_title .'</a></li>';
			} elseif ($post_type === 'coe') {
				$banner_title = get_banner_title();
				$post_type_object = get_post_type_object($post_type);
				echo '<li><a href="' . get_home_url() . '/sriher-research/">Research</a></li>';
				echo '<li><a href="' . get_home_url() . '/centres-of-excellence/' . '">' . $post_type_object->labels->name . '</a></li>';
				echo '<li><a>' . get_the_title() .'</a></li>';
			} elseif ($post_type === 'thematic-cluster') {
				$post_type_object = get_post_type_object($post_type);
				$terms = wp_get_post_terms($post->ID, 'cluster');
				echo '<li><a href="' . get_home_url() . '/sriher-research/">Research</a></li>';
				echo '<li><a href="' . get_home_url() . '/sriher-research/thematic-cluster/' . '">' . $post_type_object->labels->name . '</a></li>';
				if (!empty($terms) && is_array($terms)) {
					$term = reset($terms);
					echo '<li><a href="' . get_home_url() . '/sriher-research/thematic-cluster/' . $term->slug . '/">' . $term->name . '</a></li>';
				}
				echo '<li><a>' . get_the_title() .'</a></li>';
			} elseif (($post_type === 'focal-area') || ($post_type === 'lab-facilities')) {
				$post_type_object = get_post_type_object($post_type);
				$terms = wp_get_post_terms($post->ID, 'cluster');
				echo '<li><a href="' . get_home_url() . '/sriher-research/">Research</a></li>';
				echo '<li><a href="' . get_home_url() . '/sriher-research/thematic-cluster/' . '">Thematic Clusters</a></li>';
				if (!empty($terms) && is_array($terms)) {
					$term = reset($terms);
					echo '<li><a href="' . get_home_url() . '/thematic-cluster/' . $term->slug . '/">' . $term->name . '</a></li>';
				}
				echo '<li><a href="' . get_home_url() . '/thematic-cluster/' . $term->slug . '/' . $post_type_object->rewrite['slug'] . '/">' . $post_type_object->labels->name . '</a></li>';
				echo '<li><a>' . get_the_title() .'</a></li>';
			} elseif ($post_type === 'incubatee') {
				$post_type_object = get_post_type_object($post_type);
				$terms = wp_get_post_terms($post->ID, 'incubatees');
				echo '<li><a href="' . get_home_url() . '/sriher-research/">Research</a></li>';
				echo '<li><a href="' . get_home_url() . '/about-sriic/">About SRIIC</a></li>';
				echo '<li><a href="' . get_home_url() . '/about-sriic/incubatees/' . '">' . $post_type_object->labels->name . '</a></li>';
				if (!empty($terms) && is_array($terms)) {
					$term = reset($terms);
					echo '<li><a href="' . get_home_url() . '/sriher-research/thematic-cluster/' . $term->slug . '/">' . $term->name . '</a></li>';
				}
				echo '<li><a>' . get_the_title() .'</a></li>';
			} elseif ($post_type === 'impact-ranking') {
				$post_type_object = get_post_type_object($post_type);
				$terms = wp_get_post_terms($post->ID, 'project-category');
				echo '<li><a href="' . get_home_url() . '/iqac/">IQAC</a></li>';
				echo '<li><a href="' . get_home_url() . '/iqac/">The Impact Ranking</a></li>';
				if (!empty($terms) && is_array($terms)) {
					$term = reset($terms);
					echo '<li><a href="' . get_home_url() . '/iqac/' . $term->slug . '/">' . $term->name . '</a></li>';
				}
			} elseif ($post_type === 'project') {
				$post_type_object = get_post_type_object($post_type);
				$terms = wp_get_post_terms($post->ID, 'project-category');
				echo '<li><a href="' . get_home_url() . '/iqac/">IQAC</a></li>';
				echo '<li><a href="' . get_home_url() . '/iqac/">The Impact Ranking</a></li>';
				if (!empty($terms) && is_array($terms)) {
					$term = reset($terms);
					echo '<li><a href="' . get_home_url() . '/iqac/' . $term->slug . '/">' . $term->name . '</a></li>';
				}
				echo '<li><a>Projects</a></li>';
			} else {
                // Custom post type
                $post_type_object = get_post_type_object($post_type);
                echo '<li><a href="' . get_post_type_archive_link($post_type) . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li><a href="">' . get_the_title() . '</a></li>';
            }
        }

		if (is_author()) {
			$author_id = get_query_var('author');
			$author_link = get_author_posts_url($author_id);
			echo '<li><a href="' . get_home_url() . '/faculty-list/">Faculty</a></li>';
			echo '<li><a href="' . $author_link . '">' . get_the_author_meta('display_name', $author_id) . '</a></li>';
		}
        // Check if it's a category page
        if (is_category()) {
            $cat = get_queried_object();
            $category_parent_id = $cat->category_parent;
            while ($category_parent_id) {
                $cat_parent = get_category($category_parent_id);
                echo '<li><a href="' . get_category_link($cat_parent) . '">' . $cat_parent->name . '</a></li>';
                $category_parent_id = $cat_parent->category_parent;
            }
            echo '<li><a href="">' . single_cat_title('', false) . '</a></li>';
        }

        // Check if it's a tag page
        if (is_tag()) {
            echo '<li><a href="">' . single_tag_title('', false) . '</a></li>';
        }

        // Check if it's a search page
        if (is_search()) {
            echo '<li><a href="">' . __('Search results for', 'textdomain') . ' "' . get_search_query() . '"</a></li>';
        }

        // Check if it's a 404 error page
        if (is_404()) {
            echo '<li><a href="">' . __('Error 404', 'textdomain') . '</a></li>';
        }
    }

    echo '</ul>';
}

function get_banner_title() {
    $pageID = get_the_ID(); // Get the current page ID
    $banner_title = get_field('banner_title', $pageID); // Get the banner title from the current page
    $post_type = get_post_type();
    if(is_author()){ 
        $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
        $userID = $author->ID;
        $banner_title = get_field('banner_title','user_'.$userID); 
        if (empty($banner_title)) {
            $banner_title = get_field('faculty_profile_title','option'); // Return the Faculty Profile Title
        }
    }
    // Check if the banner title field is not empty
    if (!empty($banner_title)) {
        return $banner_title; // Return the custom banner title
    } else if($post_type === 'gallery') {
        return get_field('gallery_banner_title','option'); // Return the Gallery Banner title from Option
    } else if($post_type === 'career') {
        return get_field('career_banner_title','option'); // Return the Career Banner title from Option
    } else {
        return get_the_title(); // Return the default page title
    }
}

function get_banner_image_url() {
	global $post;

    $pageID = get_the_ID(); // Get the current page ID
     // Get the banner image URL from the current page
    // $banner_image = get_field('banner_image', 'option');
    if(is_author()){
        $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
        $userID = $author->ID;
        $banner_image = get_field('banner_image','user_'.$userID); 

    }else{
        $banner_image = get_field('banner_image', $pageID);
        if (empty($banner_image)) {
            if ($post->post_parent > 0) {
                // If the page has a parent, get the banner image URL from the parent
                $parent_banner = get_field('banner_image', $post->post_parent);
                if (!empty($parent_banner)) {
                    $banner_image = $parent_banner; // Use parent's banner image if available
                }
            } 
        }
    }
    if (empty($banner_image)) {
            $banner_image = get_field('default_banner', 'option');
    }

    // Return the banner image URL
    return $banner_image;
}

add_action('wp_ajax_load_custom_posts', 'load_custom_posts');
add_action('wp_ajax_nopriv_load_custom_posts', 'load_custom_posts');

function load_custom_posts() {
    $termId = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
    $args = array(
        'post_type' => 'incubatee',
        'tax_query' => array(
            array(
                'taxonomy' => 'incubatees',
                'field' => 'term_id',
                'terms' => $termId,
            ),
        ),
    );

    $incubatees = new WP_Query($args);

    if ($incubatees->have_posts()) {
        while($incubatees->have_posts()) : $incubatees->the_post(); ?>
			<div class="listItem">
				<a href="<?php the_permalink(); ?>" class="listThumb">
				<?php 
					if(has_post_thumbnail($incubatees->ID)&&(get_the_post_thumbnail($incubatees->ID)!='')){
					$post_thumb = get_the_post_thumbnail_url(); ?>
						<img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
					<?php } else { ?>
						<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
					<?php } ?>
					</a>
				<div class="listCaption">
					<h2><?php echo get_the_title(); ?></h2>
					<p><?php echo wp_trim_words(get_the_content(), 40); ?>...</p>
					<a href="<?php the_permalink(); ?>">Learn More</a>
				</div>
			</div>
		<?php endwhile;
        wp_reset_postdata();
    } else {
        echo 'No posts found.';
    }

    die(); // Always end with die() for AJAX actions
}








add_action('wp_ajax_load_iqac_posts', 'load_iqac_posts');
add_action('wp_ajax_nopriv_load_iqac_posts', 'load_iqac_posts');
function load_iqac_posts() {
    $subpage_id = $_POST['subpage_id'];
    $content = get_post_field('post_content', $subpage_id);
	echo $content; 
    wp_die();
}
class Custom_Walker_iqac_menu extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = NULL ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    function end_lvl( &$output, $depth = 0, $args = NULL ) {
        $indent = str_repeat( "\t", $depth );
        $output .= "$indent</ul>\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        
        if ( ! empty( $item->object_id ) ) { 
            if(! (empty($item->url) || $item->url === '#')){
                $page = get_post( $item->object_id ); 
                $pageid = $page->ID;
            } else {
                $pageid="custom";
            }

			if ( isset( $item->target ) && $item->target !== '' ) {
				$settings = ' target="' . esc_attr( $item->target ) . '"';
			} else {
				$settings = 'data-page-id="'.$pageid.'"';
			}

            // Do something with the page ID or other attributes
            $output .= $indent . "<li id='menu-item-$item->ID' $class_names>";
            $output .= '<a href="'.esc_url( $item->url ) .'"' . $settings . '>' . esc_html( $item->title ) . '</a>';
        } else {
            $output .= $indent . "<li $class_names>";
            $output .= '<span>' . esc_html( $item->title ) . '</span>';
        }
    }

    function end_el( &$output, $item, $depth = 0, $args = NULL ) {
        $output .= "</li>\n";
    }
}

function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
    $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

    if ( ! empty( $item->object_id ) ) { 
        if ( ! ( empty( $item->url ) || $item->url === '#' ) ) {
            $page = get_post( $item->object_id ); 
            $pageid = $page->ID;
        } else {
            $pageid = "custom";
        }
		if ( isset( $item->target ) && $item->target !== '' ) {
			$settings = ' target="' . esc_attr( $item->target ) . '"';
		} else {
			$settings = 'data-page-id="'.$pageid.'"';
		}
        // Do something with the page ID or other attributes
        $output .= $indent . "<li id='menu-item-$item->ID' $class_names>";
        $output .= '<a href="' . esc_url( $item->url )  . $settings . '>' . esc_html( $item->title ) . '</a>';
        $output .= '</li>';
    }
}


// @ faculty module
/*---------------- Admin end custom scripts ---------- */
function enqueue_acf_script() {
    if (!(current_user_can('vc')||current_user_can('hod') || current_user_can('principal'))) {
        wp_enqueue_script('vc-hide-field', get_template_directory_uri() . '/js/vc-hide-scripts.js', array('acf-input'), null, true);
		wp_enqueue_script('principal-hide-field', get_template_directory_uri() . '/js/principal-hide-scripts.js', array('acf-input'), null, true);
        wp_enqueue_script('hod-hide-field', get_template_directory_uri() . '/js/hod-hide-scripts.js', array('acf-input'), null, true);
        wp_enqueue_script('faculty-role', get_template_directory_uri() . '/js/faculty-role.js', array('acf-input'), null, true);
    }else if (current_user_can('hod')) {
        wp_enqueue_script('vc-hide-field', get_template_directory_uri() . '/js/vc-hide-scripts.js', array('acf-input'), null, true);
		wp_enqueue_script('principal-hide-field', get_template_directory_uri() . '/js/principal-hide-scripts.js', array('acf-input'), null, true);
    }else if (current_user_can('principal')) {
		wp_enqueue_script('hod-hide-field', get_template_directory_uri() . '/js/hod-hide-scripts.js', array('acf-input'), null, true);
        wp_enqueue_script('vc-hide-field', get_template_directory_uri() . '/js/vc-hide-scripts.js', array('acf-input'), null, true);
		wp_enqueue_script('feedbackfield-principal-display', get_template_directory_uri() . '/js/feedbackfield-principal-display.js', array('acf-input'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_acf_script');

add_action( 'get_header', function() {
    if ((is_singular() )) return;

    // Remove AddToAny core script.
    // Note: This disables AddToAny's ability to load dynamically.
    add_filter( 'addtoany_script_disabled', '__return_true' );

    // Remove AddToAny plugin's JS & CSS.
    wp_dequeue_script( 'addtoany' );
    wp_dequeue_style( 'addtoany' );
}, 21);

function assign_custom_post_type_template($template) {
    if ((is_singular('focal-area' ) && (has_post_parent())) ||  is_singular('facilities') || is_singular('incubatee')) {
        $template = locate_template('common-page.php');
    }
	if(is_singular('lab-facilities')){
		$template = locate_template('single-coe.php');
	}
	if (is_singular('impact-ranking')) {
        $template = locate_template('single-impact-ranking.php');
    }
	if ( is_singular( 'gallery' ) ) {
        $terms = get_the_terms( get_the_ID(), 'gallery-category' );
        if ( ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( $term->slug === 'nri' ) {
                    $new_template = locate_template( 'single-nri-gallery.php' );
                    if ( ! empty( $new_template ) ) {
                        return $new_template;
                    }
                }
            }
        }
    }
    return $template;
}
add_filter('template_include', 'assign_custom_post_type_template');


/*--------------------    faculty filter - Associated Faculty LIST for HOD   ----------------------------*/
function faculty_filter($query) {
    $current_user = wp_get_current_user();
  
    $page_name = basename($_SERVER['PHP_SELF']);
    if (is_admin() && current_user_can("list_faculty_user") && (!current_user_can( 'manage_options' ))) {

         if($page_name === "users.php"){

            $query->set('role', 'faculty');
            $query->set('meta_query', array(
                array(
                    'key'     => 'fac_hod',  
                    'value'   => $current_user->ID,
                    'compare' => '=',
                ),
            ));
         }  
    }
}

add_action('pre_get_users', 'faculty_filter');

/*--------------------    faculty filter - Associated Faculty LIST for HOD   ----------------------------*/
function faculty_filter_for_principal($query) {
    $current_user = wp_get_current_user();
  
    $page_name = basename($_SERVER['PHP_SELF']);
    if (is_admin() && current_user_can("list_faculty_for_principal") && (!current_user_can( 'manage_options' ))) {

         if($page_name === "users.php"){

            $query->set('role', 'faculty');
            $query->set('meta_query', array(
                array(
                    'key'     => 'fac_principal',  
                    'value'   => $current_user->ID,
                    'compare' => '=',
                ),
            ));
         }  
    }
}

add_action('pre_get_users', 'faculty_filter_for_principal');

/*-------------------- CF7 - Career skillset select field ---------------*/
remove_action( 'wpcf7_swv_create_schema', 'wpcf7_swv_add_select_enum_rules', 20, 2 );
add_filter('wpcf7_form_tag_data_option', function($n, $options, $args) {
    global $post;
    $skillset = array();
    if (in_array('skills', $options)){
        $terms = wp_get_post_terms($post->ID, 'skillset');
        $skillset[''] = 'Select';
        if(isset($terms) && (!empty($terms))){
        foreach ($terms as $term) {
            $skillset[$term->term_id] = $term->name;
        } }
        return $skillset;
    }
    return $n;
  }, 10, 3);
  
/*------------------Programm filter ---------------*/
  function filter_programmes() { 
	$paged = $_POST['page']; 
	$tax_query =  array(
		'relation' => 'IN',
	);
	$degree ='';
	if(isset($_POST['degree']) && ($_POST['degree']!='')){
		$deg_data = array(
				'taxonomy' => 'degree',
				'field' => 'slug',
				'terms' => $_POST['degree']
			);
		array_push($tax_query, $deg_data);
		$degree = $_POST['degree'];
	}
	$college ='';
	if(isset($_POST['college']) && ($_POST['college']!='')){
		$college_data = array(
				'taxonomy' => 'college',
				'field' => 'slug',
				'terms' =>$_POST['college']
				);
		array_push($tax_query, $college_data);
		$college = $_POST['college'];
	} 
    $args = array(
        'post_type' => 'programme',
        'tax_query' => $tax_query,
		'paged' => $paged,
		
    );

    $programmes = new WP_Query($args);

    if ($programmes->have_posts()) :
        while ($programmes->have_posts()) : $programmes->the_post(); ?>
		<div class="programmeList">
			<h2><a href="<?php the_permalink($programmes->pageID); ?>"><?php the_title(); ?></a></h2>
			<?php if ( have_rows('programme_details', $programmes->$pageID) ) : ?>
			<ul>
			<?php while ( have_rows('programme_details', $programmes->$pageID) ) : the_row(); ?>
				<li>
					<?php echo get_sub_field("title", $programmes->$pageID); ?>
					<span><?php echo get_sub_field("details", $programmes->$pageID); ?></span>
				</li>
				<?php endwhile; ?>
			</ul>
			<?php endif; ?>
			<a href="<?php the_permalink($programmes->pageID); ?>">View more</a>
			<?php $more_details = get_field('eligibility_criteria', $programmes->$pageID);
			if ($more_details) { ?>
				<div class="moreDetails">
					<?php echo get_field('eligibility_criteria', $programmes->$pageID); ?>
				</div>
			<?php } ?>
		</div>
    <?php endwhile; wp_reset_query(); ?>
        <?php if($programmes->max_num_pages > 1){ ?> 
        <div class="pagination paginate">
            <?php
                echo paginate_links(array(
                    'base' => str_replace(999999, '%#%', esc_url(get_pagenum_link(999999))),
                    'format' => '?paged=%#%',
                    'current' => $paged,
                    'total' => $programmes->max_num_pages,
                    'prev_next' => false,
                  //  'prev_text' => __('<img src="'.get_stylesheet_directory_uri().'/images/prev.svg" alt="prev" /> Prev'),
                  //  'next_text' => __('Next <img src="'.get_stylesheet_directory_uri().'/images/next.svg" alt="next" />'),
                ));
            ?> 
        </div>
        <?php } ?>
    <?php else: ?>
            <div class="no-posts"><?php echo "No programs to display!"; ?></div>
    <?php endif; 
    wp_reset_postdata();
    die();
}
add_action('wp_ajax_filter_programmes', 'filter_programmes');
add_action('wp_ajax_nopriv_filter_programmes', 'filter_programmes');

/*------------------Programm Jobs ---------------*/
function filter_jobs() { 
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1; 

    $tax_query = array(
        'relation' => 'AND', 
    );

    if (isset($_POST['skillset']) && !empty($_POST['skillset'])) {
        $tax_query[] = array(
            'taxonomy' => 'skillset',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['skillset']),
        );
    }

    if (isset($_POST['college']) && !empty($_POST['college'])) {
        $tax_query[] = array(
            'taxonomy' => 'college',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['college']),
        );
    }

    $meta_query = array(
        array(
            'key'     => 'job_closing_date',
            'value'   => date('Y-m-d'),
            'compare' => '>=',
            'type'    => 'DATE',
        ),
    );

    $args = array(
        'post_type'      => 'career',
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        'paged'          => $paged,
        'tax_query'      => $tax_query,
        'meta_query'     => $meta_query,
    );

    $careers = new WP_Query($args);

    if ($careers->have_posts()) : ?>
        <?php while ($careers->have_posts()) : $careers->the_post(); ?>
            <div class="programmeList careers">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php $content = get_the_content(); ?>
                <?php if ($content) { ?>
                    <div class="content">
                        <?php echo $content; ?>
                    </div>
                <?php } ?>
                <a href="<?php the_permalink(); ?>">Apply Now</a>
            </div>
        <?php endwhile; wp_reset_query(); ?>
        <?php if ($careers->max_num_pages > 1) { ?>
            <div class="pagination paginate">
                <?php
                echo paginate_links(array(
                    'base' => str_replace(999999, '%#%', esc_url(get_pagenum_link(999999))),
                    'format' => '?paged=%#%',
                    'current' => $paged,
                    'total' => $careers->max_num_pages,
                    'prev_next' => true,
                    'prev_text' => __('<img src="' . get_stylesheet_directory_uri() . '/images/prev.svg" alt="prev" /> Prev'),
                    'next_text' => __('Next <img src="' . get_stylesheet_directory_uri() . '/images/next.svg" alt="next" />'),
                ));
                ?>
            </div>
        <?php } ?>
    <?php else: ?>
        <div class="no-posts"><?php echo "No job openings to display!"; ?></div>
    <?php endif;

    wp_reset_postdata();
    die();
}
add_action('wp_ajax_filter_jobs', 'filter_jobs');
add_action('wp_ajax_nopriv_filter_jobs', 'filter_jobs');

/*-----------------------------------------------*/

//Change username

function modify_login_username_label() {
    ?>
    <script type="text/javascript">
        document.getElementById('user_login').placeholder = 'Employee ID';
        document.querySelector('label[for="user_login"]').innerHTML = 'Employee ID';
    </script>
    <?php
}
add_action( 'login_head', 'modify_login_username_label' );

function change_username_label( $translated_text, $text, $domain ) {
    if ( 'Username' === $text ) {
        $translated_text = 'Employee ID';
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_username_label', 10, 3 );

// Change 'Username' to 'Employee ID' on the login form
// Change 'Username' to 'Employee ID' in the profile.php page

// Add a description to the Username (Employee ID) field in user profile
function add_description_to_username_field() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Append description under the Username field
            $('#user_login').parent().append('<p class="description">Please note: Your employee ID will be your username for login.</p>');
        });
    </script>
    <?php
}
add_action('admin_footer', 'add_description_to_username_field');
//Add description to update button
function add_description_to_update_field() {
    
    // Get the current user object
    $current_user = wp_get_current_user();

    // Check if the current user has the 'faculty' role
    if (in_array('faculty', (array) $current_user->roles)) {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Append description after the "Update User" button (submit button)
                $('#submit').before('<p class="description">Please complete all the details before clicking the <strong>Update Profile</strong> button. The approved request will be forwarded to your HOD.</p>');
            });
        </script>
        <?php
    }
}
add_action('admin_footer', 'add_description_to_update_field');
// Add description to the Username field in registration form
function add_description_to_username_registration_form() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Append description under the Username field on the registration form
            $('#user_login').parent().append('<p class="description">Please use your Employee ID as the username.</p>');
        });
    </script>
    <?php
}
add_action('login_footer', 'add_description_to_username_registration_form');

// Add a description to the Email field in user profile
function add_description_to_email_field() {

	$current_user = wp_get_current_user();
    // Check if the current user has the role 'faculty'
    $is_faculty = in_array('faculty', $current_user->roles);
    // Only add this for the user profile page
    $screen = get_current_screen();
    if ($screen->id === 'profile' || $screen->id === 'user-edit') {
    ?>
        <script type="text/javascript">
			jQuery(document).ready(function($) {
					// Show this message for all other users
					$('#email').parent().append('<p class="description">Please note: Confirm your email address is from <strong>sriramachandra.edu.in.</strong></p>');
			});
    	</script>
    <?php
    }
}
add_action('admin_footer', 'add_description_to_email_field');

// Add description to the Username field in registration form
function add_description_to_email_registration_form() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Append description under the Username field on the registration form
            $('#user_email').parent().append('<p class="description">enter your official email address from <strong>sriramachandra.edu.in.</p>');
        });
    </script>
    <?php
}
add_action('login_footer', 'add_description_to_email_registration_form');

// Remove nickname field from user profile
function remove_user_nickname_field( $user ) {
    ?>
    <style>
        tr.user-nickname-wrap {
            display: none !important;
        }
    </style>
    <?php
}
add_action( 'show_user_profile', 'remove_user_nickname_field' );
add_action( 'edit_user_profile', 'remove_user_nickname_field' );


function custom_validate_user_profile_fields($errors, $update, $user) {
    // Check if we're updating the profile (not on registration)
    if ($update) {
        // Validate First Name
        if (empty($_POST['first_name'])) {
            $errors->add('first_name_error', __('Please enter your first name.'));
        }
        
        // Validate Last Name
        if (empty($_POST['last_name'])) {
            $errors->add('last_name_error', __('Please enter your last name.'));
        }
        
        // Validate Display Name
        if (empty($_POST['display_name'])) {
            $errors->add('display_name_error', __('Please enter your display name.'));
        }
    }
    return $errors;
}
add_action('user_profile_update_errors', 'custom_validate_user_profile_fields', 10, 3);

// Add 'Status' column to Users table for HOD role only
function add_user_status_column($columns) {
    // Get the current user
    $current_user = wp_get_current_user();

    // Check if the current user has the HOD role
    if (in_array('hod', (array) $current_user->roles)) {
        $columns['hod_status'] = __('Status');
    }

    return $columns;
}
add_filter('manage_users_columns', 'add_user_status_column');

// Populate 'Status' column with "Waiting for approval" initially, for HOD role only
function show_user_status_column_content($value, $column_name, $user_id) {
    // Get the current user
    $current_user = wp_get_current_user();

    // Check if the current user has the HOD role
    if (in_array('hod', (array) $current_user->roles) && $column_name == 'hod_status') {
        // Get the user's status meta field, default to 'Waiting for approval' if not set
        $status = get_user_meta($user_id, 'hod_status', true);
        if (!$status) {
            $status = 'Waiting for approval';
        }
        return $status;
    }

    return $value;
}
add_filter('manage_users_custom_column', 'show_user_status_column_content', 10, 3);

// Add 'Status' column to Users table for Principal role only
function add_user_status_column_principal($columns) {
    // Get the current user
    $current_user = wp_get_current_user();

    // Check if the current user has the HOD role
    if (in_array('principal', (array) $current_user->roles)) {
        $columns['principal_status'] = __('Status');
    }

    return $columns;
}
add_filter('manage_users_columns', 'add_user_status_column_principal');

// Populate 'Status' column with "Waiting for approval" initially, for principal role only
function show_user_status_column_content_principal($value, $column_name, $user_id) {
    // Get the current user
    $current_user = wp_get_current_user();

    // Check if the current user has the principal role
    if (in_array('principal', (array) $current_user->roles) && $column_name == 'principal_status') {
        // Get the user's status meta field, default to 'Waiting for approval' if not set
        $status = get_user_meta($user_id, 'principal_status', true);
        if (!$status) {
            $status = 'Waiting for approval';
        }
        return $status;
    }

    return $value;
}
add_filter('manage_users_custom_column', 'show_user_status_column_content_principal', 10, 3);
// Add 'Status' column to Users table for vc role only
function add_user_status_column_vc($columns) {
    // Get the current user
    $current_user = wp_get_current_user();

    // Check if the current user has the vc role
    if (in_array('vc', (array) $current_user->roles)) {
        $columns['vc_status'] = __('Status');
    }

    return $columns;
}
add_filter('manage_users_columns', 'add_user_status_column_vc');

// Populate 'Status' column with "Waiting for approval" initially, for HOD role only
function show_user_status_column_content_vc($value, $column_name, $user_id) {
    // Get the current user
    $current_user = wp_get_current_user();

    // Check if the current user has the vc role
    if (in_array('vc', (array) $current_user->roles) && $column_name == 'vc_status') {
        // Get the user's status meta field, default to 'Waiting for approval' if not set
        $status = get_user_meta($user_id, 'vc_status', true);
        if (!$status) {
            $status = 'Waiting for approval';
        }
        return $status;
    }

    return $value;
}
add_filter('manage_users_custom_column', 'show_user_status_column_content_vc', 10, 3);

// function custom_events_category_rewrite() {
//     add_rewrite_rule(
//         '^event-main/?$',
//         'index.php?eventDisplay=list&tribe_events_cat=event-main',
//         'top'
//     );
//     add_rewrite_rule(
//         '^iqac/?$',
//         'index.php?eventDisplay=list&tribe_events_cat=iqac',
//         'top'
//     );
// }
// add_action('init', 'custom_events_category_rewrite');

/*****************footer widget for menu's */
function custom_footer_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Menu 1 ', 'Sriher'),
        'id'            => 'footer-menu-widget1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-title">',
        'after_title'   => '</h4>',
    ));
	register_sidebar(array(
        'name'          => __('Footer Menu 2 ', 'Sriher'),
        'id'            => 'footer-menu-widget2',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-title">',
        'after_title'   => '</h4>',
    ));
	register_sidebar(array(
        'name'          => __('Footer Menu 3 ', 'Sriher'),
        'id'            => 'footer-menu-widget3',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-title">',
        'after_title'   => '</h4>',
    ));
	register_sidebar(array(
        'name'          => __('Footer Menu 4 ', 'Sriher'),
        'id'            => 'footer-menu-widget4',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'custom_footer_widgets_init');



// AJAX handler for degree options
add_action('wp_ajax_get_degree_options', 'get_degree_options');
add_action('wp_ajax_nopriv_get_degree_options', 'get_degree_options');

function get_degree_options() {
    $college_id = intval($_POST['college_id']);
    $child_terms = get_terms([
        'taxonomy' => 'college',
        'parent' => $college_id,
        'hide_empty' => false,
    ]);

    $terms_data = [];
    if (!is_wp_error($child_terms)) {
        foreach ($child_terms as $term) {
            $terms_data[] = [
                'term_id' => $term->term_id,
                'name' => $term->name
            ];
        }
    }

    wp_send_json_success($terms_data);
}


?>






