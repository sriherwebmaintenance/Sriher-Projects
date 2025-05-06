<?php get_header();?>

<section class="bannerBlock">
    <div class="bannerBg" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');"></div>
    <div class="container">
        <div class="bannerArea">
            <?php $banner_title = get_banner_title(); ?>
            <h1><?php echo esc_html($banner_title); ?></h1>
            <?php custom_breadcrumbs(); ?>
        </div>
    </div>
</section>
<section class="contentBlock">
    <div class="container">
        <div class="contentArea">
            <div class="contentFull">
            <?php if(have_posts()):
                while(have_posts()): the_post();?>
                    <div class="posts">
                        <h2><a href="<?php echo get_the_permalink();?>"><?php the_title();?></a></h2>
                        <p><?php echo get_the_excerpt();?></p>
                    </div><?php 
                endwhile;
            endif;?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>