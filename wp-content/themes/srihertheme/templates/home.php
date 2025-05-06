<?php
    /*
    Template Name: Home Page
    */
    get_header();
    $pageID = get_the_ID();
    wp_enqueue_script('Swiper');
    add_action('wp_footer','home_scripts',25);
    function home_scripts(){?>
        <script>
            $(document).ready(function() {
                
                // var swiper = new Swiper(".banner-slider", {
                //     slidesPerView: 1,
                //     spaceBetween:0,
                //     speed: 1500,
                //     autoplay: {
                //         disableOnInteraction: false,
                //     },
                //     effect: "fade",
                //     pagination: {
                //         el: ".swiper-pagination-1",
                //         clickable: "true",
                //     },
                //     loop:true,
                // });

                var homeBannerSwiper = new Swiper(".banner-slider", {
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  effect: "fade",
  loop: true,
  pagination: {
            el: ".swiper-pagination-1",
            clickable: "true",
        },
  on: {
    init: function () {
      // Get all video elements in the swiper
      const videos = document.querySelectorAll('.swiper-slide video');
      
      videos.forEach(video => {
        video.addEventListener('play', function() {
          homeBannerSwiper.autoplay.stop();
        });
        
        video.addEventListener('ended', function() {
          homeBannerSwiper.autoplay.start();
          homeBannerSwiper.slideNext();
        });
      });
    },
    slideChange: function () {
      // Get the current slide
      const currentSlide = homeBannerSwiper.slides[homeBannerSwiper.activeIndex];
      const video = currentSlide.querySelector('video');
      
      // Stop all other videos first
      const allVideos = document.querySelectorAll('.swiper-slide video');
      allVideos.forEach(v => {
        if (!v.paused) {
          v.pause();
        }
        v.currentTime = 0;
      });
      
      // If current slide has a video, play it
      if (video) {
        video.currentTime = 0;
        video.play().catch(function(error) {
          if (error.name !== 'AbortError') {
            console.log('Video playback error:', error);
          }
        });
      } else {
        // No video in current slide, ensure autoplay is running
        homeBannerSwiper.autoplay.start();
      }
    },
    transitionEnd: function() {
      const currentSlide = homeBannerSwiper.slides[homeBannerSwiper.activeIndex];
      const hasVideo = currentSlide.querySelector('video');
      
      if (!hasVideo) {
        homeBannerSwiper.autoplay.start();
      }
    }
  }
});

                var swiper = new Swiper(".image-slider", {
                    slidesPerView: 1,
                    spaceBetween:0,
                    speed: 1500,
                    autoplay: {
                        disableOnInteraction: false,
                    },
                    effect: "fade",
                    pagination: {
                        el: ".swiper-pagination-2",
                        clickable: "true",
                    },
                    loop:true,
                });

                var swiper = new Swiper(".recognition-slider", {
                    slidesPerView: 7,
                    spaceBetween: 20,
                    speed: 1500,
                    autoplay: {
                        disableOnInteraction: false,
                    },
                    loop:true,
                    breakpoints: {
                        2500: {
                            slidesPerView: 7,
                            spaceBetween: 20,
                        },
                        1500: {
                            slidesPerView: 7,
                            spaceBetween: 20,
                        },
                        1200: {
                            slidesPerView: 7,
                            spaceBetween: 20,
                        },
                        700: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        },
                        600: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                        200: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                    }
                });

                var swiper = new Swiper(".logo-slider", {
                    slidesPerView: 6,
                    spaceBetween: 20,
                    speed: 1500,
                    autoplay: true,
                    // autoplay: {
                    //     disableOnInteraction: false,
                    // },
                    loop:true,
                    breakpoints: {
                        2500: {
                            slidesPerView: 6,
                            spaceBetween: 20,
                        },
                        1500: {
                            slidesPerView: 6,
                            spaceBetween: 20,
                        },
                        1200: {
                            slidesPerView: 6,
                            spaceBetween: 20,
                        },
                        700: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        },
                        600: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                        200: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                    }
                });

                var swiper = new Swiper(".testimonial-slider", {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    loop:true,
                    breakpoints: {
                        2500: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1500: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1200: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        700: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                        600: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                        200: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                    }
                });
            })
            //init scrolling parallax
            $(window).scroll(function(e){
                var scrolled = $(window).scrollTop();
                if(scrolled < 750){
                    parallax()
                }
            });

            //define parallax function
            function parallax(){
                var scrolled = $(window).scrollTop();
                $('#parallax-info').css('background-positionY',(scrolled * -0.5)+'px');
            };

            //define parallax function
            function parallax(){
                var scrolled = $(window).scrollTop();
                $('#parallax-admn').css('background-positionY',(scrolled * -0.5)+'px');
            };

            //read more url of featured news
            document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.image-slider', {
        pagination: {
            el: '.swiper-pagination-2',
            clickable: true,
        },
        slidesPerView: 1,
        spaceBetween: 10,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        on: {
            slideChange: function () {
                var currentSlideIndex = this.realIndex;
                var dynamicLink = document.getElementById('dynamic-link');
                dynamicLink.href = postUrls[currentSlideIndex];
            }
        }
    });

    // Set initial link on page load
    document.getElementById('dynamic-link').href = postUrls[0];
});


        </script>
	<?php
    }
?>
<div class="home_page">
    <?php $banners = get_field('banner',$pageID);
    if(isset($banners) && !empty($banners)) { ?>
        <section class="homeBanner">
   
        <div class="swiper banner-slider">
            <div class="swiper-wrapper">
                <?php foreach($banners as $banner) { 
                    if($banner['select_upload_type_image_or_video'] == 'image') { ?>
                <div class="swiper-slide">
                    <div class="banner" style="background-image: url(<?php echo $banner['banner_image'];?>);">
                        <div class="container">
                            <div class="bannerText" data-aos="fade-up" data-aos-duration="1000">
                                <?php if($banner['title']) { ?>
                                <h1 class="h1"><?php echo $banner['title'];?></h1>
                                <?php } if($banner['content']) { ?>
                                <p><?php echo $banner['content'];?></p>
                                <?php } if($banner['button_link']) { ?>
                                <a href="<?php echo $banner['button_link'];?>"><?php echo $banner['button_text'];?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <span class="yellow" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300"></span>
                        <span class="white" data-aos="fade-down" data-aos-duration="1500"></span>
                    </div>
                </div>
                <?php }
                else {
                if($banner['banner_video_url']) { ?>
                <div class="swiper-slide">
                    <video id="videoSlide" controls muted>
                        <source src="<?php echo $banner['banner_video_url'];?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <?php } } } ?>
            </div>
            <div class="pagination">
                <div class="container">
                    <div class="swiper-pagination-1"></div>
                </div>
            </div>
        </div>
        </section>
    <?php } ?>
    <?php $section1 = get_field('section1_content',$pageID);
    if(isset($section1) && !empty($section1)){?>
        <section class="info" id="parallax-info">
            <div class="container">
                <h3 data-aos="fade-up" data-aos-duration="1000"><?php echo $section1;?></h3>
            </div>
        </section>
    <?php } ?>
    <section class="about-us">
        <div class="container">
            <div class="content">
                <span class="yellow" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300"></span>
                <?php ?>
                <?php $about = get_field('about_section',$pageID);
                if(isset($about) && !empty($about)) { ?>
                    <div class="left" data-aos="fade-up" data-aos-duration="1000">
                        <?php echo $about;?>
                    </div>
                <?php } ?>
                <?php $area = get_field('area_study_text',$pageID);
                if(isset($area) && !empty($area)){?>
                    <div class="right">
                        <?php if($area['title']) { ?>
                            <h3><?php echo $area['title'];?></h3>
                        <?php } ?>
                        <ul>
                            <?php foreach($area['departments'] as $department) {?>
                                <li data-aos="zoom-out-left" data-aos-duration="1000" ><a href="<?php echo $department['url'];?>"><?php echo $department['department'];?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="gallery">
        <div class="container">
        <?php
$args = array(
    'posts_per_page'   => 4,
    'post_type'        => 'news',
    'meta_query'       => array(
        array(
            'key'       => 'is_featured',
            'value'     => 1,
            'compare'   => '=',
        )
    ),
);
$featured = new WP_Query($args);

$post_urls = array();  // Initialize an array to hold post URLs
?>
<?php if($featured->have_posts()) : ?>
    <div class="image-gallery">
        <span class="yellow" data-aos="fade-down" data-aos-duration="1500" data-aos-delay="300"></span>
        <div class="swiper image-slider">
            <div class="swiper-wrapper">
                <?php while($featured->have_posts()) : $featured->the_post(); ?>
                    <div class="swiper-slide">
                        <div class="slide-content">
                            <h2 data-aos="fade-up" data-aos-duration="1000"><?php the_title(); ?></h2>
                            <div class="image">
                                <?php 
                                if(has_post_thumbnail()) {
                                    $post_thumb = get_the_post_thumbnail_url();
                                } else {
                                    $post_thumb = get_template_directory_uri() . '/images/logo.png';
                                }
                                ?>
                                <img src="<?php echo esc_url($post_thumb); ?>" alt="<?php the_title_attribute(); ?>">
                            </div>
                        </div>
                    </div>
                    <?php $post_urls[] = get_permalink(); // Collect the post URL ?>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination swiper-pagination-2"></div>
        </div>
        <span class="outer">
            <a id="dynamic-link" href="<?php echo esc_url(home_url('/news/')); ?>" class="link">View more</a>
        </span>
    </div>
    <script type="text/javascript">
        var postUrls = <?php echo json_encode($post_urls); ?>;
    </script>
<?php endif; ?>
<?php wp_reset_postdata(); ?>



            <?php $accreditaion = get_field('accreditation_section',$pageID);
            if(isset($accreditaion) && !empty($accreditaion)) { ?>
            <div class="recognition">
                <?php if($accreditaion['title']) { ?>
                    <h3><?php echo $accreditaion['title'];?></h3>
                <?php } ?>
                <?php if($accreditaion['logos']) { ?>
                    <div class="swiper recognition-slider">
                        <div class="swiper-wrapper">
                            <?php foreach($accreditaion['logos'] as $logos){?>
                                <div class="swiper-slide">
                                    <div class="item">
                                        <img src="<?php echo $logos['logo'];?>" alt="">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </section>
    <section class="placements">
        <?php $founder = get_field('quotes',$pageID);
        if(isset($founder) && !empty($founder)){?>
            <div class="founder">
                <div class="container">
                    <div class="content">
                        <?php if($founder['image']) {?>
                        <div class="left">
                            <img src="<?php echo $founder['image']; ?>" alt="">
                        </div>
                        <?php } if($founder['content']) { ?>
                        <div class="right" data-aos="fade-up" data-aos-duration="1000">
                            <?php echo $founder['content'];?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php $placements= get_field('placements_section',$pageID);
        if(isset($placements) && !empty($placements)){
            if($placements['background_image']) { ?>
            <div class="placementContent">
                <?php if($placements['background_image']) { ?>
                    <div class="displayImage">
                        <img src="<?php echo $placements['background_image'];?>" alt="">
                    </div>
                <?php } ?>
                <div class="placement-outer">
                <div class="container">
                        <div class="placementBox">
                            <div class="red" data-aos="fade-down" data-aos-duration="1500" data-aos-delay="300"></div>
                            <?php if($placements['content']) { ?>
                            <div class="content">
                                <?php echo $placements['content'];?>
                            </div>
                            <?php } if($placements['placed_companies']) { ?>
                                <div class="swiper logo-slider">
                                    <div class="swiper-wrapper">
                                        <?php foreach($placements['placed_companies'] as $placmnt){?>
                                        <div class="swiper-slide">
                                            <div class="item">
                                                <img src="<?php echo $placmnt['logo'];?>" alt="">
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div> 
                </div>
            </div>
        <?php } }?>
    </section>
    
    <section class="alumini-speak">
        <div class="container">
            <div class="head">
                <?php $testimonial = get_field('testimonial_section',$pageID);
                if($testimonial['testimonial_title']) {?>
                    <h2><?php echo $testimonial['testimonial_title'];?></h2>
                <?php } ?>
                <div class="navigation">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
        <div class="testimonial">
           <div class="container">
           <?php
                $args = array(
                    'posts_per_page'   => 4,
                    'post_type'     => 'testimonials',
                    'meta_query'    => array(
                        array(
                            'key'       => 'is_featured',
                            'value'     => 1,
                            'compare'   => '=',
                        )
                    ),
                );
                $featured = new WP_Query($args);

                // $featured = new WP_Query( $post_args );
                if ( $featured->have_posts()) { ?>
                <div class="swiper testimonial-slider">
                    <div class="swiper-wrapper">
                    <?php while ( $featured->have_posts() ) : $featured->the_post(); 
                            $postID = $featured->post->ID; ?>
                        <div class="swiper-slide">
                            <div class="card">
                                <p>“<?php echo wp_trim_words( get_the_excerpt($postID), 52 ); ?>”</p>
                                <div class="nameTag">
                                    <div class="left">
                                    <?php 
                                    if(has_post_thumbnail($postID)&&(get_the_post_thumbnail($postID)!='')){
                                    $post_thumb = get_the_post_thumbnail_url($postID); 
                                    } ?>
                                    <img src="<?php echo $post_thumb; ?>" alt="<?php echo get_the_title();?>">
                                    </div>
                                    <div class="right">
                                        <h3><?php echo get_the_title();?></h3>
                                        <?php if(get_field('designation')) {?>
                                            <h5><?php echo get_field('designation');?></h5>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php } 
                wp_reset_postdata( )?>
            </div> 
        </div>
        <div class="other-pages">
            <div class="container">
                <?php if($testimonial['view_all_link']) { ?>
                    <span class="outer">
                        <a href="<?php echo $testimonial['view_all_link'];?>" class="more">View All Testimonials <img src="<?php echo get_template_directory_uri(); ?>/images/home/updates/arrow.svg" alt=""></a>
                    </span>
                <?php } ?>
                <?php $other_link = get_field('other_links',$pageID);
                if(isset($other_link) && !empty($other_link)) {?>
                    <div class="other-pages-links">
                        <ul>
                            <?php foreach($other_link as $links) { ?>
                                <li><img src="<?php echo $links['icon'];?>" alt=""><a href="<?php echo $links['url'];?>"><?php echo $links['link_text'];?></a></li>
                            <?php } ?>
                        </ul>
                        <!-- <ul class="second">
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/home/testimonial/hospital.svg" alt=""><a href="">Hospital</a></li>
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/home/testimonial/vidya.svg" alt=""><a href="">Vidya Sudha</a></li>
                        </ul> -->
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="updates">
        <?php $admission = get_field('admission_section',$pageID);
        if(isset($admission) && !empty($admission)) { ?>
            <div class="admission" id="parallax-admn">
                <div class="container">
                    <div class="content" data-aos="fade-up" data-aos-duration="1000">
                        <?php echo $admission;?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="latest-updates">
            <div class="container">
                <?php $blog_detail = get_field('blog_section',$pageID);
                if(isset($blog_detail) && !empty($blog_detail)) {
                if($blog_detail['blog_main_title']){?>
                    <h2><?php echo $blog_detail['blog_main_title'];?></h2>
                <?php } ?>
                <div class="blog-news">
                    <div class="blog">
                        <h3>Blogs</h3>
                        <?php $post_args = array(
                        'post_type' => 'post',
                        'paged' => $paged,
                        'order' => 'DESC',
                        'posts_per_page' => 3,
                        );
                        $post_query = new WP_Query( $post_args );
                        if ( $post_query->have_posts()) { ?>
                        <div class="boxContent">
                        <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                            $postID = $post_query->post->ID; ?>
                            <div class="box">
                                <div class="image">
                                <?php 
                                    if(has_post_thumbnail($postID)&&(get_the_post_thumbnail($postID)!='')){
                                    $post_thumb = get_the_post_thumbnail_url($postID); 
                                    } ?>
                                    <img src="<?php echo $post_thumb; ?>" alt="<?php echo get_the_title();?>">
                                </div>
                                <a href="<?php echo get_the_permalink($postID); ?>" class="updates-link"><p><?php echo get_the_title();?></p></a>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php } ?>
                        <?php if($blog_detail['view_more_link']){ ?>
                        <a href="<?php echo $blog_detail['view_more_link'];?>" class="more"><?php echo $blog_detail['viewmore_link_text'];?> <img src="<?php echo get_template_directory_uri(); ?>/images/home/updates/arrow.svg" alt=""></a>
                        <?php }?>
                    </div>
                    <?php } ?>
                    <div class="news">
                    <?php $news_detail = get_field('news_section',$pageID);
                        if(isset($news_detail) && !empty($news_detail)) {
                            if($news_detail['news_title']){?>
                                <h3><?php echo $news_detail['news_title'];?></h3>
                            <?php } ?>
                            <div class="content">
                                <?php
                                $post_args = array(
                                'post_type' => 'news',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'media-category',
                                        'field' => 'term_id',
                                        'terms' => array(136),
                                        'operator' => 'NOT IN',
                                    ),
                                ),
                                'paged' => $paged,
                                'order' => 'DESC',
                                'posts_per_page' => 3,
                                );
                                $post_query = new WP_Query( $post_args );
                                if ( $post_query->have_posts()) { ?> 
                                <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                                    $postID = $post_query->post->ID; ?>
                                    <div class="news-link">
                                        <h4><?php echo get_the_date('d M Y'); ?></h4>
                                        <a href="<?php echo get_the_permalink($postID); ?>" class="updates-link"><p><?php echo get_the_title(); ?> </p></a>
                                    </div>
                                    <?php endwhile; ?>
                                    <?php } ?>
                            </div>
                            <?php if($news_detail['view_all_news_link']) { ?>
                                <a href="<?php echo $news_detail['view_all_news_link'];?>" class="more"><?php echo $news_detail['view_all_link_text'];?> <img src="<?php echo get_template_directory_uri(); ?>/images/home/updates/arrow.svg" alt=""></a>
                            <?php } ?>
                        <?php  }?>
                    </div>
                </div>
                <?php $events = get_field('event_section',$pageID);
                if(isset($events) && !empty($events)) {?>
                    <div class="events">
                        <?php if($events['event_title']) {?>
                            <h3><?php echo $events['event_title'];?></h3>
                        <?php } ?>
                        <?php
                        $post_args = array(
                        'post_type' => 'events',
                        'paged' => $paged,
                        'order' => 'DESC',
                        'posts_per_page' => 3,
                        );
                        $post_query = new WP_Query( $post_args );
                        if ( $post_query->have_posts()) { ?> 
                            <div class="boxContent">
                            <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                            $postID = $post_query->post->ID; ?>
                                <div class="box">
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
                                            <h4>
                                                <?php echo $event_dates['event_date'];?>
                                                <?php if($event_dates['event_start_time']) {
                                                    echo ' ' . $event_dates['event_start_time'];
                                                }
                                                if($event_dates['event_end_time']) {
                                                    echo ' - ' . $event_dates['event_end_time'];
                                                }
                                                ?>
                                            </h4>
                                        <?php } }?>
                                        <a href="<?php echo get_the_permalink($postID); ?>" class="updates-link"><p><?php echo wp_trim_words( get_the_title($postID), 7 ); ?></p></a>
                                        
                                    </div>
                                </div>
                                <?php endwhile;?>
                            </div>
                        <?php } ?>
                        <?php if($events['view_all_event_link_url']) { ?>
                        <a href="<?php echo $events['view_all_event_link_url'];?>" class="more"><?php echo $events['view_all_event_link_text'];?> <img src="<?php echo get_template_directory_uri(); ?>/images/home/updates/arrow.svg" alt=""></a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
<?php 
get_footer();
 ?>