<?php
    /*
    Template Name: rankings page
    */
    get_header();
    wp_enqueue_script('easyResponsiveTabs');
    wp_enqueue_script('gsap-animate');

    $pageID = get_the_ID();
    $parent_id = null;
    if(has_post_parent($pageID)) {
        $parent_id = $post->post_parent;
    }
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
<script>
$(document).ready(function() {
    $(".sidebarDrop").click(function() {
        $('.menu').toggleClass("open");
    });
});
</script>
<script>
var tag = document.createElement("script");
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName("script")[1];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
</script>
<script>
$(".video-pop").magnificPopup({
    disableOn: 0,
    type: "iframe",
    mainClass: "mfp-fade",
    removalDelay: 160,
    preloader: false,
    fixedContentPos: true,
    fixedBgPos: true,
    callbacks: {
        open: function() {
            new YT.Player("player", {
                events: {
                    onStateChange: onPlayerStateChange,
                },
            });
        },
    },
});

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.ENDED) {
        $.magnificPopup.close();
    }
}
</script>

<script>
class AnimatedCounter {
    constructor(counterElement) {
        this.counterElement = counterElement;
        this.targetValue = parseFloat(counterElement.getAttribute('data-target'));
        this.currentValue = 0;
        this.isFloat = !Number.isInteger(this.targetValue);
        this.animationDuration = 2000; // Duration in ms
        this.startValue = 0;
        this.startTime = null;
        this.animateCounter();
    }

    animateCounter() {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.startTime = performance.now();
                    requestAnimationFrame(this.updateCounter.bind(this));
                    entry.target.style.opacity = 1;
                }
            });
        });

        observer.observe(this.counterElement);
    }

    updateCounter(timestamp) {
        if (!this.startTime) this.startTime = timestamp;

        const progress = Math.min((timestamp - this.startTime) / this.animationDuration, 1);
        this.currentValue = this.startValue + (this.targetValue - this.startValue) * progress;

        this.counterElement.textContent = this.isFloat ?
            this.currentValue.toFixed(1) :
            Math.floor(this.currentValue);

        if (progress < 1) {
            requestAnimationFrame(this.updateCounter.bind(this));
        }
    }
}
document.querySelectorAll('.count-number').forEach(counter => {
    new AnimatedCounter(counter);
});
</script>


<?php
    }
?>

<?php $common_banner_without_overlay = get_field('banner_full_image_nirf',$pageID);
if(isset($common_banner_without_overlay) && !empty(array_filter($common_banner_without_overlay))) { ?>
<section class="bannerBlock-sec banner-sec">
    <div class="img-blk">
        <img src="<?php echo $common_banner_without_overlay['image']; ?>" />
    </div>
    <div class="container">
        <div class="bannerArea-sec" data-aos="fade-up" data-aos-duration="1500">
            <h1><?php echo $common_banner_without_overlay['title']; ?></h1>
        </div>
    </div>

</section>
<?php } ?>
<!-- Rank About -->
<section class="rank-about-sec">
    <div class="container">
        <div class="rank-about-blk">
            <div class="rank-aboutwrap">
                <?php $about_title = get_field('about_section_title',$pageID);
                 $about_content = get_field('about_section_about_content',$pageID)
                ?>
                <div class="content-sec" animate>
                    <?php if($about_title){?>
                    <h3><?php echo $about_title?></h3>
                    <?php }?>
                    <?php if($about_content){?>
                    <p><?php echo $about_content;?></p>
                    <?php }?>
                </div>
                <?php if(get_field('about_section_video_link',$pageID)){?>
                <div class="video-sec">
                    <a class="video-pop" href="<?php echo get_field('about_section_video_link',$pageID)?>" target="_blank"
                        rel="noopener">
                        <?php if(get_field('about_section_vedio_thumb',$pageID)){?>
                        <img src="<?php echo get_field('about_section_vedio_thumb',$pageID);?>">
                        <?php }?>
                    </a>
                    <span class="yellow-up" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300"></span>
                    <span class="red-down " data-aos="fade-down" data-aos-duration="1500" data-aos-delay="300"></span>
                </div>
                <?php }?>
            </div>

            <div class="rank-aboutwrap  row-reverse">

                <?php if(have_rows('about_section_animated_paragraph')):?>
                <div class="content-sec" animate>
                    <?php while(have_rows('about_section_animated_paragraph')):the_row()?>
                    <p><?php echo get_sub_field('paragraph');?></p>
                    <?php endwhile;?>
                </div>
                <?php endif;?>
                <?php if(get_field('about_section_animated_image',$pageID)):?>
                <div class="right-sec  image-sec zoom-image">

                    <img src="<?php echo get_field('about_section_animated_image',$pageID)?>" alt="">
                </div>
                <?php endif;?>
            </div>
        </div>

    </div>
</section>
<!-- Rank logo -->
<section class="rank-logo-sec">
    <div class="container">
        <?php if(have_rows('ranking_logos_rank_logo_top',$pageID)): ?>
        <div class="rank-logo-blk">
            <?php while(have_rows('ranking_logos_rank_logo_top',$pageID)):the_row();?>
            <div class="ranklogo-blk">

                <img src="<?php echo get_sub_field('top_logo');?>" alt="">
            </div>
            <?php endwhile;?>


        </div>
        <?php endif;?>
        <?php if(have_rows('ranking_logos_rank_logo_bottom',$pageID)):?>
        <div class="rank-logo-blk">
            <?php while(have_rows('ranking_logos_rank_logo_bottom',$pageID)):the_row() ;?>
            <div class="ranklogo-blk">

                <img src="<?php echo get_sub_field('bottom_logo_');?>" alt="">
            </div>
            <?php endwhile?>

        </div>
        <?php endif;?>
    </div>
</section>

<section class="ranking-detail-sec">
    <div class="container">
        <?php if(get_field('ranking_detail',$pageID)):?>
        <div class="title-sec">
            <h2><?php echo get_field('ranking_detail',$pageID); ?></h2>
        </div>
        <?php endif?>
        <?php if(have_rows('ranking_content_block')):?>
        <div class="ranking-detail-blk">
            <!-- repeater -->
            <?php while(have_rows('ranking_content_block')):the_row() ?>
            <div class="ranking-cntnt-blk">
                <div class="top-cntnt">
                    <?php if(get_sub_field('rank_logo')):?>
                    <div class="img-blk">
                        <img src="<?php echo get_sub_field('rank_logo');?>" alt="">
                    </div>
                    <?php endif;?>

                    <div class="position-detail">
                        <?php if(get_sub_field('position')){?>
                        <h4 class="position"><?php echo get_sub_field('position')?></h4>
                        <?php }?>

                        <?php if(get_sub_field('country_name')){?>
                        <div class="name"><?php echo get_sub_field('country_name')?></div>
                        <?php }?>
                    </div>
                    <?php if(get_sub_field('position_in_world')){ ?>
                    <div class="position-detail">
                        <h4 class="position"><?php echo get_sub_field('position_in_world'); ?></h4>
                        <div class="name"><?php echo get_sub_field('world');?></div>
                    </div>
                    <?php }?>
                </div>
                <?php if(get_sub_field('content')){?>
                <div class="btm-cntnt" animate>
                    <p><?php echo get_sub_field('content'); ?></p>
                </div>
                <?php }?>
            </div>
            <?php endwhile;?>

        </div>
        <?php endif; ?>
    </div>
</section>
<!-- Rank Middle banner -->
<section class="rank-middle-banner">
    <div class=" container">
        <div class="rank-middle-area">

        </div>
        <div class="rank-middle-wrap">
            <div class="left-sec">

                <div class="left-cntnt">
                    <?php if(get_field('rank_middle_banner_left_content')){?>
                    <h4><?php echo get_field('rank_middle_banner_left_content',$pageID); ?></h4>
                    <?php }?>
                    <?php if(get_field('rank_middle_banner_rank_logo',$pageID)){?>
                    <img src="<?php echo get_field('rank_middle_banner_rank_logo',$pageID); ?>" alt="">
                    <?php }?>
                </div>
                <div class="left-img-wrap">
                    <?php if(get_field('rank_middle_banner_mask_image')){?>
                    <div class='mask-img-sec'>
                        
                        <img src="<?php echo get_field('rank_middle_banner_mask_image',$pageID);?>" alt="">
                        
                    </div>
                    <?php }?>
                    <span class="yellow-up" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300"></span>

                </div>
            </div>
        <?php if(get_field('rank_middle_banner_right_sec_content',$pageID)){?>
            <div class="right-sec" animate>
                <?php echo get_field('rank_middle_banner_right_sec_content',$pageID);?>
            </div>
            <?php }?>
        </div>
</section>



<section class="rank-counter-sec">
    <div class="container">
        <div class="rank-counter-blk">
            <?php if(have_rows('rank_counter_section_rank_counter',$pageID)):?>
            <div class="left-item">
                <div class="row">
                   <?php while(have_rows('rank_counter_section_rank_counter',$pageID)): the_row()?>
                    <div class="counter-blk">
                        <?php if(get_sub_field('count_number')) {?>
                        <div class="countbx">
                            <span class="count count-number" data-target="<?php echo get_sub_field('count_number') ?>"><?php echo get_sub_field('count_number')?></span>
                        </div>
                        <?php }?>
                        <?php if(get_sub_field('title')) {?>
                        <div class="title"> <?php echo get_sub_field('title') ?></div>
                        <?php }?>
                    </div>
                    <?php endwhile;?>
                    
                </div>
                <?php if(get_field('rank_counter_section_button_link',$pageID)){?>
                <div class="know-more-btn ">
                    <a href="<?php echo get_field('rank_counter_section_button_link',$pageID);?>"><?php echo get_field('rank_counter_section_know_more_button')?></a>
                </div>
                <?php }?>
            </div>
            <?php endif;?>
            <?php if(get_field('rank_counter_section_right_image',$pageID)){?>
            <div class="right-item">
                <img class="zoom-image"
                    src="<?php echo get_field('rank_counter_section_right_image',$pageID); ?>" />
            </div>
            <?php }?>
        </div>
    </div>
</section>

<!-- latest updates -->
<?php $blog_detail = get_field('blog_section_common',$pageID);
    if(isset($blog_detail) && !empty(array_filter($blog_detail))) { ?>
<div class="home_page">
    <section class="updates">
       
        <div class="latest-updates" style="background: <?php echo get_field('section_background_color_ltst',$pageID);?>;">
            <div class="container">
                <?php
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
                                <a href="<?php echo get_the_permalink($postID); ?>" class="updates-link">
                                    <p><?php echo get_the_title();?></p>
                                </a>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <?php } ?>
                        <?php if($blog_detail['view_more_link']){ ?>
                        <a href="<?php echo $blog_detail['view_more_link'];?>"
                            class="more"><?php echo $blog_detail['viewmore_link_text'];?> <img
                                src="<?php echo get_template_directory_uri(); ?>/images/home/updates/arrow.svg"
                                alt=""></a>
                        <?php }?>
                    </div>
                    
                    <div class="news ">
                        <?php $news_detail = get_field('news_section_common',$pageID);
                        if(isset($news_detail) && !empty($news_detail)) {
                            if($news_detail['news_title']){?>
                        <h3><?php echo $news_detail['news_title'];?></h3>
                        <?php } ?>
                        <div class="content" animate>
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
                            <div class="news-link ">
                                <h4><?php echo get_the_date('d M Y'); ?></h4>
                                <a href="<?php echo get_the_permalink($postID); ?>" class="updates-link">
                                    <p><?php echo get_the_title(); ?> </p>
                                </a>
                            </div>
                            <?php endwhile; ?>
                            <?php } ?>
                        </div>
                        <?php if($news_detail['view_all_news_link']) { ?>
                        <a href="<?php echo $news_detail['view_all_news_link'];?>"
                            class="more"><?php echo $news_detail['view_all_link_text'];?> <img
                                src="<?php echo get_template_directory_uri(); ?>/images/home/updates/arrow.svg"
                                alt=""></a>
                        <?php } ?>
                        <?php  }?>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
</div>
<?php } ?>
<!-- latest updates end -->


<?php get_footer(); ?>