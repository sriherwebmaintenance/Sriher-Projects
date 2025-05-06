<?php
    get_header();
    $pageID=get_the_id();
    if(has_post_parent($pageID)) {
        $parent_id = $post->post_parent;
    }
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('.customFileBox input');
            const fileNameDisplay = document.getElementById('file-name-display');
            
            if (fileInput && fileNameDisplay) {
                fileInput.addEventListener('change', function() {
                    const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : 'No file chosen';
                    fileNameDisplay.textContent = fileName;
                });
            } else {
                console.log('File input or file name display element not found');
            }
        });
    </script>
	<?php
    }
?>

<section class="bannerBlock banner" style="background-image: url('<?php echo esc_url(get_banner_image_url()); ?>');">
    <div class="container">
        <div class="bannerArea">
        <?php $banner_title = get_the_title(); ?>
        <h1><?php echo esc_html($banner_title); ?></h1>
        <?php custom_breadcrumbs(); ?>
        </div>
    </div>
</section>
<section class="commonContentBlock">
    <div class="container">
        <div class="contentColumnLeft">
            <div class="careerForm">
                <h2><?php echo get_field('careerform_title', 'option'); ?></h2>
                <?php echo do_shortcode('[contact-form-7 id="501daf5" title="Career Form"]'); ?>
            </div>
        </div>
        <div class="contentColumnRight">
            <?php $mainTitle = get_field('main_title', 'option'); ?>
            <?php if($mainTitle) { ?>
                <h2><?php echo $mainTitle;?></h2>
            <?php } ?>
            <?php if ( have_rows('process_steps', 'option') ) : ?>
                <ul class="processArea">
                <?php while ( have_rows('process_steps', 'option') ) : the_row(); ?>
                    <li class="processBox">
                        <img src="<?php echo get_sub_field('icon'); ?>" alt="Process" width="30" height="30" />
                        <p><?php echo get_sub_field("process_step"); ?></p>
                    </li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>