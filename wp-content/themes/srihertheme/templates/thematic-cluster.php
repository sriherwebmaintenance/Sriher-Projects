<?php
     /*
    Template Name: Thematic Cluster Page
    */
    get_header();
    $pageID = get_the_ID();
    $parent_id = null;
    if(has_post_parent($pageID)) {
        $parent_id = $post->post_parent;
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
<div class="contentBlock">
    <div class="container">
        <div class="contentArea">
            <div class="contentFull">
                <?php echo get_the_content()?>
                <?php
                $args = array(
                    'paged'          => $paged,    
                    'post_type'      => 'thematic-cluster',
                    'order'          => 'ASC'
                );

                $terms = new WP_Query($args);
                if($terms->have_posts()) : ?>
                    <div class="thematicCategoryArea">
                    <?php while($terms->have_posts()) : $terms->the_post() ?>
                        <div class="thematicCategoryBox">
                            <?php 
                            if(has_post_thumbnail($terms->ID)&&(get_the_post_thumbnail($terms->ID)!='')){
                            $post_thumb = get_the_post_thumbnail_url($terms->ID); ?>
                                <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                            <?php } ?>
                            <a href="<?php the_permalink($terms->ID); ?>" class="thematicCategoryCaption">
                                <?php the_title(); ?>
                            </a>
                        </div>
                    <?php endwhile ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>