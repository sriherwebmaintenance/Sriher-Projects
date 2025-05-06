<?php
    /*
    Template Name: Programme
    */
    get_header();
    $pageID = get_the_ID();
    add_action('wp_footer','page_scripts',25);
    function page_scripts(){?>
       <script>
jQuery(document).ready(function($) {
    // Auto-submit form when college changes
    $('#college-select').on('change', function() {
        $(this).closest('form').submit();
    });
    
    // AJAX for degree dropdown population
    $('#college-select').on('change', function() {
        var collegeId = $(this).val();
        var degreeSelect = $('#degree-select');
        
        degreeSelect.empty().append('<option value="">Select Degree</option>');
        
        if (collegeId) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'get_degree_options',
                    college_id: collegeId
                },
                success: function(response) {
                    if (response.success) {
                        $.each(response.data, function(index, term) {
                            degreeSelect.append(
                                $('<option>', {
                                    value: term.term_id,
                                    text: term.name
                                })
                            );
                        });
                    }
                }
            });
        }
    });
});
</script>
<script>
// jQuery(document).ready(function($) {
//     // Auto-submit when college changes
//     $('#college-select').on('change', function() {
//         // Reset degree selection
//         $('#degree-select').val('');
//         $(this).closest('form').submit();
//     });
// });
</script>
	<?php
    }
    


// Get current selections from URL
$college = isset($_GET['college']) ? intval($_GET['college']) : '';
$degree = isset($_GET['degree']) ? intval($_GET['degree']) : '';
$paged = max(1, get_query_var('paged'));

// 1. College Dropdown (Parent Terms Only)
$college_args = array(
    'show_option_none' => __('Select College'),
    'orderby'          => 'name',
    'echo'             => 0,
    'name'             => 'college',
    'id'               => 'college-select',
    'class'            => '',
    'taxonomy'         => 'college',
    'value_field'      => 'term_id',
    'hierarchical'     => true,
    'hide_empty'       => false,
    'option_none_value' => '',
    'selected'         => $college,
    'parent'           => 0,
);
$select_college = wp_dropdown_categories($college_args);

// 2. Degree Dropdown (Child Terms of Selected College)
$select_deg = '<select name="degree" id="degree-select" class="">';
$select_deg .= '<option value="">' . __('Select Degree') . '</option>';

if (!empty($college)) {
    $child_terms = get_terms([
        'taxonomy'   => 'college',
        'parent'     => $college,
        'hide_empty' => false,
    ]);

    if (!empty($child_terms) && !is_wp_error($child_terms)) {
        foreach ($child_terms as $term) {
            $selected = ($degree == $term->term_id) ? ' selected="selected"' : '';
            $select_deg .= '<option value="' . esc_attr($term->term_id) . '"' . $selected . '>' . esc_html($term->name) . '</option>';
        }
    }
}
$select_deg .= '</select>';

// 3. Build Tax Query - Modified to include both parent and child when both selected
$tax_query = array();

if (!empty($college) && !empty($degree)) {
    // When both college and degree are selected, include both in the query
    $tax_query[] = array(
        'taxonomy' => 'college',
        'field'    => 'term_id',
        'terms'    => array($college, $degree),
        'operator' => 'IN'
    );
} elseif (!empty($degree)) {
    // When only degree is selected
    $tax_query[] = array(
        'taxonomy' => 'college',
        'field'    => 'term_id',
        'terms'    => $degree,
    );
} elseif (!empty($college)) {
    // When only college is selected, include all its children
    $children = get_term_children($college, 'college');
    $terms_to_include = array_merge(array($college), $children);
    
    $tax_query[] = array(
        'taxonomy' => 'college',
        'field'    => 'term_id',
        'terms'    => $terms_to_include,
        'operator' => 'IN'
    );
}

// 4. Main Programme Query
$args = array(
    'post_type' => 'programme',
    'paged' => $paged,
    'posts_per_page' => 5,
);

if (!empty($tax_query)) {
    $args['tax_query'] = $tax_query;
}

$programmes = new WP_Query($args);
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
<!-- Filter Section -->
<section class="filterBlock">
    <div class="container">
        <form method="get" action="<?php the_permalink(); ?>">
            <input type="hidden" name="paged" value="1">
            <div class="filterArea">
                <h4><?php _e('Filter Programs','sriher'); ?></h4>
                <?php echo $select_college; ?>
                <?php echo $select_deg; ?>
                <button type="submit" class="filter-button" >Search</button>
            </div>
        </form>
    </div>
</section>

<!-- Programme List Section -->
<section class="programmeListBlock">
    <div class="container">
        <div id="programmes">
            <?php if($programmes->have_posts()) : 
                while($programmes->have_posts()) : $programmes->the_post(); 
                $current_post_id = get_the_ID(); ?>
                <div class="programmeList">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <!-- Your programme content here -->
                    <?php if ( have_rows('programme_details', $programmes->$pageID) ) : ?>
            <ul>
            <?php while ( have_rows('programme_details', $programmes->$pageID) ) : the_row(); ?>
                <li>
                    <?php echo get_sub_field("title", $programmes->$pageID); ?>
                    <span><?php echo get_sub_field("details", $programmes->$pageID); ?></span>
                </li>
                <?php endwhile; ?>
            </ul>
            <?php endif; ?>
            <a href="<?php the_permalink($programmes->pageID); ?>">View more</a>
            <?php $more_details = get_field('eligibility_criteria', $programmes->$pageID);
            if ($more_details) { ?>
                <div class="moreDetails">
                    <?php echo get_field('eligibility_criteria', $programmes->$pageID); ?>
                </div>
            <?php } ?>
                </div>
                <?php endwhile; ?>
                
                <?php if($programmes->max_num_pages > 1): ?>
                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'base'    => add_query_arg(array('paged' => '%#%', 'college' => $college, 'degree' => $degree)),
                        'current' => $paged,
                        'total'   => $programmes->max_num_pages,
                    ));
                    ?>
                </div>
                <?php endif; ?>
                
                <?php wp_reset_postdata(); ?>
            <?php else: ?>
                <div class="no-results">
                    <?php if(!empty($college) && !empty($degree)): ?>
                        <p>No programs found for both <strong><?php echo get_term($college, 'college')->name; ?></strong> and <strong><?php echo get_term($degree, 'college')->name; ?></strong></p>
                    <?php elseif(!empty($college)): ?>
                        <p>No programs found for <strong><?php echo get_term($college, 'college')->name; ?></strong></p>
                    <?php elseif(!empty($degree)): ?>
                        <p>No programs found for <strong><?php echo get_term($degree, 'college')->name; ?></strong></p>
                    <?php else: ?>
                        <p>No programs to display. Please select a filter.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>





<?php get_footer();