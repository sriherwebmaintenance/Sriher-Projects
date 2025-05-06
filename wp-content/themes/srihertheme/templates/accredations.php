<?php
    /*
    Template Name: accredations page
    */
    get_header();
    wp_enqueue_script('gsap-animate');
    wp_enqueue_script('slick');


    $pageID = get_the_ID();
    $parent_id = null;
    if(has_post_parent($pageID)) {
        $parent_id = $post->post_parent;
    }
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
    <script>
            $(document).ready(function () {
                $(".sidebarDrop").click(function() {
                    $('.menu').toggleClass("open");
                });
            });
    </script>
    <script>

            $(window).on("load", function() {
                $(".slider-main-wrapper").slick({
                    infinite: true,
                    slidesToShow: 4,
                    // margin:'20',
                    slideswToScroll: 1,
                    arrows: false,
                    autoplay: true,
                    autoplaySpeed: 1500,
                    arrows: false,
                    dots: true,
                    pauseOnHover: false,
                    responsive: [{
                        breakpoint: 1080,
                        settings: {
                            slidesToShow: 4
                        }
                    },{
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3
                        }
                    }, {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                        {
                        breakpoint: 580,
                        settings: {
                            slidesToShow: 1
                        }
                    },
                    {
                        breakpoint: 430,
                        settings: {
                            slidesToShow: 1
                        }

                    }]
                });
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


<?php $accreditation_posts = get_field('accreditation_posts_sec',$pageID);
if(isset($accreditation_posts) && !empty(array_filter($accreditation_posts))) { ?>
<section class="acc-first-blk">
    <div class="container">
        <?php if(!empty($accreditation_posts['sec_title'])) { ?>
        <div class="title-blk">
            <h2><?php echo $accreditation_posts['sec_title']; ?></h2>
        </div>
        <?php } 
        $accreditation_args = array(
            'post_type' => 'accreditation',
            'paged' => $paged,
            'order' => 'DESC',
            'posts_per_page' => -1,
        );
        $accreditation_query = new WP_Query( $accreditation_args );
        if ($accreditation_query->have_posts()) { ?>
            <div class="acc-first-wrapper">
                <?php while ( $accreditation_query->have_posts() ) : $accreditation_query->the_post(); 
                    $acc_postID = $accreditation_query->post->ID; ?>
                    <div class="acc-blk">
                        <?php if(has_post_thumbnail($acc_postID)&&(get_the_post_thumbnail($acc_postID)!='')) {
                            $acc_post_thumb = get_the_post_thumbnail_url($acc_postID); ?>
                            <div class="img-blk">
                                <img src="<?php echo $acc_post_thumb; ?>" alt="<?php echo get_the_title();?>">
                            </div>
                        <?php } ?>
                        <div class="cntnt-blk" animate>
                            <h3><?php the_title();?></h3>
                            <p><?php the_excerpt(); ?></p>
                            <div class="know-more-btn ">
                                <a href="<?php the_permalink()?>">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php } else { ?>
            <div class="acc-first-wrapper">
                <div class="acc-blk">
                    <?php echo "No Posts Found"; ?>
                </div>
            </div>
        <?php } wp_reset_postdata(); ?>
    </div>
</section>
<?php } ?>

<?php $iso_certification = get_field('iso_certification_sec',$pageID);
if(isset($iso_certification) && !empty(array_filter($iso_certification))) { ?>
<section class="iso-certfctn-sec">
    <div class="container">
        <?php if($iso_certification['sec_title']){ ?>
        <div class="title-sec">
            <h3><?php echo $iso_certification['sec_title']; ?></h3>
        </div>
        <?php } ?>
        <div class="icon-certifctn-blk">
            <?php if($iso_certification['sec_description']) { ?>
            <div class="lft-contnt" animate>
                <p><?php echo $iso_certification['sec_description']; ?></p>
            </div>
            <?php } 
            $certification_logos = $iso_certification['certification_logos'];
            if($certification_logos) { ?>
            <div class="rht-img">
                <?php foreach($certification_logos as $certi_logo) { ?>
                <div class="img-blk">
                    <img src="<?php echo $certi_logo['logo']; ?>"/>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>

<?php $ranking_details = get_field('ranking_details_sec',$pageID);
if(isset($ranking_details) && !empty($ranking_details)) { 
    foreach($ranking_details as $ranking_detail) { ?>
        <section class="ranking-detail-sec <?php if($ranking_detail['bg_color']) {echo "bg-blue";}?>">
            <div class="container">
                <div class="ranking-cntnt-sec" animate>
                    <?php if($ranking_detail['ranking_logo']) {
                        echo '<img src="'.$ranking_detail['ranking_logo'].'" />';
                    } if($ranking_detail['ranking_contents']) {
                        echo $ranking_detail['ranking_contents'];
                    }?>
                </div>
            </div>
        </section>
    <?php } 
}?>

<?php $acc_awards_section = get_field('acc_awards_section',$pageID); 
if(isset($acc_awards_section) && !empty($acc_awards_section)) { ?>
<section class="acc-award-blk">
    <div class="container">
        <?php if($acc_awards_section['title_sec']) { ?>
        <div class="title-blk">
            <h2><?php echo $acc_awards_section['title_sec']; ?></h2>
        </div>
        <?php } ?>
        <div class="acc-award-wrapper">

            <?php if($acc_awards_section['awards_post']) {
                $aw_postID =  $acc_awards_section['awards_post']->ID; ?>
                <div class="acc-blk">
                    <?php if(has_post_thumbnail($aw_postID)) { ?>
                        <div class="img-blk">
                            <a href="">
                                <img src="<?php echo get_the_post_thumbnail_url($aw_postID, 'full'); ?>" />
                            </a>
                        </div>
                    <?php } ?>
                    <div class="cntnt-blk" animate>
                        <h4><?php echo get_the_title($aw_postID); ?></h4>
                        <?php $award_cnt = apply_filters('the_content', get_post_field('post_content', $aw_postID));  
                        echo $award_cnt; ?>
                        <div class="know-more-btn ">
                            <a href="<?php the_permalink($aw_postID); ?>">Read More</a>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php $award_boxs = $acc_awards_section['award_boxs']; 
            if($award_boxs) { ?>
                <div class="blue-awrdbx-wrp">
                    <?php foreach($award_boxs as $box) { ?>
                    <div class="blue-award-item">
                        <?php if($box['award_image']) { ?>
                        <div class="img-blk">
                            <img src="<?php echo $box['award_image']; ?> " />
                        </div>
                        <?php } if($box['box_text']) { ?>
                        <div class="cntnt-blk">
                            <h4><?php echo $box['box_text']; ?></h4>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>

<?php
$awards_args = array(
    'post_type' => 'awards',
    'paged' => $paged,
    'order' => 'DESC',
    'posts_per_page' => -1,
);
$awards_query = new WP_Query( $awards_args );
if ($awards_query->have_posts()) { ?>
<section class="acc-award-slider">
    <div class="container">
        <div class="award-slider-wrapper">
            <div class="slider-main-wrapper">
                <?php while ( $awards_query->have_posts() ) : $awards_query->the_post(); 
                    $awards_postID = $awards_query->post->ID; 
                    if(has_post_thumbnail($awards_postID)&&(get_the_post_thumbnail($awards_postID)!='')) {
                        $awards_post_thumb = get_the_post_thumbnail_url($awards_postID); ?>
                        <div class="img-blk ">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo $awards_post_thumb; ?>" alt="<?php echo get_the_title();?>">
                            </a>
                        </div>
                    <?php } 
                endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<?php } ?>

<?php $course_detail = get_field('course_detail_sec',$pageID); 
if(isset($course_detail) && !empty(array_filter($course_detail))) { ?>
<section class="biomedical-blk-sec">
    <div class="container">
        <div class="biomedical-blk-area">
            <div class="lft-cntnt" animate>
                <?php if($course_detail['title_sec']) {
                    echo '<h2>'.$course_detail['title_sec'].'</h2>';
                } if($course_detail['description']) {
                    echo '<p>'.$course_detail['description'].'</p>';
                } $cta_button = $course_detail['cta_button'];
                if(isset($cta_button['btn_url']) && !empty($cta_button['btn_url']) && isset($cta_button['btn_txt'])) { ?>
                    <div class="know-more-btn ">
                        <a href="<?php echo $cta_button['btn_url']; ?>"><?php echo $cta_button['btn_txt']; ?></a>
                    </div>
                <?php } ?>
            </div>
            <?php if($course_detail['hero_image']) { ?>
                <div class="rht-cntnt">
                    <img class ="zoom-image" src="<?php echo $course_detail['hero_image']; ?>" />
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>

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