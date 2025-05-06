<?php
    /*
    Template Name: NIRF page
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
        document.addEventListener("DOMContentLoaded", function () {
            gsap.registerPlugin(ScrollTrigger);

            let sections = gsap.utils.toArray(".animatedblk");

            sections.forEach((section, index) => {
            let prevSection = sections[index - 1];

            gsap.fromTo(
                section,
                { opacity: 1, y: 70 }, // Start with lower opacity & slight downward shift
                {
                opacity: 1,
                y: 0,
                duration: 1.2, // Smooth animation speed
                ease: "power2.out",
                scrollTrigger: {
                    trigger: section,
                    start: "top 70%", // Start animation when 70% of section is in view
                    end: "top 30%", // Ends when reaching 30% of viewport
                    scrub: 1.2, // Ensures smooth fading instead of abrupt change
                    toggleActions: "play none none reverse",
                    onEnter: () => {
                    if (prevSection) {
                        gsap.to(prevSection, { opacity: 0.95, duration: 1, ease: "power2.out" });
                    }
                    },
                    onLeaveBack: () => {
                    if (prevSection) {
                        gsap.to(prevSection, { opacity: 1, duration: 1, ease: "power2.out" });
                    }
                    },
                },
                }
            );
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

        <script>
            $( window ).on("load", function() {
                $('#maintab-sec').easyResponsiveTabs({
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

<section  class="nirf-about-sec">
        <div class="container" >
            <?php $abt = get_field('about_section',$pageID);
            if(isset($abt) && !empty(array_filter($abt))) { ?>
	        <div class="nirf-blksec animatedblk" >
                <div class="nirf-blkwrap">
                    <?php if($abt['about_nirf']) { ?>
                    <div class="content-sec" > 
                        <div class="txt-blk">
                            <?php echo $abt['about_nirf']; ?>
                        </div>
                    </div>
                    <?php } if($abt['image']) { ?>
                    <div class="image-sec">
                        <img src="<?php echo $abt['image']; ?>" />
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php $report_sec = get_field('report_section',$pageID); 
            if(isset($report_sec) && !empty(array_filter($report_sec))) { ?>
            <div class="nirf-blksec row-reverse animatedblk" >
                <div class="nirf-blkwrap ">
                    <div class="content-sec"> 
                        <div class="txt-blk">
                            <?php if($report_sec['title']) { ?>
                            <h3><?php echo $report_sec['title'];?></h3>
                            <?php } if($report_sec['reports']) { ?>
                            <div class="link-blk">
                                <ul>
                                    <?php foreach($report_sec['reports'] as $rpts ) { ?>
                                    <li>
                                        <h5><?php echo $rpts['title__year_']; ?></h5>
                                        <span>
                                            <?php 
                                            $doc_rpt = $rpts['section_wise_report'];
                                            $total_items = count($doc_rpt);
                                            $counter = 0;
                                            foreach($doc_rpt as $yrwserpt) { 
                                                $counter++; 
                                            ?>
                                            <a href="<?php echo $yrwserpt['pdf_url']; ?>" target="_blank" rel="noopener"><strong><?php echo $yrwserpt['text']; ?></strong></a>
                                            <?php if ($counter < $total_items) { echo "&nbsp; |&nbsp;"; } ?>
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <?php 
                                $yronly = get_field('year_and_pdf_only_section', $pageID);
                                if (isset($yronly) && !empty(array_filter($yronly))) { ?>
                                    <h6>
                                        <?php 
                                        $total_items = count($yronly);
                                        $counter = 0;
                                        foreach ($yronly as $yr) { 
                                            $counter++; ?>
                                            <a href="<?php echo esc_url($yr['pdf_url']); ?>" target="_blank" rel="noopener">
                                                <?php echo esc_html($yr['year']); ?>
                                            </a>
                                            <?php if ($counter < $total_items) { echo "&nbsp;|&nbsp;"; } ?>
                                        <?php } ?>
                                    </h6>
                                <?php } ?>

                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if($report_sec['image']) { ?>
                    <div class="image-sec">
                        <img src="<?php echo $report_sec['image']; ?>" alt="report-img"/>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php $cargry_sec = get_field('ranking_section',$pageID); 
            if(isset($cargry_sec) && !empty(array_filter($cargry_sec))) { ?>
            <div class="nirf-blksec animatedblk" >
                <div class="nirf-blkwrap ">
                    <div class="content-sec"> 
                        <div class="txt-blk">
                            <?php if($cargry_sec['left_content_section']) { ?>
                            <?php echo $cargry_sec['left_content_section'];?>
                            <?php } if($cargry_sec['category_sub_title']) { ?>
                            <span><?php echo $cargry_sec['category_sub_title']; ?></span>
                            <?php } if($cargry_sec['category_lists']) { ?>
                            <div class="link-blk">
                                <ul>
                                    <li>
                                        <span>
                                            <?php
                                            $totalitems = count($cargry_sec['category_lists']);
                                            $counter= 0 ;
                                            foreach($cargry_sec['category_lists'] as $items ) {
                                                $counter++; ?>
                                            <a href="<?php echo $items['url'];?>" target="_blank" rel="noopener"><strong><?php echo $items['title'];?></strong></a>
                                            <?php if ($counter < $totalitems) { echo "&nbsp;|&nbsp;"; } ?>
                                            <?php } ?>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if($cargry_sec['right_image']) { ?>
                    <div class="image-sec">
                        <img src="<?php echo $cargry_sec['right_image']; ?>" alt="rankimg"/>
                    </div>
                    <?php } ?>
                </div>
              
            </div>
            <?php } ?>
        </div>
</section>
<?php if(get_field('achievement_title',$pageID)) { ?>
<section class="nirf-middle-banner">
    <div class="nirf-middle-area container">
        <div class="nirf-middle-wrap">
        <h4><?php echo get_field('achievement_title',$pageID); ?></h4>
        </div>
        <span class="yellow-up" data-aos="fade-up" data-aos-duration="1500" ></span>
        <span class="red-down " data-aos="fade-down" data-aos-duration="1500" ></span>
    </div>
</section>
<?php } ?>
<?php $prvs_year = get_field('previous_year_section',$pageID); 
if(isset($prvs_year) && !empty(array_filter($prvs_year))) { ?>
<section class="nirf-year-indicators">
    <div class="container ">
        <?php if($prvs_year['title']) { ?>
        <div class="mainblk-title">
	        <h2><?php echo $prvs_year['title']; ?></h2>
        </div>
        <?php } if($prvs_year['year_wise_items']) { ?>
        <div class="nirf-sec-programs">
            <div class="maintab-sec" id="maintab-sec">
                <div class="yr-indicator-blk">
                    <ul class="yr-indictr-list resp-tabs-list  hor_1 animatedblk">
                        <?php foreach($prvs_year['year_wise_items'] as $yrws) { ?>
                        <li id="<?php echo $yrws['year']; ?>"><?php echo $yrws['year']; ?></li>
                        <?php } ?>
                    </ul>
                </div>
                
                <div class="yr-indictr-mainouter resp-tabs-container hor_1 animatedblk">
                    <?php foreach($prvs_year['year_wise_items'] as $yrws) { ?>
                        <div class="yr-indictr-item">
                            <div class="tab-item">
                                <?php if ($yrws['title']) { ?>
                                    <h5 class="tab-title"><?php echo $yrws['title']; ?></h5>
                                <?php } ?>

                                <?php 
                                $certificates = $yrws['certficates']; 
                                $total_certificates = count($certificates);
                                ?>

                                <?php if (!empty($certificates)) { 
                                    $counter = 0; ?>
                                    
                                    <div class="tab-row">
                                        <?php foreach ($certificates as $index => $cerfct) { 
                                            if ($counter > 0 && $counter % 4 == 0) { ?>
                                                </div> <!-- Close the current tab-row -->
                                                <div class="tab-row"> <!-- Start a new tab-row -->
                                            <?php } ?>
                                            
                                            <div class="item-col">
                                                <div class="cntr-txt">
                                                    <p><?php echo $cerfct['title']; ?></p><br>
                                                    <p><?php echo $cerfct['certficate_']; ?></p>
                                                </div>
                                                <div class="cntr-img">
                                                    <a href="<?php echo $cerfct['url']; ?>" target="_blank">
                                                        <img src="<?php echo esc_url($cerfct['image']); ?>" />
                                                    </a>
                                                </div>
                                            </div>

                                            <?php $counter++; ?>
                                        <?php } ?>
                                    </div> <!-- Close last tab-row -->
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                
            </div>
        </div>
        <?php } ?>
    </div>
</section>
<?php } ?>
<?php $score_sec = get_field('score_card_section',$pageID); 
if(isset($score_sec) && !empty(array_filter($score_sec))) { ?>
<section class="nirf-scorecard-sec">
    <div class="container">
        <div class="scorecard-indictr-item">
            <?php if($score_sec['title']) { ?>
            <div class="scorecard-title">
                <h2><?php echo $score_sec['title']; ?></h2>
            </div>
            <?php } ?>
            <div class="scorecard-cntnt-row" >
                <?php if($score_sec['list_scores']) { ?>
                <div class="left-item ">
                    <div class="row">
                        <?php foreach($score_sec['list_scores'] as $scrs) { ?>
                        <div class="col-item">
                            <?php if($scrs['score']) { ?>
                            <h3><?php echo $scrs['score'] ; ?></h3>
                            <?php } if($scrs['text']) { ?>
                            <p><?php echo $scrs['text'] ; ?></p>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } if($score_sec['right_image']) { ?>
                <div class="right-item " >
                    <img src="<?php echo $score_sec['right_image'] ; ?>"/>
                     
                </div>
                <?php } ?>
            </div>
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
<?php $contact = get_field('contact_section',$pageID);
if(isset($contact) && !empty(array_filter($contact))) {  ?>
<section class="nirf-contct-sec">
    <div class="container">
        <?php if($contact['title']) { ?>
        <div class="lft-head">
        <h2><?php echo $contact['title']; ?></h2>
        </div>
        <?php } ?>
       <div class="nirf-contct-blk " >
        <div class="lft-sec" >
            <?php if($contact['address_section']) { ?>
            <div class="ct-txt">
                <?php echo $contact['address_section']; ?>
            </div>
            <?php } if($contact['other_contact_details_section']) { ?>
            <div class="ct-txt ">
                <?php echo $contact['other_contact_details_section']; ?>
            </div>
            <?php } ?>
        </div>
        <?php if($contact['image']) { ?>
        <div class="rht-sec " >
            <img src="<?php echo $contact['image']; ?>" alt="contact-image" />
        </div>
        <?php } ?>
       </div>
    </div>
</section>
<?php } ?>


<?php get_footer(); ?>