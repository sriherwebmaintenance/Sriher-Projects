<?php
    /*
    Template Name: IQAC
    */
    get_header();
    $pageID = get_the_ID();
    wp_enqueue_style('tabs');
    wp_enqueue_script('Swiper');
    wp_enqueue_script('tabs');
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
        <script>
            $(document).ready(function() {
                var swiper = new Swiper(".banner-slider", {
                    slidesPerView: 1,
                    spaceBetween:0,
                    speed: 1500,
                    // autoplay: false,
                    autoplay: {
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination-1",
                        clickable: "true",
                    },
                    loop:true,
                });

                var swiper = new Swiper(".accreditation-slider", {
                    slidesPerView: 7,
                    spaceBetween: 20,
                    pagination: {
                        el: ".swiper-pagination-1",
                        clickable: "true",
                    },
                    breakpoints: {
                        2500: {
                            slidesPerView: 7,
                        },
                        992: {
                            slidesPerView: 7,
                        },
                        768: {
                            slidesPerView: 5,
                        },
                        450: {
                            slidesPerView: 4,
                        },
                        200: {
                            slidesPerView: 3,
                        },
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $(".menu-item-has-children > a").click(function(e) {
                    e.preventDefault();
                    $(this).parent().toggleClass("active");
                    $(this).siblings().slideToggle();
                });
                $(".sidebarDrop").click(function() {
                    $('.menu').toggleClass("open");
                });
                $(".menu li a").click(function() {
                    $('.menu').toggleClass("open");
                    var currentText = $(this).text();
                    $('.sidebarDrop').text('');
                    $('.sidebarDrop').text(currentText);
                });
            });
        </script>
        <script>
            jQuery(document).ready(function ($) {
                $('#menu-iqac-menu li a').on('click', function (e) {
                    var subpage_id = $(this).data('page-id');
                    if(subpage_id){
                        e.preventDefault();
                        $('#menu-iqac-menu li').removeClass('current_page_item');
                        $(this).parent().addClass('current_page_item');
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'load_iqac_posts',
                                subpage_id: subpage_id,
                            },
                            success: function (response) {
                                $('.iqacContent').html(response);
                                window.KBTabs.init();
                            },
                            error: function (error) {
                                console.log(error);
                            },
                        });
                    }
                });
            });
        </script>
	<?php
    }
?>

<?php $banners = get_field('banner',$pageID); ?>
<?php if(isset($banners) && !empty($banners)) { ?>
<section class="researchBanner iqacBanner" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
    <div class="container">
        <div class="swiper banner-slider">
            <div class="swiper-wrapper">
            <?php foreach($banners as $banner) { ?>
                <div class="swiper-slide">
                    <div class="researchBannerArea">
                        <div class="researchBannerLeft" data-aos="fade-up" data-aos-duration="1000">
                        <?php echo $banner['banner_content'];?>
                        </div>
                        <div class="researchBannerRight">
                            <div class="researchBannerImg">
                                <img src="<?php echo $banner['banner_image'];?>" alt="Sriher">
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="researchBannerPagination swiper-pagination-1"></div>
        </div>
    </div>
</section>
<?php } ?>

<section class="iqacContentBlock">
    <div class="container" data-aos="fade-up" data-aos-duration="1000">
    <?php if (have_posts()) : the_post(); ?>
        <?php the_content()?>
    <?php endif;  ?>
    </div>
</section>

<section class="iqacAccreditation">
    <div class="container">
    <?php $accreditaion = get_field('iqac_accreditation',$pageID); ?>
    <?php if(isset($banners) && !empty($banners)) { ?>
        <h2><?php echo $accreditaion['title'] ?></h2>
        <?php $accreditationSlider = $accreditaion['accreditation_logos'];
            if(isset($accreditationSlider) && !empty($accreditationSlider)) { ?>
            <div class="swiper accreditation-slider">
                <div class="swiper-wrapper">
                <?php foreach($accreditationSlider as $accreditationLogo) { ?>
                    <div class="swiper-slide">
                        <div class="accreditationBox">
                            <img src="<?php echo $accreditationLogo; ?>" alt="Sriher">
                        </div>
                    </div>
                <?php } ?>
                <div class="swiper-pagination-1"></div>
                </div>
            </div>
            <?php } ?>
    <?php } ?>
    </div>
</section>

<section class="iqacMainBlock">
    <div class="container">
        <?php $team = get_field('iqac_team',$pageID); ?>
        <?php if(isset($team) && !empty($team)) { ?>
            <div class="iqcaTeamArea iqacRow"  data-aos="fade-up" data-aos-duration="1000">
                <div class="iqacCol-6">
                    <?php echo $team['team_content'] ?>
                </div>
                <div class="iqacCol-6">
                    <img src="<?php echo $team['team_photo'] ?>" alt="IQAC Team" width="" height="" />
                </div>
            </div>
        <?php } ?>
    
        <?php $details = get_field('iqac_details',$pageID); ?>
        <?php if(isset($details) && !empty($details)) { ?>
            <div class="iqcaContentArea">
                <h2><?php echo $details['title'] ?></h2>
                <div class="iqacMenu">
                    <div class="sidebarDrop">Guidelines</div>
                    <?php // wp_nav_menu(array('theme_location' => 'iqac_menu')); 
                    wp_nav_menu( array( 'theme_location' => 'iqac_menu',
                        'walker' => new Custom_Walker_iqac_menu()
                    ) ); ?>
                    
                </div>
                <div class="iqacContentDetails">
                    <div class="iqacContent">
                    <?php $content = get_post_field('post_content', 1729);
                        echo $content;
                    ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php
            $args = array(
                'paged' => $paged,
                'posts_per_page'   => 3,
                'post_type'      => 'events',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'media-category',
                        'field' => 'term_id',
                        'terms' => array(136),
                    ),
                ),
            );
            $events = new WP_Query($args);
        ?>
        <?php if($events->have_posts()) : ?>
            <div class="eventBlock iqcaTeamArea">
                <?php $eventSettings = get_field('event_section',$pageID);
                if (isset($eventSettings) && !empty($eventSettings)) { ?>
                    <div class="eventHeader">
                        <h2><?php echo $eventSettings['title']; ?></h2>
                        <?php if($eventSettings['cta_link']){?>
                            <a href="<?php echo $eventSettings['cta_link']; ?>">
                                View All <?php echo $eventSettings['title']; ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="eventArea">
                    <?php while($events->have_posts()) : $events->the_post() ?>
                        <a href="<?php the_permalink($events->ID); ?>" class="pressBox">
                            <div class="pressThumb"  data-aos="fade-up" data-aos-duration="1000">
                                <?php 
                                if(has_post_thumbnail($events->ID)&&(get_the_post_thumbnail($events->ID)!='')){
                                $post_thumb = get_the_post_thumbnail_url($events->ID); ?>
                                    <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                <?php } else { ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                <?php } ?>
                            </div>
                            <div class="pressCaption"  data-aos="fade-up" data-aos-duration="1000">
                                <p><?php the_title(); ?></h4>

                                <?php $content = get_the_content($events->ID);
                                $pattern = '/<p>(.*?)<\/p>/';
                                preg_match($pattern, $content, $paragraph);
                                if (!empty($paragraph)) {
                                    $firstParagraph = strip_tags(substr($paragraph[0], 0, '200'));
                                    echo '<p>' . $firstParagraph . '...</p>' ;
                                } ?>
                            </div>
                            </a>
                    <?php endwhile ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
            $args = array(
                'paged' => $paged,
                'posts_per_page'   => 4,
                'post_type'      => 'news',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'media-category',
                        'field' => 'term_id',
                        'terms' => array(136),
                    ),
                ),
            );
            $news = new WP_Query($args);
        ?>
        <?php if($news->have_posts()) : ?>
            <div class="newsLetterBlock iqcaTeamArea">
                <?php $newsSettings = get_field('news_section',$pageID);
                if (isset($newsSettings) && !empty($newsSettings)) { ?>
                    <div class="newsLetterHeader">
                        <h2><?php echo $newsSettings['title']; ?></h2>
                        <?php if($newsSettings['cta_link']){?>
                        <a href="<?php echo $newsSettings['cta_link']; ?>">
                            View All <?php echo $newsSettings['title']; ?>
                        </a>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="newsLetterArea">
                    <?php while($news->have_posts()) : $news->the_post() ?>
                        <a href="<?php the_permalink($news->ID); ?>" class="newsLetterBox">
                            <div class="newsLetterThumb"  data-aos="fade-up" data-aos-duration="1000">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/newsletterThumb.svg" alt="Sriher" class="default" />
                            </div>
                            <div class="newsLetterCaption"  data-aos="fade-up" data-aos-duration="1000">
                                <?php $duration = get_field('duration', $news->ID);
                                if($duration) { ?>
                                    <p><strong><?php echo $duration['start_date'] . ' to ' . $duration['end_date'] ; ?></strong></p>
                                <?php } else { ?>
                                    <p><strong><?php echo get_the_date('d M Y'); ?></strong></p>
                                <?php } ?>
                                <p><?php the_title(); ?></p>
                            </div>
                        </a>
                    <?php endwhile ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>