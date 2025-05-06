<?php
    /*
    Template Name: List Page
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

<div class="wrapper about_page">
    <section class="bannerBlock">
        <div class="bannerBg" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');"></div>
        <div class="container">
            <div class="bannerArea">
                <?php $banner_title = get_banner_title(); ?>
                <h1><?php echo esc_html($banner_title); ?></h1>
                <ul>
                    <li>
                        <a href="<?php echo esc_url( home_url( '/' )); ?>">Home</a>
                    </li>
                    <?php if ($parent_id) { ?>
                    <li>
                        <a href="<?php echo get_the_permalink($parent_id); ?>">
                            <?php echo get_the_title($parent_id); ?>
                        </a>
                    </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo get_the_permalink($pageID); ?>"><?php the_title(); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
    <section class="contentBlock">
        <div class="container">
            <div class="contentArea">
                <?php if(have_posts()): while(have_posts()): the_post()?>
                <div class="contentLeft">
                    <div class="sidebarDrop"><?php the_title(); ?></div>
                    <?php $selected_side_menu = get_field('sidebar_menu',$pageID); ?>
                    <?php wp_nav_menu(array('theme_location' => $selected_side_menu)); ?>
                </div>
                <div class="contentRight">
                    <div class="mainContent">
                        <?php $listItems = get_field('list_item',$pageID);
                        if(isset($listItems) && !empty($listItems)) { ?>
                        <div class="listGroup">
                        <?php foreach ($listItems as $item) { ?>
                            <div class="listItem">
                                <div class="listThumb">
                                    <img src="<?php echo $item["thumbnail"]; ?>" alt="<?php the_title(); ?>"
                                    width="280" height="160" fetchpriority="high" />
                                </div>
                                <div class="listCaption">
                                    <?php echo $item["details"]; ?>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>