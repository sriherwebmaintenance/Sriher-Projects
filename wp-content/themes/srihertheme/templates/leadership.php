<?php
    /*
    Template Name: leadership page
    */
    get_header();
    wp_enqueue_script('gsap-animate');
    wp_enqueue_script('easyResponsiveTabs');
    wp_enqueue_script('magnificPopup');

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
        $(document).ready(function() {
            $('.popup-with-desc').magnificPopup({
                type: 'inline',
                preloader: false,
                closeBtnInside: true,
                focus: '#name',

                // When elemened is focused, some mobile browsers in some cases zoom in
                // It looks not nice, so we disable it:
                callbacks: {
                    beforeOpen: function() {
                        if($(window).width() < 700) {
                            this.st.focus = false;
                        } else {
                            this.st.focus = '#name';
                        }
                    }
                }
            });
        });
       
    </script>
    <script>
            $( window ).on("load", function() {
                $('#leader-tab-area').easyResponsiveTabs({
                    type: 'default', 
                    width: 'auto', 
                    fit: true, 
                    closed: 'accordion', 
                    tabidentify: 'hor_1', 
                    activate: function(event) { 
                        var $tab = $(this);
                        var $info = $('#nested-tabInfo2');
                        var $name = $('span', $info);
                        $name.text($tab.text());
                        $info.show();
                    }
                });
           
                $(".resp-tabs-container.hor_1 .resp-accordion.hor_1:first").addClass('resp-tab-active'); 
                if($(".resp-tabs-container.hor_1 .resp-accordion.hor_1:first").hasClass("resp-tab-active")){
                    $(".yr-indictr-item.resp-tab-content.hor_1:first").addClass("resp-tab-content-active");
                    $(".yr-indictr-item.resp-tab-content.hor_1:first").css("display", "block");
                }else{
                    $(".yr-indictr-item.resp-tab-content.hor_1:first").removeClass("resp-tab-content-active");
                    $(".yr-indictr-item.resp-tab-content.hor_1:first").css("display", "none");
                }
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


<?php $top_leader = get_field('about_leader_sec',$pageID);
if(isset($top_leader) && !empty(array_filter($top_leader))) { ?>
<section class="top-leader-sec">
    <div class="container">
        <div class="leader-top-wrap" data-aos="fade-up" data-aos-duration="3000">
            <?php if($top_leader['image']) { ?>
            <div class="leader-top-left">
                <div class="lead-item">
                        <a class="popup-with-desc" href="#leader1">
                            <img src="<?php echo $top_leader['image'];?>" alt="<?php echo $top_leader['title'];?>" />
                        </a>
                </div>
                <span class="yellow-down" data-aos="fade-down" data-aos-duration="1500" ></span>
                <span class="red-up " data-aos="fade-up" data-aos-duration="1500" ></span>
            </div>
            <?php } ?>
            <div class="leader-top-right">
                <div class="abtquote-outer">
                    <?php if($top_leader['quotes_sec']) { ?>
                    <div class="quotes">
                        <h3><?php echo $top_leader['quotes_sec'];?></h3>
                    </div>
                    <?php } if($top_leader['title']) { ?>
                    <h2><?php echo $top_leader['title'];?></h2>
                    <?php } if($top_leader['content']) { ?>
                    <?php echo $top_leader['content'];?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="leader-pop-sec mfp-hide" id="leader1">
            <div class="pop-wrapper">
                <?php if($top_leader['image']) { ?>
                <div class="pop-image">
                    <img src="<?php echo $top_leader['image'];?>" alt="<?php echo $top_leader['title'];?>" />
                </div>	
                <?php } ?>	
                <div class="pop-content">
                    <?php if($top_leader['title']) { ?>
                    <h3><?php echo $top_leader['title'];?></h3>
                    <?php } if($top_leader['designation']) { ?>
                    <div class="pop-desig"><?php echo $top_leader['designation'];?></div>
                    <?php } if($top_leader['popup_content']) { ?>
                    <div class="pop-desc">
                    <?php echo $top_leader['popup_content'];?>
                    </div>
                    <?php } if($top_leader['button_url']) { ?>
                    <div class="know-more-btn ">
                        <a href="<?php echo $top_leader['button_url'];?>"><?php echo $top_leader['button_text'];?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>

<?php
$args = array(
    'post_type'      => 'leaders',  // Replace with your post type
    'posts_per_page' => -1,         // Get all parent posts
    'post_parent'    => 0,          // Get only parent posts (no parent)
    'orderby'        => 'date',
    'order'          => 'DESC'
);

$parent_services = new WP_Query($args);

if ($parent_services->have_posts()) : ?>
<div class="btm-leader-sec">
    <div class="container">
        <div class="btm-leader-area">
            <?php 
            $i = 1;
            while ($parent_services->have_posts()) : $parent_services->the_post(); ?>
            <div class="leader-wrap" data-aos="fade-up" data-aos-duration="1500">
                <a class="popup-with-desc" href="#leader<?php the_ID(); ?>">
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="leader-img-sec">
                            <div class="lead-item">
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>" />
                            </div>
                            <span class="polygon"></span>
                        </div>
                    <?php } ?>
                    <div class="leader-cntnt-sec">
                        <div class="leader-name"><?php the_title(); ?></div>
                        <?php if(get_field('designation_leaders',get_the_ID())) { ?>
                        <p><?php echo get_field('designation_leaders',get_the_ID()); ?></p>
                        <?php } ?>
                    </div>
                </a>

                <div class="leader-pop-sec mfp-hide" id="leader<?php the_ID(); ?>">
                    <div class="pop-wrapper">
                        <?php if (has_post_thumbnail()) { ?>
                            <div class="pop-image">
                                <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>" />
                            </div>
                        
                        <?php }?>
                        		
                        <div class="pop-content">
                            <h3><?php the_title(); ?></h3>
                            <?php if(get_field('designation_leaders',get_the_ID())) { ?>
                            <div class="pop-desig"><?php echo get_field('designation_leaders',get_the_ID()); ?></div>
                            <?php } ?>
                            <div class="pop-desc">
                                <p><?php echo wp_trim_words(get_the_content(), 60, '...'); ?></p>
                            </div>
                            <div class="know-more-btn">
                                <a href="<?php the_permalink(); ?>">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++; 
        endwhile; ?>
            
            
        </div>
    </div>
</div>
<?php wp_reset_postdata(); ?>
<?php else : ?>
    <p>No parent services found.</p>
<?php endif; ?>
<section class="leader-tab-sec">
    <div class="container">
        <div class="leader-tab-area" id="leader-tab-area">
            <!-- Leaders Category Section -->
            <div class="leaders-category">
                <?php
                // Get all terms (categories) from the 'leaders-category' taxonomy
                $terms = get_terms(array(
                    'taxonomy' => 'leaders-category',
                    'hide_empty' => false, // Include empty terms
                ));

                if (!empty($terms) && !is_wp_error($terms)) :
                ?>
                    <ul class="leader-tab-list resp-tabs-list hor_1 animatedblk">
                        <?php foreach ($terms as $term) : ?>
                            <li id="<?php echo esc_attr($term->slug); ?>">
                                <?php echo esc_html($term->name); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Leader Tabs Content -->
            <div class="leader-tab-mainouter resp-tabs-container hor_1 animatedblk" data-aos="fade-up" data-aos-duration="3000">
                <?php
                // Loop through each term (category) to display its leaders
                foreach ($terms as $term) :
                    $args = array(
                        'post_type' => 'leaders', // Custom post type
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'leaders-category',
                                'field' => 'slug',
                                'terms' => $term->slug,
                            ),
                        ),
                    );

                    $query = new WP_Query($args);

                    if ($query->have_posts()) :
                ?>
                        <div class="btm-leader-sec">
                            <div class="container">
                                <div class="btm-leader-area">
                                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                                        <div class="leader-wrap">
                                            <a class="popup-with-desc" href="#leader-<?php echo get_the_ID(); ?>">
                                                <div class="leader-img-sec">
                                                    <div class="lead-item">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>" />
                                                        <?php else : ?>
                                                            <img src="<?php echo get_template_directory_uri(); ?>/images/leadership/default.svg" alt="Default Image" />
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="polygon"></span>
                                                </div>
                                                <div class="leader-cntnt-sec">
                                                    <div class="leader-name"><?php the_title(); ?></div>
                                                    <p><?php echo get_post_meta(get_the_ID(), 'designation', true); ?></p>
                                                </div>
                                            </a>
                                            <div class="leader-pop-sec mfp-hide" id="leader-<?php echo get_the_ID(); ?>">
                                                <div class="pop-wrapper">
                                                    <div class="pop-image">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title(); ?>" />
                                                        <?php else : ?>
                                                            <img src="<?php echo get_template_directory_uri(); ?>/images/leadership/default.svg" alt="Default Image" />
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="pop-content">
                                                        <h3><?php the_title(); ?></h3>
                                                        <div class="pop-desig"><?php echo get_post_meta(get_the_ID(), 'designation', true); ?></div>
                                                        <div class="pop-desc">
                                                        <p><?php echo wp_trim_words(get_the_content(), 60, '...'); ?></p>
                                                        </div>
                                                        <div class="know-more-btn">
                                                            <a href="<?php the_permalink(); ?>">Read More</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                <?php
                        wp_reset_postdata(); // Reset the post data
                    endif;
                endforeach;
                ?>
            </div>
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