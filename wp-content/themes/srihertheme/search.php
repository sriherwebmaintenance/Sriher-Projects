<?php
if (empty(get_search_query())) {
    wp_redirect(home_url('/'));
    exit;
}
?>

<?php get_header();
add_action('wp_footer','scripts',25);
function scripts(){ ?>
    <script>
        $(document).ready(function(){
            blogfixHeight();
        });

        function blogfixHeight() {
            var cHeight = '';
            $('.search_post').css("height", "auto");
            $('.search_post').each(function () {
                cHeight = cHeight > $(this).height() ? cHeight : $(this).height();
            });
            $('.search_post').each(function () {
                $(this).height(cHeight);
            })
        }
    </script><?php
}
if ( get_field( 'default_banner', 'option' ) ) {
	$style = 'style="background-image:url('.get_field( 'default_banner', 'option' ).');"';
} else {
	$style = 'style="background-image:url('.get_template_directory_uri().'/images/banner01.jpg );"'; 
}

$ban_title ='Search Page'; ?>
<section class="singleBannerBlock" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
    <div class="container">
        <div class="singleBannerArea">
            <h1><?php echo $ban_title; ?></h1>
        </div>
    </div>
</section>
<section class="commonContentBlock">
    <div class="container">
		<h6>
			Search Results For : 
			<span>
				<?php echo get_search_query(); ?>
			</span>
		</h6>
		<div class="search_result_area clearfix">
			<?php if (have_posts()): 
				while (have_posts()) : the_post(); ?>
					<div class="search_result_box">
						<h2>
							<a href="<?php the_permalink(); ?>">
								<?php echo substr( get_the_title(), 0, 50 ); ?>
								<?php if( strlen(get_the_title()) > 50 ) { echo '...'; } ?>
							</a>
						</h2>
						<?php if(get_the_excerpt()) { ?>
						<p>
							<?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?>
						</p>
						<?php } ?>
						<a href="<?php the_permalink(); ?>">Read More</a>
					</div>
				<?php endwhile;?> 
				<div class="navigation">
					<div><?php next_posts_link('&laquo; Previous') ?></div>
					<div><?php previous_posts_link('Next &raquo;') ?></div>
				</div>
			<?php else : ?>
				<div class="no_search_result">
					<h2 class="center">Not Found</h2>
					<p class="center">Sorry, but you are looking for something that isn't here.</p>
				</div>
			<?php endif; ?>
		</div>
   </div>
</section>
</div>
<?php get_footer(); ?>