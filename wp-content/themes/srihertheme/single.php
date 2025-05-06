<?php
    get_header();
    $pageID=get_the_id();
    $postType = get_post_type();
    $postCategory = $postType;
    $category = '';
    $terms = get_the_terms($pageID, 'media-category');
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            // Output the term name
            $category = $term->name;
        }
    }
    $impacts = get_the_terms($pageID, 'project-category');
    if ($impacts && !is_wp_error($impacts)) {
        foreach ($impacts as $impact) {
            // Output the term name
            $impact = $impact->slug;
        }
    }
    if ($postType === 'post') {
        $postCategory = 'blogs';
    } else if ($postType === 'srmcblog') {
        $postType = 'students-corner';
        $postCategory = 'Students Corner';
    } else if ($category === 'iqac') {
        $postCategory = 'IQAC ' . $postType;
        $postType = 'iqac-' . $postType;
    }
?>

<section class="singleBannerBlock" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
    <div class="container">
        <div class="singleBannerArea">
            <?php $banner_title = get_banner_title(); ?>
            <h1><?php echo esc_html($banner_title); ?></h1>
            <?php if ($postType === 'project') {
                custom_breadcrumbs();
            } else { ?>
                <p>Posted by SRIHER on 
                    <?php echo get_the_date('d F Y'); ?> in 
                    <span><?php echo $postCategory; ?></span>
                </p>
            <?php } ?>
            
        </div>
    </div>
</section>
<section class="singleContentBlock">
    <div class="container">
        <div class="singleContentArea">
            <?php if(have_posts()): while(have_posts()): the_post()?>
            <?php echo get_the_content(); ?>
            <?php endwhile; endif; ?>
        </div>
        <div class="bottomBlock">
            <?php if ($postType === 'project') { ?>
                <a href="<?php echo esc_url( home_url( '/impact-ranking/' . $impact . '/' ) ); ?>" class="view_all">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/category.svg" alt="Category" />
                    View all <span><?php echo $postCategory; ?></span>
                </a>
            <?php } else { ?>
                <a href="<?php echo esc_url( home_url( '/' . $postType . '/' ) ); ?>" class="view_all">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/category.svg" alt="Category" />
                    View all <span><?php echo $postCategory; ?></span>
                </a>
            <?php } ?>
            <?php if ( is_active_sidebar( 'share_widget' ) ) : ?>
                <div class="share_widget">
                    <span>Share </span>
                    <?php dynamic_sidebar( 'share_widget' ); ?>
                </div><!-- #primary-sidebar -->
            <?php endif; ?>
        </div>
    </div>
</section>
<?php get_footer();?>