<?php
    get_header();
    $pageID=get_the_id();
?>

<section class="singleBannerBlock programme" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
    <div class="container">
        <div class="singleBannerArea">
            <ul>
                <li>
                    <a href="<?php echo esc_url( home_url( '/' )); ?>">Home</a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/programme/' )); ?>">
                        Programme
                    </a>
                </li>
                <li>
                    <a href="<?php echo get_the_permalink($pageID); ?>"><?php the_title(); ?></a>
                </li>

            </ul>
            <div class="bannerContent"  data-aos="fade-up" data-aos-duration="1000">
                <?php $banner_title = get_banner_title(); ?>
                    <h1><?php echo esc_html($banner_title); ?></h1>
                    <?php $programme_details = get_field('programme_details', $pageID);
                    if ($programme_details && is_array($programme_details)) {
                        $first_row = $programme_details[0]; ?>
                        <p>
                            <?php echo $first_row['title']; ?>
                            <span><?php echo $first_row['details']; ?></span>
                        </p>
                    <?php } ?>
                    <a hre="#">Apply Now</a>
            </div>
        </div>
        <div class="programmeForm">
            <h4>Enquire Now</h4>
            <?php echo do_shortcode('[contact-form-7 id="1bc04e2" title="Program - Enquiry"]'); ?>
        </div>
    </div>
</section>
<section class="programmeContentBlock">
    <div class="container">
    <?php $about = get_field('about_programme',$pageID);
    if(isset($about) && !empty($about)){?>
        <div class="programmeAboutArea" data-aos="fade-up" data-aos-duration="1000">
            <?php if($about['about']) { ?>
                <div class="programmeAboutLeft" >
                    <?php echo $about['about'];?>
                </div>
            <?php } ?>
            <?php if($about['about_image']) {?>
                <div class="programmeAboutRight">
                    <img src="<?php echo $about['about_image'];?>" alt="Sriher" />
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if ( have_rows('programme_details', $pageID) ) : ?>
        <ul class="programmeDetailArea">
        <?php while ( have_rows('programme_details', $pageID) ) : the_row(); ?>
            <li>
                <?php echo get_sub_field("title", $pageID); ?>
                <span><?php echo get_sub_field("details", $pageID); ?></span>
            </li>
        <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <?php if ( have_rows('downloads', $pageID) ) : ?>
        <div class="programmeDocArea">
            <h2>Downloads</h2>
            <div class="programmeDoc">
                <?php while ( have_rows('downloads', $pageID) ) : the_row(); ?>
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div class="programmeDocBox">
                        <div class="thumb">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/newsletterThumb.svg" alt="Sriher" />
                        </div>
                        <div class="detail">
                            <p><?php echo get_sub_field("file_title", $pageID); ?></p>
                            <a href="<?php echo get_sub_field("download_file", $pageID); ?>" target="_blank">Download PDF</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php $campus_details = get_field('campus_details',$pageID); 
if(isset($campus_details) && !empty(array_filter($campus_details))){ ?>
<section class="programmeCampusBlock" style="background-image: url(<?php echo $campus_details['campus_background'];?>);" >
    <div class="container">
    <span class="yellowTriangle" data-aos="fade-down" data-aos-duration="1500" data-aos-delay="300"></span>
    
        <div class="programmeCampusArea">
            <?php if($campus_details['details']) { ?>
                <div class="programmeCampusLeft">
                    <?php echo $campus_details['details'];?>
                </div>
            <?php } ?>
            <?php if($campus_details['campus_logo']) {?>
                <div class="programmeCampusRight">
                    <img src="<?php echo $campus_details['campus_logo'];?>" alt="Sriher" />
                </div>
            <?php } ?>
        </div>
    
    </div>
</section>
<?php } ?>

<?php if (have_posts()) : the_post(); ?>
    <?php the_content()?>
<?php endif;  ?>
<?php get_footer();?>