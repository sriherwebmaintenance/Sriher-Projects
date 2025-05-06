<?php
    get_header();
    $pageID=get_the_id();
    wp_enqueue_script('magnificPopup');
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
        <script>
            $(document).ready(function () {
                $(".sidebarDrop").click(function() {
                    $('.menu').toggleClass("open");
                });
            });
            AOS.init({
                once:true,
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.popup-gallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                tLoading: 'Loading image #%curr%...',
                mainClass: 'mfp-img-mobile',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0,1]
                },
                image: {
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                    titleSrc: function(item) {
                    return item.el.attr('title');
                    }
                }
                });
            });
      </script>
	<?php
    }
?>

<div class="wrapper gallery-page">
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
                <div class="contentLeft">
                    <div class="sidebarDrop">Photo Gallery</div>
                    <?php 
                        $selected_side_menu = 'about_side_bar_menu';
                        wp_nav_menu(array('theme_location' => $selected_side_menu));
                    ?>
                </div>
                <div class="contentRight">
                    <div class="contentHeader">
                        <h2><?php echo get_the_title(); ?></h2>
                        <a href="<?php echo esc_url( home_url( '/photo-gallery/' )); ?>">
                            Back to Gallery
                        </a>
                    </div>

                    <?php if (have_posts()): while (have_posts()): the_post(); ?>
                    <div class="galleryGrid popup-gallery">
                        <?php $collection = get_field("collection", $pageID); ?>
                        <?php if(isset($collection) && !empty($collection)) { ?>
                            <?php $i=0; foreach ($collection as $image): ?>
                            <a href="<?php echo $image["url"]; ?>" title="<?php echo $image["alt"]; ?>" class="galleryItem">
                                <img src="<?php echo $image["url"]; ?>" alt="<?php echo $image["alt"]; ?>"
                                width="280" height="160" fetchpriority="high" />
                            </a>
                            <?php $i++; endforeach; ?>
                        <?php } else{ ?>
                            <div class="no-cnt">No Collection Found</div>
                        <?php } ?>
                    </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>