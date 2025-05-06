<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<meta name="theme-color" content="#1D35AA"/>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/styles.css?version=204" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" type="text/css" media="screen" />
	<style>
		<?php if($variable=get_field('custom_css',get_the_ID())){echo $variable;}?>
	</style>
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bitter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
	<script>
		var site_url = '<?php echo site_url(); ?>';
		var directory_url = "<?php bloginfo('template_directory'); ?>";
		window.stylesheet_directory_uri = '<?php echo get_stylesheet_directory_uri(); ?>';
		var theme_url = '<?php echo get_template_directory_uri(); ?>';
	    var directory_url = "<?php bloginfo('template_directory'); ?>";
	    var ajax_url = "<?php echo admin_url()."/admin-ajax.php"; ?>";
	</script>
	<?php global $wp_query; global $post; ?>
	<style>
	<?php if(get_field('custom_css', 'option')!='') {
	 echo get_field('custom_css', 'option'); } 
	 if($post->ID){
	 if(get_field('custom_css',$post->ID)) {
 		echo get_field('custom_css',$post->ID); } } ?> 
	</style>
	<?php if($post->ID){
	 if(get_field('custom_js_header',$post->ID)) {
 		echo get_field('custom_js_header',$post->ID); } } ?> 
	<?php echo get_field('google_analytics','option'); ?>
</head>
<body <?php body_class(); ?>>
<?php $pageID = get_the_ID(); ?>
<?php if((is_front_page() || is_home())) { ?>
<div class="preloader">
	<span class="loaderLogo">
		<span>
			<?php 
			$preloader_logo = get_field('header_logo','option');
			if ( $preloader_logo ) { ?>
				<img src="<?php echo $preloader_logo;?>" alt="<?php bloginfo('name'); ?>" />
			<?php } else { ?>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" />
			<?php } ?>
		</span>
	</span>
</div>
<?php } ?>
<header class="header">
	<div class="top-menu">
		<div class="top-menu-items">
			<?php  wp_nav_menu(array( 'container' => '', 'theme_location' => 'top_menu' )); ?>
		</div>
		<div class="top-toggler"></div>
	</div>
	<div class="header-content">
		<div class="logo">
			<a href="<?php echo esc_url( home_url( '/' )); ?>">
				<?php 
				$preloader_logo = get_field('header_logo','option');
				if($preloader_logo!='') {?>
					<img src="<?php echo $preloader_logo;?>" alt="logo.svg">
				<?php } else {?>
					<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="logo.png">
				<?php } ?>
			</a>
		</div><!-- logo -->
		<div class="menu-items">
			<div class="toggler">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<?php  wp_nav_menu(array( 'container' => '', 'theme_location' => 'mobilemenu' )); ?>
			<?php  wp_nav_menu(array( 'container' => '', 'theme_location' => 'header_menu' )); ?>
		</div>	

		
		<div class="menu-right">
			<div class="search-container">

				<?php echo get_search_form(); 
				// if(get_search_query() == '') { 
				// 	echo "no input";
				// } 
				?>
			</div>
			<?php $admission = get_field('header_admission_text_and_url','option');
			if(isset($admission) && (!empty($admission))) { ?>
				<a href="<?php echo $admission['url'];?>" class="admission"><?php echo $admission['text'];?></a>
			<?php } ?>
		</div>
	</div>
	<div class="overlay"></div>
</header>