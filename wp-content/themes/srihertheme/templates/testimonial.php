<?php
/*
Template Name: Testimonial Page
*/
get_header();
$pageID = get_the_ID();
if (has_post_parent($pageID)) {
    $parent_id = $post->post_parent;
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
                <?php if (have_posts()) { while (have_posts()) { the_post(); ?>
                    <div class="contentLeft">
                        <div class="sidebarDrop"><?php echo get_the_title(); ?></div>
                        <?php
                        $terms = get_terms(array(
                            'taxonomy' => 'testimony-category',
                            'hide_empty' => false,
                        ));
                        if (!empty($terms) && !is_wp_error($terms)) : ?>
                            <ul class="menu">
                                <li class="<?php echo (!isset($_GET['category']) || $_GET['category'] == 'all') ? 'current_page_item' : ''; ?>">
                                    <a href="?category=all">All Testimonials</a>
                                </li>
                                <?php foreach ($terms as $term) { ?>
                                    <li class="<?php echo (isset($_GET['category']) && $_GET['category'] == $term->slug) ? 'current_page_item' : ''; ?>">
                                        <a href="?category=<?php echo esc_attr($term->slug); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="contentRight">
                        <div class="testimonyArea">
                            <?php
                            $category = isset($_GET['category']) ? sanitize_title($_GET['category']) : 'all';

                            $post_args = array(
                                'post_type' => 'testimonials',
                                'posts_per_page' => 5,
                                'order' => 'DESC',
                                'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
                            );

                            if ($category && $category != 'all') {
                                $post_args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'testimony-category',
                                        'field' => 'slug',
                                        'terms' => $category,
                                    ),
                                );
                            }

                            $post_query = new WP_Query($post_args);
                            if ($post_query->have_posts()) { ?>
                                <?php while ($post_query->have_posts()) {
                                    $post_query->the_post();
                                    $postID = get_the_ID(); ?>
                                    <div class="card">
                                        <div class="content">
                                            <?php the_content(); ?>
                                        </div>
                                        <button type="button" class="readMore">Read More</button>
                                        <div class="nameTag">
                                            <div class="left">
                                                <?php 
                                                if (has_post_thumbnail($postID)) {
                                                    $post_thumb = get_the_post_thumbnail_url($postID); 
                                                } ?>
                                                <img src="<?php echo esc_url($post_thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                            </div>
                                            <div class="right">
                                                <h3><?php echo esc_html(get_the_title()); ?></h3>
                                                <?php if (get_field('designation')) { ?>
                                                    <h5><?php echo esc_html(get_field('designation')); ?></h5>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php wp_reset_postdata(); ?>
                            <?php } else { ?>
                                <p>No posts found.</p>
                            <?php } ?>
                        </div>
                        <?php if ($post_query->max_num_pages > 1) { ?> 
                            <div class="pagination paginate">
                                <?php
                                echo paginate_links(array(
                                    'base' => add_query_arg(array('paged' => '%#%', 'category' => $category)),
                                    'format' => '?paged=%#%',
                                    'current' => max(1, get_query_var('paged')),
                                    'total' => $post_query->max_num_pages,
                                    'prev_next' => true,
                                    'prev_text' => __('<img src="'.get_stylesheet_directory_uri().'/images/prev.svg" alt="prev" /> Prev'),
                                    'next_text' => __('Next <img src="'.get_stylesheet_directory_uri().'/images/next.svg" alt="next" />'),
                                ));
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } } ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
