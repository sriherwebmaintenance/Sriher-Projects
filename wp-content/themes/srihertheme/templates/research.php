<?php
    /*
    Template Name: Research Landing
    */
    get_header();
    $pageID = get_the_ID();
    wp_enqueue_script('Swiper');
    wp_enqueue_script('ticker');
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

                var swiper = new Swiper(".grant-slider", {
                    slidesPerView: 3,
                    spaceBetween: 40,
                    breakpoints: {
                        2500: {
                            slidesPerView: 3,
                            spaceBetween: 40,
                        },
                        992: {
                            slidesPerView: 3.2,
                            spaceBetween: 30,
                        },
                        768: {
                            slidesPerView: 2.2,
                            spaceBetween: 30,
                        },
                        450: {
                            slidesPerView: 1.5,
                            spaceBetween: 20,
                        },
                        200: {
                            slidesPerView: 1.2,
                            spaceBetween: 20,
                        },
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $(".researchMenuButton").click(function() {
                    $('.researchMenuList').toggleClass("open");
                });

                $.simpleTicker($("#announcements"),{
                    'effectType':'roll',
                });
            });
        </script>
	<?php
    }
?>
<?php $banners = get_field('banner',$pageID); ?>
<?php if(isset($banners) && !empty($banners)) { ?>
<section class="researchBanner" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
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

<?php $research_menu = get_field('research_menu',$pageID); ?>
<?php if(isset($research_menu) && !empty($research_menu)) { ?>
    <div class="researchMenuBlock">
        <div class="container">
            <button type="button" class="researchMenuButton">
            <?php echo $research_menu[0]['menu_item']; ?>
            </button>
            <ul class="researchMenuList">
            <?php foreach ( $research_menu as $menu_item){ ?>
                <li>
                    <?php echo $menu_item['menu_item'];?>
                </li>
            <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>

<section class="researchUpdateBlock">
    <div class="container">
        <?php $researchSection = get_field('research_update_section',$pageID); ?>
        <h2><?php echo $researchSection['main_title']; ?></h2>

        <div class="announcementArea">
            <h4><?php echo $researchSection['announcement_title']; ?></h4>
            <div class="announcementBox">
            <?php $announcements = $researchSection['announcements'];
            if (isset($announcements) && !empty($announcements)) { ?>
                <div id="announcements" class="ticker">
                    <ul>
                    <?php foreach ( $announcements as $announcement){ ?>
                        <li><?php echo $announcement['announcement'];?></li>
                    <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            </div>
        </div>

        <div class="newsEventArea">
            <?php $eventSection = $researchSection['event']; ?>
            <div class="events">
                <h3><?php echo $eventSection['title']; ?></h3>
                <?php
                $post_args = array(
                    'post_type' => 'events',
                    'paged' => $paged,
                    'order' => 'DESC',
                    'posts_per_page' => 3,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'media-category',
                            'field' => 'term_id',
                            'terms' => array(136),
                            'operator' => 'NOT IN',
                        ),
                    ),
                );
                $post_query = new WP_Query( $post_args );
                if ( $post_query->have_posts()) { ?> 
                    <div class="boxContent" >
                    <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                    $postID = $post_query->post->ID; ?>
                        <a href="<?php echo get_the_permalink($postID); ?>" class="box" data-aos="fade-up" data-aos-duration="1000">
                            <div class="left">
                            <?php 
                            if(has_post_thumbnail($postID)&&(get_the_post_thumbnail($postID)!='')){
                            $post_thumb = get_the_post_thumbnail_url($postID); 
                            } ?>
                            <img src="<?php echo $post_thumb; ?>" alt="<?php echo get_the_title();?>">
                            </div>
                            <div class="right">
                                <?php 
                                $event_dates = get_field('events_dates');
                                if(isset($event_dates) && !empty($event_dates)) {
                                if($event_dates['event_date']) { ?>
                                    <h4><?php echo $event_dates['event_date'];?> - <?php echo $event_dates['event_start_time'];?>-<?php echo $event_dates['event_end_time'];?></h4>
                                <?php } }?>
                                <?php $content = get_the_content($postID);
                                $pattern = '/<p>(.*?)<\/p>/';
                                preg_match($pattern, $content, $paragraph);
                                if (!empty($paragraph)) {
                                    $firstParagraph = strip_tags(substr($paragraph[0], 0, '60'));
                                    echo '<p>' . $firstParagraph . '...</p>' ;
                                } ?>
                            </div>
                        </a>
                        <?php endwhile;?>
                    </div>
                <?php } ?>
                <?php echo $eventSection['cta_link']; ?>
            </div>
            <?php $newsSection = $researchSection['news']; ?>
            <div class="news">
                <h3><?php echo $newsSection['title']; ?></h3>
                <?php
                $post_args = array(
                    'post_type' => 'news',
                    'paged' => $paged,
                    'order' => 'DESC',
                    'posts_per_page' => 2,
                );
                $post_query = new WP_Query( $post_args );
                if ( $post_query->have_posts()) { ?> 
                    <div class="boxContent">
                    <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                    $postID = $post_query->post->ID; ?>
                        <a href="<?php echo get_the_permalink($postID); ?>" class="box">
                            <div class="left">
                            <?php 
                            if(has_post_thumbnail($postID)&&(get_the_post_thumbnail($postID)!='')){
                            $post_thumb = get_the_post_thumbnail_url($postID); 
                            } ?>
                                <img src="<?php echo $post_thumb; ?>" alt="<?php echo get_the_title();?>">
                            </div>
                            <div class="right">
                                <h4><?php echo get_the_date('d F Y'); ?></h4>
                                <?php $content = get_the_content($postID);
                                $pattern = '/<p>(.*?)<\/p>/';
                                preg_match($pattern, $content, $paragraph);
                                if (!empty($paragraph)) {
                                    $firstParagraph = strip_tags(substr($paragraph[0], 0, '95'));
                                    echo '<p>' . $firstParagraph . '...</p>' ;
                                } ?>
                            </div>
                        </a>
                        <?php endwhile;?>
                    </div>
                <?php } ?>
                <?php echo $newsSection['cta_link']; ?>
            </div>
        </div>
    </div>
</section>

<?php $grantSection = get_field('research_grants_section',$pageID);
if ($grantSection) { ?>
    <section class="grantBlock">
        <div class="container">
            <div class="grantHeader">
                <h2><?php echo $grantSection['title']; ?></h2>
                <?php echo $grantSection['cta_link']; ?>
            </div>
            <?php
            $grantBanners = $grantSection['grants'];
            if(isset($grantBanners) && !empty($grantBanners)) { ?>
            <div class="swiper grant-slider">
                <div class="swiper-wrapper">
                <?php foreach($grantBanners as $grantBanner) { ?>
                    <div class="swiper-slide">
                        <div class="grantBox">
                            <div class="grantThumb">
                                <?php if($grantBanner['featured_image']){ ?>
                                    <img src="<?php echo $grantBanner['featured_image']; ?>" alt="Sriher">
                                <?php } else { ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                <?php } ?>
                            </div>
                            <div class="grantCaption">
                                <?php echo $grantBanner['details'] ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
            <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>

<?php $publicationSection = get_field('research_publications_section',$pageID);
if ($publicationSection) { ?>
    <section class="publicationBlock">
        <div class="container">
            <div class="publicationHeader">
                <h2><?php echo $publicationSection['title'] ?></h2>
                <?php echo $publicationSection['cta_link'] ?>
            </div>
            <div class="publicationArea">
            <?php
                $args = array(
                    'paged' => $paged,
                    'posts_per_page'   => 6,
                    'post_type'      => 'publication',
                );
                $publication = new WP_Query($args);
            ?>
            <?php if($publication->have_posts()) : ?>
            <?php while($publication->have_posts()) : $publication->the_post() ?>
                <a href="<?php the_permalink($publication->ID); ?>" class="publicationBox">
                    <div class="publicationThumb" data-aos="fade-up" data-aos-duration="1000">
                    <?php 
                        if(has_post_thumbnail($publication->ID)&&(get_the_post_thumbnail($publication->ID)!='')){
                        $post_thumb = get_the_post_thumbnail_url($publication->ID); ?>
                            <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                        <?php } else { ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/newsletterThumb.svg" alt="Sriher" class="default" />
                        <?php } ?>
                    </div>
                    <div class="publicationCaption" data-aos="fade-up" data-aos-duration="1000">
                        <?php $content = get_the_content($publication->ID);
                        $pattern = '/<p>(.*?)<\/p>/';
                        preg_match($pattern, $content, $paragraph);
                        if (!empty($paragraph)) {
                            echo $paragraph[0];
                        } ?>
                    </div>
                </a>
            <?php endwhile ?>
            <?php endif; ?>
            </div>
        </div>
    </section>
<?php } ?>

<?php $job_details = get_field('research_job_section',$pageID); 
if(isset($job_details) && !empty($job_details)){ ?>
<section class="researchJobBlock" style="background-image: url(<?php echo $job_details['background'];?>);" >
    <div class="container">
    <span class="yellowTriangle" data-aos="fade-down" data-aos-duration="1500" data-aos-delay="300"></span>
    
        <div class="researchJobArea">
            <?php if($job_details['job_details']) { ?>
                <div class="researchJobLeft">
                    <?php echo $job_details['job_details'];?>
                </div>
            <?php } ?>
            <?php if($job_details['cta_link']) {?>
                <div class="researchJobRight">
                    <?php echo $job_details['cta_link'];?>
                </div>
            <?php } ?>
        </div>
    
    </div>
</section>
<?php } ?>

<?php get_footer(); ?>