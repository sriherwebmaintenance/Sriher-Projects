<?php
get_header();
$pageID = get_the_ID();
$parent_id = null;
if (has_post_parent($pageID)) {
    $parent_id = $post->post_parent;
}
add_action('wp_footer', 'page_scripts', 25);
function page_scripts()
{ ?>
    <script>
        $(document).ready(function() {
            $(".sidebarDrop").click(function() {
                $('.menu').toggleClass("open");
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initially activate the first tab
            $('ul li:first').addClass('current_page_item');
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
                <div class="sidebarDrop"><?php the_title(); ?></div>
                <?php
                $thematic_post_type = get_field('choose_post_type', $pageID);
                $thematic_cluster_terms = wp_get_post_terms($pageID, 'cluster');
                if (!empty($thematic_cluster_terms)) {
                    $thematic_cluster_slug = wp_list_pluck($thematic_cluster_terms, 'term_id');
                    $cluster_args = array(
                        'post_type' => 'thematic-cluster',
                        'fields' => 'ids',
                        'post_parent'    => 0,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'cluster',
                                'field'    => 'term_id',
                                'terms'    => $thematic_cluster_slug[0],
                            ),
                        ),
                    );

                    $cluster_postID = get_posts($cluster_args);
                    $parent_menuid = $cluster_postID[0];
                }
                $custom_left_menu = get_field('leftmenu_items', $parent_menuid);
                if (isset($custom_left_menu) && !empty($custom_left_menu)) {
                    $curr_url = get_permalink($pageID);
                ?>
                    <ul class="menu">
                        <?php
                        $first = true;
                        foreach ($custom_left_menu as $menu_item) { ?>
                            <li class="<?php echo ($first ? 'current_page_item ' : '') . ($menu_item['url'] == $curr_url ? 'current_page_item' : '');
                                        $first = false; ?>">
                                <a href="<?php echo $menu_item['url']; ?>">
                                    <?php echo $menu_item['menu_title']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <div class="contentRight">
                <?php if (have_posts()) : while (have_posts()) : the_post() ?>
                        <?php the_content(); ?>
                <?php endwhile;
                endif; ?>
                <div class="mainContent">
                    <?php


                    if (!empty($thematic_cluster_terms)) {
                        $thematic_cluster_slug = wp_list_pluck($thematic_cluster_terms, 'term_id');

                        $args = array(
                            'post_type' => $thematic_post_type,
                            'post_parent'    => $pageID,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'cluster',
                                    'field'    => 'term_id',
                                    'terms'    => $thematic_cluster_slug[0],
                                ),
                            ),
                            // 'orderby' => 'date',
                            'order' => 'ASC',
                            'orderby' => 'title',
                        );



                        $query = new WP_Query($args); ?>
                        <?php if ($query->have_posts()) : ?>
                            <div class="listGroupArea">
                                <?php while ($query->have_posts()) : $query->the_post(); ?>
                                    <div class="<?php echo ($thematic_post_type === 'publication') ? 'listItem publication' : 'listItem'; ?>">
                                        <a href="<?php the_permalink(); ?>" class="listThumb">
                                            <?php
                                            if (has_post_thumbnail($query->ID) && (get_the_post_thumbnail($query->ID) != '')) {
                                                $post_thumb = get_the_post_thumbnail_url(); ?>
                                                <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                            <?php } else { ?>
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                            <?php } ?>
                                        </a>
                                        <div class="listCaption">
                                            <h2><?php echo get_the_title(); ?></h2>
                                            <?php $facility_type = get_field('facility_type', $query->ID);
                                            if ($facility_type) {
                                                echo '<h6>' . $facility_type . '</h6>';
                                            }
                                            ?>
                                            <p><?php echo wp_trim_words(get_the_content(), 40); ?>...</p>
                                            <?php if ($thematic_post_type !== 'publication') { ?>
                                                <a href="<?php the_permalink(); ?>">Learn More</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php endwhile ?>
                            </div>
                        <?php else : ?>
                            No <?php echo $thematic_post_type; ?> found.
                    <?php endif;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>