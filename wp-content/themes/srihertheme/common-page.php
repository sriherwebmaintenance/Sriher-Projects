<?php
    get_header();
    $pageID = get_the_ID();
    $parent_id = null;
    if(has_post_parent($pageID)) {
        $parent_id = $post->post_parent;
    }
    wp_enqueue_script('Swiper');
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
        <script>
            $(document).ready(function() {
                var swiper = new Swiper(".banner-slider", {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    pagination: {
                        el: ".swiper-pagination-1",
                        clickable: "true",
                    },
                    loop:true,
                });
            });
        </script>
	<?php
    }
?>

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
<div class="contentBlock">
    <div class="container">
        <div class="contentArea">
            <div class="contentFull p-55">
                <?php $banner_images = get_field('banner_images',$pageID);
                $banner_content = get_field('banner_content',$pageID);

                if ($banner_content) { ?>
                <div class="bannerGrid">
                <?php } ?>

                <?php if(isset($banner_images) && !empty($banner_images)){?>
                    <div class="bannerSlide">
                        <div class="swiper banner-slider">
                            <div class="swiper-wrapper">
                                <?php foreach ($banner_images as $image): ?>
                                <div class="swiper-slide">
                                    <div class="bannerBox">
                                        <img src="<?php echo $image["url"]; ?>" alt="<?php echo $image["alt"]; ?>"
                                        width="280" height="160" fetchpriority="high" />
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="bannerPagination swiper-pagination-1"></div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($banner_content) { ?>
                    <div class="bannerDesc"><?php echo $banner_content; ?></div>
                </div>
                <?php } ?>

                <?php $column_content = get_field('column_content',$pageID);
                if(isset($column_content) && !empty($column_content)){?>
                <div class="columnGrid">
                    <?php while ( have_rows('column_content', $pageID) ) : the_row(); ?>
                    <div class="columnGridBox">
                        <?php echo get_sub_field("description"); ?>
                    </div>
                    <?php endwhile ?>
                </div>
                <?php } ?>
                <?php $download_button = get_field('download_buttn', $pageID);
                if ($download_button) { ?>
                    <div class="downloadBtn">
                        <?php if ($download_button['button_url']) { ?>
                            <a href="<?php echo $download_button['button_url']; ?>"><?php echo $download_button['button_title']; ?></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>