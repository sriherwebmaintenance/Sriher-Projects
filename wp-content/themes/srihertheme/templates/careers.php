<?php
    /*
    Template Name: Careers
    */
    get_header();
    $pageID = get_the_ID();
    wp_enqueue_script('Swiper');
    wp_enqueue_script('magnificPopup');
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
        <script>
            function filterJobs(skillset, college, page){
                $.ajax({
                    url: '<?php echo admin_url(); ?>admin-ajax.php',
                    type: 'post',
                    data: {
                        action: 'filter_jobs',
                        skillset: skillset,
                        college: college,
                        page: page,
                    },
                    success: function(response){
                        $('#jobs').html(response);
                        history.pushState({}, document.title, window.location.pathname);
                        $('.pagination').html($('.pagination', response).html());
                    }
                });
            }
           jQuery(document).ready(function($) {
            $('#skillset, #college').change(function() {
                var skillset = $('#skillset').val();
                var college = $('#college').val();
                var currentPage = 1; // Set the initial page number
                filterJobs(skillset, college, currentPage);
            });
            $(document).on('click', '#submit', function(event){
                event.preventDefault();
                var page = 1; // Get the page number from the URL 
                var degree = $('#degree').val();
                var college = $('#college').val();
                filterJobs(degree, college, page);
            });
            // Function to handle pagination click
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                var page = $(this).html(); // Get the page number from the URL 
              //  alert(page);
                var skillset = $('#skillset').val();
                var college = $('#college').val();
                filterJobs(skillset, college, page);
            });
           });
        </script>
        <script>
            $(document).ready(function() {
                var swiper = new Swiper(".life-slider", {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    pagination: {
                        el: ".swiper-pagination-1",
                        clickable: "true",
                    },
                    loop:true,
                    breakpoints: {
                        2500: {
                            slidesPerView: 4,
                        },
                        992: {
                            slidesPerView: 3,
                        },
                        768: {
                            slidesPerView: 2,
                        },
                        450: {
                            slidesPerView: 2,
                        },
                        200: {
                            slidesPerView: 1,
                        },
                    }
                });

                var swiper = new Swiper(".testimonialSlider", {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    navigation: {
                        prevEl: ".swiper-button-prev",
                        nextEl: ".swiper-button-next",
                    },
                    breakpoints: {
                        2500: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1500: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1200: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        700: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                        600: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                        200: {
                            slidesPerView: 1,
                            spaceBetween: 20,
                        },
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.popup-gallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                tLoading: 'Loading image #%curr%...',
                mainClass: 'mfp-img-mobile',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0,1]
                },
                image: {
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                    titleSrc: function(item) {
                    return item.el.attr('title');
                    }
                }
                });
            });
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
<?php 
    $tax_query =  array(
        'relation' => 'IN',
    );
    $skillset ='';
    if(isset($_GET['skillset']) && ($_GET['skillset']!='')){
        $skill_data = array(
                'taxonomy' => 'skillset',
                'field' => 'slug',
                'terms' => $_GET['skillset']
              );
        array_push($tax_query, $skill_data);
        $skillset = $_GET['skillset'];
      }
      $college ='';
      if(isset($_GET['college']) && ($_GET['college']!='')){
          $college_data = array(
                  'taxonomy' => 'college',
                  'field' => 'slug',
                  'terms' => $_GET['college']
                );
          array_push($tax_query, $college_data);
          $college = $_GET['college'];
      } 

$skill_args = array(
    'show_option_none' => __('Select Skillset'),
    'show_count'       => 1,
    'orderby'          => 'name',
    'echo'             => 0,
    'name'             => 'skillset',
    'class'              => '',
    'taxonomy'           => 'skillset',
    'value_field'      => 'slug',
    'hierarchical' => true,
    'show_count' => 0,
    'hide_empty' => false,
    'option_none_value'  => '',
    'selected'           => $skillset,
  );
$select_college  = wp_dropdown_categories( $skill_args); 
$college_args = array(
    'show_option_none' => __('Select College'),
    'show_count'       => 1,
    'orderby'          => 'name',
    'echo'             => 0,
    'name'             => 'college',
    'class'              => '',
    'taxonomy'           => 'college',
    'value_field'      => 'slug',
    'hierarchical' => true,
    'show_count' => 0,
    'hide_empty' => false,
    'option_none_value'  => '',
    'selected'           => $college,
    'parent' => '0',
  );
$select_skill  = wp_dropdown_categories( $college_args); 
?>
<section class="filterBlock">
    <div class="container">
        <div class="filterArea">
            <h4>Filter By</h4>
            <?php echo $select_college; ?> 
            <?php echo $select_skill; ?> 
            <button type="submit" id="submit">Search</button>
        </div>
    </div>
</section>

<section class="programmeListBlock">
    <div class="container">

    <?php $infoContent = get_field('info_card',$pageID);
    if ($infoContent) { ?>
    <div class="infoArea">
        <?php echo $infoContent; ?>
    </div>
    <?php } ?>
    <div id="jobs">
    <?php
  
    $date_now = time(); 
    $meta_query =  array(
        'meta_query' => array(
          array(
              'key'     => 'job_closing_date',
              'value'   => date('Y-m-d'),
              'compare' => '>=',
              'type'    => 'DATE'
          )
        ),
      );
        $args = array(
            'paged' => $paged,    
            'post_type'      => 'career',
            'post_status'   => 'publish',
            'posts_per_page' => 10,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query
        );
        $careers = new WP_Query($args);
    ?>
    <?php if($careers->have_posts()) : ?>
    <?php while($careers->have_posts()) : $careers->the_post() ?>
        <div class="programmeList careers">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php $content = get_the_content(); 
            if ($content) { ?>
            <div class="content">
                <?php echo $content ?>
            </div>
            <?php } ?>
            <a href="<?php the_permalink(); ?>">Apply Now</a>
        </div>
    <?php endwhile ?>
    <?php if($careers->max_num_pages > 1){ ?> 
        <div class="pagination paginate">
            <?php
                echo paginate_links(array(
                    'base' => str_replace(999999, '%#%', esc_url(get_pagenum_link(999999))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $careers->max_num_pages,
                    'prev_next' => true,
                    'prev_text' => __('<img src="'.get_stylesheet_directory_uri().'/images/prev.svg" alt="prev" /> Prev'),
                    'next_text' => __('Next <img src="'.get_stylesheet_directory_uri().'/images/next.svg" alt="next" />'),
                ));
            ?> 
        </div>
    <?php } ?>
    <?php endif; ?>
    </div>
    </div>
</section>

<section class="lifeBlock">
    <div class="container">
        <div class="lifeArea">
        <?php $life_at_sriher = get_field('life_at_sriher',$pageID);
        if(isset($life_at_sriher) && !empty($life_at_sriher)){?>
            <?php if($life_at_sriher['content']) { ?>
                <?php echo $life_at_sriher['content'];?>
            <?php } ?>
            
            <div class="swiper life-slider">
                <div class="swiper-wrapper">
                    <?php if(isset($life_at_sriher['gallery']) && !empty($life_at_sriher['gallery'])) { ?>
                        <?php $i=0; foreach ($life_at_sriher['gallery'] as $image): ?>
                        <div class="swiper-slide popup-gallery">
                            <a href="<?php echo $image["url"]; ?>" title="<?php echo $image["alt"]; ?>" class="lifeBox">
                                <img src="<?php echo $image["url"]; ?>" alt="<?php echo $image["alt"]; ?>"
                                width="280" height="160" fetchpriority="high" />
                            </a>
                        </div>
                        <?php $i++; endforeach; ?>
                    <?php } ?>
                </div>
                <div class="lifePagination swiper-pagination-1"></div>
            </div>
        <?php } ?>
        </div>
    </div>
</section>

<section class="testimonialBlock">
    <div class="container">
        <div class="testimonialHeader">
            <?php $testimonial = get_field('testimonial_title',$pageID);
            if($testimonial) { ?>
                <h2><?php echo $testimonial;?></h2>
            <?php } ?>
            <div class="navigation">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        <?php
            $post_args = array(
                'post_type' => 'testimonials',
                'paged' => $paged,
                'order' => 'DESC',
                'posts_per_page' => 5,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'testimony-category',
                        'field' => 'term_id',
                        'terms' => array(138),
                        'operator' => 'NOT IN',
                    ),
                ),
            );
            $post_query = new WP_Query( $post_args );
            if ( $post_query->have_posts()) { ?>
            <div class="swiper testimonialSlider">
                <div class="swiper-wrapper">
                <?php while ( $post_query->have_posts() ) : $post_query->the_post(); 
                    $postID = $post_query->post->ID; ?>
                    <div class="swiper-slide">
                        <div class="card">
                            <p>“<?php echo wp_trim_words( get_the_excerpt($postID), 52 ); ?>”</p>
                            <div class="nameTag">
                                <div class="left">
                                <?php 
                                if(has_post_thumbnail($postID)&&(get_the_post_thumbnail($postID)!='')){
                                $post_thumb = get_the_post_thumbnail_url($postID); 
                                } ?>
                                <img src="<?php echo $post_thumb; ?>" alt="<?php echo get_the_title();?>">
                                </div>
                                <div class="right">
                                    <h3><?php echo get_the_title();?></h3>
                                    <?php if(get_field('designation')) {?>
                                        <h5><?php echo get_field('designation');?></h5>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<?php if (have_posts()) : the_post(); ?>
    <?php the_content()?>
<?php endif;  ?>

<?php get_footer(); ?>