<?php
    /*
    Template Name: News and Events
    */
    get_header();
    $pageID = get_the_ID();
    wp_enqueue_script('Swiper');
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

                var swiper = new Swiper(".press-slider", {
                    slidesPerView: 3,
                    spaceBetween: 40,
                    breakpoints: {
                        2500: {
                            slidesPerView: 3,
                            spaceBetween: 40,
                        },
                        992: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 30,
                        },
                        450: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        200: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $(".sidebarDrop").click(function() {
                    $('.menu').toggleClass("open");
                });
            });
        </script>
	<?php
    }
?>

<section class="sliderBlock" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
    <div class="container">
        <div class="swiper banner-slider">
            <div class="swiper-wrapper">
                <?php
                $args = array(
                    'posts_per_page'   => 4,
                    'post_type'     => 'news',
                    'meta_query'    => array(
                        array(
                            'key'       => 'is_featured',
                            'value'     => 1,
                            'compare'   => '=',
                        )
                    ),
                );
                $featured = new WP_Query($args);
                ?>
                <?php if($featured->have_posts()) : ?>
                <?php while($featured->have_posts()) : $featured->the_post() ?>
                <div class="swiper-slide">
                    <div class="sliderArea">
                        <div class="sliderLeft">
                            <p>Featured News</p>
                            <h2><?php the_title(); ?></h2>
                            <?php $newsUrl = get_field('news_page_url',$pageID);
                            if($newsUrl){?>
                            <a href="<?php echo $newsUrl; ?>">Read More</a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">Read More</a>
                            <?php } ?>
                        </div>
                        <div class="sliderRight">
                            <div class="sliderImg">
                                <?php 
                                if(has_post_thumbnail($featured->ID)&&(get_the_post_thumbnail($featured->ID)!='')){
                                $post_thumb = get_the_post_thumbnail_url($featured->ID); ?>
                                    <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                <?php } else { ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile ?>
                <?php endif; ?>
            </div>
            <div class="sliderPagination swiper-pagination-1"></div>
        </div>
    </div>
</section>

<section class="eventBlock">
    <div class="container">
        <?php $newsSettings = get_field('news_page_settings',$pageID);
        if (isset($newsSettings) && !empty($newsSettings)) { ?>
        <div class="eventHeader">
            <h2><?php echo $newsSettings['title']; ?></h2>
            <?php if($newsSettings['url']){?>
            <a href="<?php echo $newsSettings['url']; ?>">
                View All <?php echo $newsSettings['title']; ?>
            </a>
            <?php } else { ?>
                <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">
                    View All <?php echo $newsSettings['title']; ?>
                </a>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="eventArea">
        <?php
            $args = array(
                'paged' => $paged,
                'posts_per_page'   => 6,
                'post_type'      => 'news',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'media-category',
                        'field' => 'term_id',
                        'terms' => array(136),
                        'operator' => 'NOT IN',
                    ),
                ),
            );
            $news = new WP_Query($args);
        ?>
        <?php if($news->have_posts()) : ?>
        <?php while($news->have_posts()) : $news->the_post() ?>
            <div class="eventBox">
                <a href="<?php the_permalink($news->ID); ?>" class="eventThumb">
                    <?php 
                    if(has_post_thumbnail($news->ID)&&(get_the_post_thumbnail($news->ID)!='')){
                    $post_thumb = get_the_post_thumbnail_url($news->ID); ?>
                        <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                    <?php } else { ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                    <?php } ?>
                </a>
                <div class="eventCaption">
                    <h4><a href="<?php the_permalink($news->ID); ?>"><?php the_title(); ?></a></h4>
                    <?php $content = get_the_content($news->ID);
                    $pattern = '/<p>(.*?)<\/p>/';
                    preg_match($pattern, $content, $paragraph);
                    if (!empty($paragraph)) {
                        $firstParagraph = strip_tags(substr($paragraph[0], 0, '200'));
                        echo '<p>' . $firstParagraph . '...</p>' ;
                    } ?>

                    <a href="<?php the_permalink($news->ID); ?>">Read More</a>
                </div>
            </div>
        <?php endwhile ?>
        <?php endif; ?>
        </div>
    </div>
</section>

<section class="eventBlock">
    <div class="container">
        <?php $eventSettings = get_field('events_page_settings',$pageID);
        if (isset($eventSettings) && !empty($eventSettings)) { ?>
        <div class="eventHeader">
            <h2><?php echo $eventSettings['title']; ?></h2>
            <?php if($eventSettings['url']){?>
            <a href="<?php echo $eventSettings['url']; ?>">
                View All <?php echo $eventSettings['title']; ?>
            </a>
            <?php } else { ?>
                <a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">
                    View All <?php echo $eventSettings['title']; ?>
                </a>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="eventArea">
        <?php
            $args = array(
                'paged' => $paged,
                'posts_per_page'   => 6,
                'post_type'      => 'events',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'media-category',
                        'field' => 'term_id',
                        'terms' => array(136),
                        'operator' => 'NOT IN',
                    ),
                ),
            );
            $events = new WP_Query($args);
        ?>
        <?php if($events->have_posts()) : ?>
        <?php while($events->have_posts()) : $events->the_post() ?>
            <div class="eventBox">
                <a href="<?php the_permalink($events->ID); ?>" class="eventThumb">
                    <?php 
                    if(has_post_thumbnail($events->ID)&&(get_the_post_thumbnail($events->ID)!='')){
                    $post_thumb = get_the_post_thumbnail_url($events->ID); ?>
                        <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                    <?php } else { ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                    <?php } ?>
                    </a>
                <div class="eventCaption">
                    <h4><a href="<?php the_permalink($events->ID); ?>"><?php the_title(); ?></a></h4>

                    <?php $content = get_the_content($events->ID);
                    $pattern = '/<p>(.*?)<\/p>/';
                    preg_match($pattern, $content, $paragraph);
                    if (!empty($paragraph)) {
                        $firstParagraph = strip_tags(substr($paragraph[0], 0, '200'));
                        echo '<p>' . $firstParagraph . '...</p>' ;
                    } ?>

                    <a href="<?php the_permalink($events->ID); ?>">Read More</a>
                </div>
            </div>
        <?php endwhile ?>
        <?php endif; ?>
        </div>
    </div>
</section>

<section class="pressBlock">
    <div class="container">
        <?php $pressSettings = get_field('press_release_page_settings',$pageID);
        if (isset($pressSettings) && !empty($pressSettings)) { ?>
        <div class="pressHeader">
            <h2><?php echo $pressSettings['title']; ?></h2>
            <?php if($pressSettings['url']){?>
            <a href="<?php echo $pressSettings['url']; ?>">
                View All <?php echo $pressSettings['title']; ?>
            </a>
            <?php } else { ?>
                <a href="<?php echo esc_url( home_url( '/press-release/' ) ); ?>">
                    View All <?php echo $pressSettings['title']; ?>
                </a>
            <?php } ?>
        </div>
        <?php } ?>
        <?php
            $args = array(
                'paged' => $paged,
                'posts_per_page'   => 6,
                'post_type'      => 'press-release',
            );
            $press = new WP_Query($args);
        ?>
        <div class="swiper press-slider">
            <div class="swiper-wrapper">
                <?php if($press->have_posts()) : ?>
                <?php while($press->have_posts()) : $press->the_post() ?>
                <div class="swiper-slide">
                    <a href="<?php the_permalink($press->ID); ?>" class="pressBox">
                        <div class="pressThumb">
                            <?php 
                            if(has_post_thumbnail($press->ID)&&(get_the_post_thumbnail($press->ID)!='')){
                            $post_thumb = get_the_post_thumbnail_url($press->ID); ?>
                                <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                            <?php } ?>
                        </div>
                        <div class="pressCaption">
                            <?php $content = get_the_content($press->ID);
                            $pattern = '/<p>(.*?)<\/p>/';
                            preg_match($pattern, $content, $paragraph);
                            if (!empty($paragraph)) {
                                echo $paragraph[0];
                            } ?>
                        </div>
                        </a>
                </div>
                <?php endwhile ?>
                <?php endif; ?>
            </div>
        </div>
        
        </div>
    </div>
</section>

<section class="newsLetterBlock">
    <div class="container">
        <?php $newsletterSettings = get_field('newsletter_page_settings',$pageID);
        if (isset($newsletterSettings) && !empty($newsletterSettings)) { ?>
        <div class="newsLetterHeader">
            <h2><?php echo $newsletterSettings['title']; ?></h2>
            <?php if($newsletterSettings['url']){?>
            <a href="<?php echo $newsletterSettings['url']; ?>">
                View All <?php echo $newsletterSettings['title']; ?>
            </a>
            <?php } else { ?>
                <a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>">
                    View All <?php echo $newsletterSettings['title']; ?>
                </a>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="newsLetterArea">
        <?php
            $args = array(
                'paged' => $paged,
                'posts_per_page'   => 4,
                'post_type'      => 'newsletter',
            );
            $newsletter = new WP_Query($args);
        ?>
        <?php if($newsletter->have_posts()) : ?>
        <?php while($newsletter->have_posts()) : $newsletter->the_post() ?>
            <a href="<?php the_permalink($newsletter->ID); ?>" class="newsLetterBox">
                <div class="newsLetterThumb">
                <?php 
                    if(has_post_thumbnail($newsletter->ID)&&(get_the_post_thumbnail($newsletter->ID)!='')){
                    $post_thumb = get_the_post_thumbnail_url($newsletter->ID); ?>
                        <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                    <?php } else { ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/newsletterThumb.svg" alt="Sriher" class="default" />
                    <?php } ?>
                </div>
                <div class="newsLetterCaption">
                    <?php $content = get_the_content($newsletter->ID);
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

<?php get_footer(); ?>