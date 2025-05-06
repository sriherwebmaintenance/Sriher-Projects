<?php
    /*
    Template Name: SRIIC Incubatees Page
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
        <script>
            jQuery(document).ready(function ($) {
                $('.menu li a').on('click', function (e) {
                    e.preventDefault();
                    var termId = $(this).data('term-id');
                    $('.menu li').removeClass('current_page_item');
                    $(this).parent().addClass('current_page_item');

                    // AJAX request to load related custom posts
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'load_custom_posts',
                            term_id: termId,
                        },
                        success: function (response) {
                            $('.listGroup').html(response);
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    });
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
                <div class="sidebarDrop"><?php echo get_the_title(); ?></div>
                <?php
                $terms = get_terms(array(
                    'taxonomy' => 'incubatees',
                    'hide_empty' => false,
                ));
                if( ! empty( $terms ) && ! is_wp_error( $terms ) ) : 
                $c= 0; ?>
                <ul class="menu">
                    <?php foreach ( $terms as $index => $term ) { 
                        $c++;  ?>
                    <li <?php echo ($c===1)?'class="current_page_item"':''; ?>>
                        <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" data-term-id="<?php echo $term->term_id; ?>">
                            <?php echo esc_html($term->name); ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="contentRight">
                <div class="mainContent">
                <div class="listGroupArea">
                <?php $termId = $terms[0]->term_id;
                    $args = array(
                        'post_type' => 'incubatee',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'incubatees',
                                'field' => 'term_id',
                                'terms' => $termId,
                            ),
                        ),
                    );

                    $incubatees = new WP_Query($args);

                    if ($incubatees->have_posts()) {
                        while($incubatees->have_posts()) : $incubatees->the_post(); ?>
                            <div class="listItem">
                                <a href="<?php the_permalink(); ?>" class="listThumb">
                                <?php 
                                    if(has_post_thumbnail($incubatees->ID)&&(get_the_post_thumbnail($incubatees->ID)!='')){
                                    $post_thumb = get_the_post_thumbnail_url(); ?>
                                        <img src="<?php echo $post_thumb; ?>" alt="<?php the_title(); ?>">
                                    <?php } else { ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Sriher" class="default" />
                                    <?php } ?>
                                    </a>
                                <div class="listCaption">
                                    <h2><?php echo get_the_title(); ?></h2>
                                    <p><?php echo wp_trim_words(get_the_content(), 40); ?>...</p>
                                    <a href="<?php the_permalink(); ?>">Learn More</a>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    } else {
                        echo 'No posts found.';
                    } ?>

                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>