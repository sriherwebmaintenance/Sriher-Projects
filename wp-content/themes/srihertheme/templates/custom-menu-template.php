<?php
    /*
    Template Name: Template with Custom Menu
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
                    <div class="menu-about-sidebar-container">
                        <?php $sidebar = get_field('custom_menu', $pageID);
                        if (isset($sidebar) && !empty($sidebar)) {?>
                        <ul id="menu-about-sidebar" class="menu">
                            <?php foreach ($sidebar as $reptr) { ?>
                            <li id="menu-item-326" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-326"><a href="<?php echo $reptr['url'];?>"><?php echo $reptr['menu_name'];?></a></li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </div>
                </div>
                <div class="contentRight">
                    <div class="mainContent">
                        <?php the_content()?>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>