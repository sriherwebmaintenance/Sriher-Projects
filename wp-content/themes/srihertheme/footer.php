<?php if (!is_front_page() && !is_home()) {?>
	<?php $admn_ftr = get_field('admission_section_ftr','option') ;
	if(isset($admn_ftr) && !empty(array_filter($admn_ftr))) { ?>
	<section class="cmn-admission-banner">
		<div class="cmn-admission-area container">
			<div class="cmn-admission-wrap">
				<?php if($admn_ftr['title']) { ?><div class="txtblk"><h2><?php echo $admn_ftr['title'];?></h2></div><?php } ?>
				<?php if($admn_ftr['button_url']) { ?>
					<div class="btnblk">
						<a href="<?php echo $admn_ftr['button_url'];?>"><?php echo $admn_ftr['button_text'];?></a>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } } ?>
	<footer>
		<div class="container">
			<div class="footer-area">
				<span class="yellow" data-aos="fade-down" data-aos-duration="1500" data-aos-delay="300"></span>
				<span class="red" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300"></span>
				<div class="footer-wrap">

					<div class="footer-left">
						<div class="footer-logo">
							<?php if(get_field('logo_footer','option')){?>
								<img src="<?php echo get_field('logo_footer','option');?>" alt="footer-logo">
							<?php } else {?>
								<img src="<?php echo get_template_directory_uri(); ?>/images/footer/footer-logo.svg" alt="footer-logo">
							<?php } ?>
						</div>
						<?php if(get_field('footer_text','option')) { ?>
							<p><?php echo get_field('footer_text','option');?></p>
						<?php } ?>
						
						<div class="ftr-left-wrap">
							<div class="ftr-lft-lnk">
								<?php echo wp_nav_menu( array('theme_location'=>'ftr_left_menu1') );?>
							</div>
							<div class="ftr-lft-lnk">
								<?php echo wp_nav_menu( array('theme_location'=>'ftr_left_menu2') );?>
							</div>
							
						</div>
						<div class="social-links">
							<?php if(get_field('youtube_url','option')){?>
								<a href="<?php echo get_field('youtube_url','option');?>" target="blank"><img src="<?php echo get_template_directory_uri(); ?>/images/footer/yt.svg" alt=""></a>
							<?php } if(get_field('instagram_url','option')){?>
								<a href="<?php echo get_field('instagram_url','option');?>" target="blank"><img src="<?php echo get_template_directory_uri(); ?>/images/footer/ig.svg" alt=""></a>
							<?php } if(get_field('facebook_url','option')){?>
								<a href="<?php echo get_field('facebook_url','option');?>" target="blank"><img src="<?php echo get_template_directory_uri(); ?>/images/footer/fb.svg" alt=""></a>
							<?php } if(get_field('linkdin_url','option')){?>
								<a href="<?php echo get_field('linkdin_url','option');?>" target="blank"><img src="<?php echo get_template_directory_uri(); ?>/images/footer/in.svg" alt=""></a>
							<?php } ?>
						</div>
						
					</div>
					<div class="footer-right">
						<div class="content-menu footer-menu1">
						<?php if (is_active_sidebar('footer-menu-widget1')) : ?>
							<div class="footer-widgets">
								<?php dynamic_sidebar('footer-menu-widget1'); ?>
							</div>
						<?php endif; ?>
						</div>
						<div class="content-menu footer-menu2">
							<?php if (is_active_sidebar('footer-menu-widget2')) : ?>
								<div class="footer-widgets">
									<?php dynamic_sidebar('footer-menu-widget2'); ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="content-menu footer-menu3">
							<?php if (is_active_sidebar('footer-menu-widget3')) : ?>
								<div class="footer-widgets">
									<?php dynamic_sidebar('footer-menu-widget3'); ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="content-menu footer-menu4">
						<?php if (is_active_sidebar('footer-menu-widget4')) : ?>
							<div class="footer-widgets">
								<?php dynamic_sidebar('footer-menu-widget4'); ?>
							</div>
						<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<p><?php if(get_field('copy_right','option')) { echo get_field('copy_right','option'); }?></p>
				<!-- <p>Site by <a href="https://acodez.in/" id="acodez" target="blank">Acodez</a></p> -->
			</div>
		</div>
	</footer>

	<?php wp_footer();
	if( get_field('custom_javascripts', 'option') ):?>
			<?php echo get_field('custom_javascripts', 'option'); ?>
	<?php endif; wp_reset_query(); global $post; 
	if($post->ID){
	if(get_post_meta($post->ID,'custom_javascripts',true)!=''){
  		echo get_post_meta($post->ID,'custom_javascripts',true);
	} } ?>
</body>
</html>