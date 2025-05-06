<?php
    /*
    Template Name: Partner University
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
                    <?php $partner_universities = get_field('partner_universities', $pageID);
                    if(isset($partner_universities) && !empty($partner_universities)) {
                        foreach($partner_universities as $university_list){ ?>
                        <div class="universityArea">
                            <h4><?php echo $university_list['continent']; ?></h4>
                            <?php $universities = $university_list['university_list'];
                            if(isset($universities) && !empty($universities)) { ?>
                                <div class="universityGrid">
                                <?php foreach($universities as $university) { ?>
                                    <div class="universityBox">
                                        <div class="universityThumb">
                                        <?php if($university['logo']) { ?>
                                            <img src="<?php echo $university['logo'];?>" alt="Sriher" width="200" height="200" />
                                        <?php } else { ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/newsletterThumb.svg" alt="Sriher" width="200" height="200" class="default" />
                                        <?php } ?>
                                        </div>
                                        <div class="universityCaption">
                                            <?php echo $university['university'];?>
                                        </div>
                                    </div>
                                <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } } ?>
                        <?php the_content()?>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>