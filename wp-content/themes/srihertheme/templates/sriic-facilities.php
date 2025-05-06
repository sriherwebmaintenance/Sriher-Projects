<?php
    /*
    Template Name: SRIIC Facilities Page
    */
    get_header();
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
	<?php
    }
?>

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
            <?php if(have_posts()): while(have_posts()): the_post()?>
            <div class="contentLeft">
                <div class="sidebarDrop"><?php echo get_the_title(); ?></div>
                <?php $selected_side_menu = get_field('sidebar_menu',$pageID); ?>
                <?php wp_nav_menu(array('theme_location' => $selected_side_menu)); ?>
            </div>
            <div class="contentRight">
                <div class="mainContent">
                <?php
                    $args = array(
                        'paged' => $paged,    
                        'post_type'      => 'facilities',
                    );
                    $facilities = new WP_Query($args);
                ?>
                <?php if($facilities->have_posts()) : ?>
                    <div class="listGroupArea">
                    <?php while($facilities->have_posts()) : $facilities->the_post() ?>
                        <div class="listItem sriic">
                            <a href="<?php the_permalink(); ?>" class="listThumb">
                                <?php 
                                    if(has_post_thumbnail($facilities->ID)&&(get_the_post_thumbnail($facilities->ID)!='')){
                                    $post_thumb = get_the_post_thumbnail_url(); ?>
                                        <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                    <?php } ?>
                                </a>
                            <div class="listCaption">
                                <h2><?php echo get_the_title(); ?></h2>
                                <?php $facility_type = get_field('facility_type', $facilities->ID);
                                if ($facility_type) { ?>
                                    <h6><?php echo $facility_type; ?></h6>
                                <?php } ?>
                                <a href="<?php the_permalink(); ?>">Read More</a>
                            </div>
                        </div>
                        <?php endwhile ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>