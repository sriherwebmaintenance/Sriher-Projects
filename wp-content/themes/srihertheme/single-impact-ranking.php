<?php
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
            });
        </script>
	<?php
    }
?>

<div class="wrapper about_page">
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
                <div class="contentFull">
                    <div class="mainContent">
                        <?php the_content()?>

                        <?php
                        $terms = get_the_terms($pageID, 'project-category');
                        $termId = $terms[0]->term_id;
                        $args = array(
                            'post_type' => 'project',
                            'posts_per_page'   => 6,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'project-category',
                                    'terms' => $termId,
                                    'field' => 'term_id',
                                ),
                            ),
                        );

                        $projects = new WP_Query($args);

                        if ($projects->have_posts()) { ?>
                        <div class="projectArea">
                            <h2>Projects</h2>
                            <div class="projectGrid">
                                <?php while($projects->have_posts()) : $projects->the_post(); ?>
                                    <div class="projectBox">
                                        <a href="<?php the_permalink(); ?>" class="projectThumb">
                                        <?php 
                                            if(has_post_thumbnail($projects->ID)&&(get_the_post_thumbnail($projects->ID)!='')){
                                            $post_thumb = get_the_post_thumbnail_url(); ?>
                                                <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                            <?php } else { ?>
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                            <?php } ?>
                                            </a>
                                        <div class="projectCaption">
                                            <h4><?php echo get_the_title(); ?></h4>
                                            <a href="<?php the_permalink(); ?>">Read More</a>
                                        </div>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();?>
                            </div>
                        </div>
                        <?php } else {
                            echo 'No posts found.';
                        } ?>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>