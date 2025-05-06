<?php
    /*
    Template Name: Contact Us
    */
    get_header();
    $pageID = get_the_ID();
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
        <script>
        </script>
	<?php
    }
?>

<section class="bannerBlock banner" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
    <div class="container">
        <div class="bannerArea">
            <?php $banner_title = get_banner_title(); ?>
            <h1><?php echo esc_html($banner_title); ?></h1>
            <?php custom_breadcrumbs(); ?>
        </div>
    </div>
</section>
<section class="contactBlock">
    <div class="container">
        <?php $post_address = get_field('post_address', $pageID);
        if(isset($post_address) && !empty($post_address)){?>
            <?php if($post_address['address']) { ?>
                <div class="contactArea">
                    <h4>
                        <img src="<?php echo $post_address['icon'];?>" alt="Sriher" width="24" height="24" />
                        <?php echo $post_address['title'];?>
                    </h4>
                    <div class="contactDetail"><?php echo $post_address['address'];?></div>
                </div>
            <?php } ?>
        <?php } ?>
        
        <?php $basic_contact_details = get_field('basic_contact_details', $pageID);
        if(isset($basic_contact_details) && !empty($basic_contact_details)){?>
            <div class="contactArea">
                <h4>
                    <img src="<?php echo $basic_contact_details['icon'];?>" alt="Sriher" width="24" height="24" />
                    <?php echo $basic_contact_details['title'];?>
                </h4>
                <div class="contactDetail">
                    <?php $phone_number = $basic_contact_details['phone_number']; 
                    if($phone_number) { ?>
                    <p>
                        <img src="<?php echo $phone_number['icon'];?>" alt="Sriher" width="24" height="24" />
                        <span>PHONE</span>
                        <?php echo $phone_number['phone'];?>
                    </p>
                    <?php } ?>

                    <?php $fax_number = $basic_contact_details['fax_number']; 
                    if($fax_number) { ?>
                    <p>
                        <img src="<?php echo $fax_number['icon'];?>" alt="Sriher" width="24" height="24" />
                        <span>Fax</span>
                        <?php echo $fax_number['fax'];?>
                    </p>
                    <?php } ?>

                    <?php $email_address = $basic_contact_details['email_address']; 
                    if($email_address) { ?>
                    <p>
                        <img src="<?php echo $email_address['icon'];?>" alt="Sriher" width="24" height="24" />
                        <span>Email</span>
                        <?php foreach ( $email_address['emails'] as $email){ ?>
                            <a href="mailto:<?php echo $email['email'];?>">
                                <?php echo $email['email'];?>
                            </a>
                        <?php } ?>
                    </p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php $location = get_field('location', $pageID);
        if(isset($location) && !empty($location)){?>
            <div class="contactArea">
                <h4>
                    <img src="<?php echo $location['icon'];?>" alt="Sriher" width="24" height="24" />
                    <?php echo $location['title'];?>
                </h4>
                <div class="contactDetail">
                    <?php echo $location['map_iframe'];?>
                </div>
            </div>
        <?php } ?>

        <?php if (have_posts()) : the_post(); ?>
            <?php the_content()?>
        <?php endif;  ?>
    </div>
</section>

<?php get_footer(); ?>