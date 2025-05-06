<?php
    /*
    Template Name: Alumni Testimonial Page
    */
    get_header();
    $pageID = get_the_ID();
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
                $(".readMore").click(function() {
                    $(this).siblings('.content').toggleClass("open");
                    if ($(this).text() === 'Read More') {
                        $(this).text('Read Less');
                    } else {
                        $(this).text('Read More');
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
                <?php if(have_posts()){while(have_posts()){the_post()?>
                <div class="contentLeft">
                    <div class="sidebarDrop"><?php echo get_the_title(); ?></div>
                    <?php $selected_side_menu = get_field('sidebar_menu',$pageID); ?>
                    <?php wp_nav_menu(array('theme_location' => $selected_side_menu)); ?>
                </div>
                <div class="contentRight">
                    <div class="testimonyArea">
                    <?php
                    $post_args = array(
                        'post_type' => 'testimonials',
                        'paged' => $paged,
                        'order' => 'DESC',
                        'posts_per_page' => 5,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'testimony-category',
                                'field' => 'term_id',
                                'terms' => array(172),
                            ),
                        ),
                    );
                    $post_query = new WP_Query( $post_args );
                    if ( $post_query->have_posts()) { ?>
                        <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                            $postID = $post_query->post->ID; ?>
                            <div class="card">
                                <div class="content">
                                    <?php echo get_the_content($postID); ?>
                                </div>
                                <button type="button" class="readMore">Read More</button>
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
                        <?php endwhile; ?>
                    <?php } ?>
                    </div>
                    <?php if($post_query->max_num_pages > 1){ ?> 
                        <div class="pagination paginate">
                            <?php
                                echo paginate_links(array(
                                    'base' => str_replace(999999, '%#%', esc_url(get_pagenum_link(999999))),
                                    'format' => '?paged=%#%',
                                    'current' => max(1, get_query_var('paged')),
                                    'total' => $post_query->max_num_pages,
                                    'prev_next'          => true,
                                    'prev_text'          => __('<img src="'.get_stylesheet_directory_uri().'/images/prev.svg" alt="prev" /> Prev'),
                                    'next_text'          => __('Next <img src="'.get_stylesheet_directory_uri().'/images/next.svg" alt="next" />'),
                                ));
                            ?>
                        </div>
                        <?php } ?>
                </div>
                <?php }}?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>