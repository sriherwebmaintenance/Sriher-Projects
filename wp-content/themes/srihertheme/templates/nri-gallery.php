<?php
    /*
    Template Name: NRI Gallery Page
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
                    <div class="galleryGrid">
                        <?php
                            $terms = get_terms(array(
                                'taxonomy' => 'gallery-category',
                                'hide_empty' => false,
                            ));
                            $termId = $terms[0]->term_id;
                            $args = array(
                                'paged' => $paged,
                                'posts_per_page'   => 6,
                                'post_type'      => 'gallery',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'gallery-category',
                                        'field' => 'term_id',
                                        'terms' => $termId,
                                    ),
                                ),
                            );
                            $gallery = new WP_Query($args);
                        ?>
                        <?php if($gallery->have_posts()) : ?>
                        <?php while($gallery->have_posts()) : $gallery->the_post() ?>
                            <a href="<?php the_permalink($gallery->ID); ?>" class="galleryBox">
                                <div class="galleryThumb">
                                    <?php 
                                    if(has_post_thumbnail($gallery->ID)&&(get_the_post_thumbnail($gallery->ID)!='')){
                                    $post_thumb = get_the_post_thumbnail_url($gallery->ID); ?>
                                        <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                    <?php } ?>
                                </div>
                                <div class="galleryCaption">
                                    <h4><?php the_title(); ?></h4>
                                    <p><?php echo get_the_date('d M Y'); ?></p>
                                </div>
                            </a>
                        <?php endwhile ?>
                        <?php endif; ?>
                    </div>
                    <?php if($gallery->max_num_pages > 1){ ?> 
                        <div class="pagination paginate">
                            <?php
                                echo paginate_links(array(
                                    'base' => str_replace(999999, '%#%', esc_url(get_pagenum_link(999999))),
                                    'format' => '?paged=%#%',
                                    'current' => max(1, get_query_var('paged')),
                                    'total' => $gallery->max_num_pages,
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