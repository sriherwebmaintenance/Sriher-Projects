<?php
    /*
    Template Name: Students Corner Page
    */
    get_header();
    $pageID = get_the_ID();

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
            <div class="contentLeft">
                <div class="sidebarDrop">Categories</div>
                <?php
                $terms = get_terms(array(
                    'taxonomy' => 'srmcblog-category',
                    'hide_empty' => false,
                ));
                if( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                <ul class="menu">
                    <li class="menuTitle">
                        <h4>Categories</h4>
                    </li>
                    <?php foreach ( $terms as $index => $term ) {
                        $term_count = $term->count; ?>
                    <li>
                        <a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
                            <?php echo esc_html($term->name); ?>
                            (<?php echo $term_count; ?>)
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="contentRight">
                <div class="blogGrid studentsGrid">
                    <?php
                        $args = array(
                            'paged' => $paged,    
                            'post_type'      => 'srmcblog',
                            'posts_per_page'   => 12,
                        );
                        $collection = new WP_Query($args);
                    ?>
                    <?php if($collection->have_posts()) : ?>
                    <?php while($collection->have_posts()) : $collection->the_post() ?>
                        <div class="blogBox">
                            <div class="blogThumb">
                                <?php 
                                if(has_post_thumbnail($collection->ID)&&(get_the_post_thumbnail($collection->ID)!='')){
                                $post_thumb = get_the_post_thumbnail_url($collection->ID); ?>
                                    <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                <?php } else { ?>
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                <?php } ?>
                            </div>
                            <div class="blogCaption">
                                <p class="tag">
                                    <?php
                                    $taxonomy_terms = get_the_terms($collection->ID, 'srmcblog-category');
                                    if ($taxonomy_terms && !is_wp_error($taxonomy_terms)) {
                                        $terms_list = [];
                                        foreach ($taxonomy_terms as $term) {
                                            $terms_list[] = $term->name;
                                        }
                                        echo implode(', ', $terms_list);
                                    }
                                    ?>
                                </p>
                                <h4><a href="<?php the_permalink($collection->ID); ?>"><?php the_title(); ?></a></h4>
                                <a href="<?php the_permalink($collection->ID); ?>">Read More</a>
                            </div>
                        </div>
                    <?php endwhile ?>
                    <?php endif; ?>
                </div>
                <?php if($collection->max_num_pages > 1){ ?> 
                    <div class="pagination paginate">
                        <?php
                            echo paginate_links(array(
                                'base' => str_replace(999999, '%#%', esc_url(get_pagenum_link(999999))),
                                'format' => '?paged=%#%',
                                'current' => max(1, get_query_var('paged')),
                                'total' => $collection->max_num_pages,
                                'prev_next'          => true,
                                'prev_text'          => __('<img src="'.get_stylesheet_directory_uri().'/images/prev.svg" alt="prev" /> Prev'),
                                'next_text'          => __('Next <img src="'.get_stylesheet_directory_uri().'/images/next.svg" alt="next" />'),
                            ));
                        ?> 
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>