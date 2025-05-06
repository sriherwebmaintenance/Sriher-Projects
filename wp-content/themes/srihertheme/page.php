<?php
     /*
    Template Name: Default Page
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
                <?php echo get_the_content(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>