<?php
    /*
    Template Name: academic-calender page
    */
    get_header();
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
                $(".academic-calender-track").slick({
                    infinite: true,
                    slidesToShow: 3,
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
                            slidesToShow: 3
                        }
                    },{
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2
                        }
                    }, {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2
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

<?php $academic_calender_section = get_field('academic_calender_section',$pageID); 
if(isset($academic_calender_section) && !empty($academic_calender_section)) { ?> 
    <div class="academic-calender-wrapper">
        <?php foreach($academic_calender_section as $calender_sec) { ?>
        <section class="academic-calender-sec ">
            <div class="container">
                <div class="academic-calender-blk" >
                    <?php if($calender_sec['campus_name']) { 
                        echo '<h3>'.$calender_sec['campus_name'].'</h3>';
                    }
                    $calender_items = $calender_sec['calender_items']; 
                    if($calender_items) { ?>
                        <div class="academic-calender-track" >
                            <?php foreach($calender_items as $cal_item) { 
                                if(isset($cal_item['calender_pdf_url']) && isset($cal_item['calender_text']) && !empty($cal_item['calender_pdf_url'])) { ?>
                                <div class="academic-calender-item " >
                                    <a href="<?php echo $cal_item['calender_pdf_url']; ?>" title="<?php echo $cal_item['calender_text']; ?>" target="_blank" >
                                        <div class="academic-calender-txt">
                                        <?php echo $cal_item['calender_text']; ?>                                                                                                                                                                                                          
                                        </div>
                                    </a>
                                </div>
                                <?php } 
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>
        <?php } ?>
    </div>
    <?php } ?>

<!-- latest updates -->
<?php $blog_detail = get_field('blog_section_common',$pageID);
    if(isset($blog_detail) && !empty(array_filter($blog_detail))) { ?>
<div class="home_page">
    <section class="updates">
       
        <div class="latest-updates">
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