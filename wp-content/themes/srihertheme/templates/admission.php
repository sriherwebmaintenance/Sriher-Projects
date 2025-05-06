<?php
    /*
    Template Name: Admission
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
                    <?php $selected_side_menu = get_field('sidebar_menu',$pageID); ?>
                    <?php wp_nav_menu(array('theme_location' => $selected_side_menu)); ?>
                </div>
                <div class="contentRight">
                    <div class="mainContent">
                    <?php $admission_list = get_field('admission_list', $pageID);
                        if($admission_list) { ?>
                        <div class="admissionGrid">
                            <?php foreach ( $admission_list as $item){ ?>
                                <a href="<?php echo $item['external_url'];?>" target="_blank" class="admissionBox">
                                    <div class="admissionThumb">
                                        <img src="<?php echo $item['icon'];?>" alt="Sriher" width="46" height="46" />
                                    </div>
                                    <div class="admissionCaption">
                                        <h4><?php echo $item['title'];?></h4>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                        <?php the_content()?>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>