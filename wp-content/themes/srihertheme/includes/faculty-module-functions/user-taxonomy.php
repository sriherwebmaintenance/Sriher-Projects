<?php
// Register Custom Taxonomy
function custom_taxonomy_registration()
{

  // Register Designation Taxonomy
  $labels = array(
    'name'                       => _x('Designations', 'Designations Name', 'text_domain'),
    'singular_name'              => _x('Designation', 'Designation Name', 'text_domain'),
    'menu_name'                  => __('Designations', 'text_domain'),
    'all_items'                  => __('All Designations', 'text_domain'),
    'parent_item'                => __('Parent Designation', 'text_domain'),
    'parent_item_colon'          => __('Parent Designation:', 'text_domain'),
    'new_item_name'              => __('New Designation Name', 'text_domain'),
    'add_new_item'               => __('Add Designation', 'text_domain'),
    'edit_item'                  => __('Edit Designation', 'text_domain'),
    'update_item'                => __('Update Designation', 'text_domain'),
    'view_item'                  => __('View Designation', 'text_domain'),
    'separate_items_with_commas' => __('Separate designation with commas', 'text_domain'),
    'add_or_remove_items'        => __('Add or remove designations', 'text_domain'),
    'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
    'popular_items'              => __('Popular Designations', 'text_domain'),
    'search_items'               => __('Search Designations', 'text_domain'),
    'not_found'                  => __('Not Found', 'text_domain'),
    'no_terms'                   => __('No designations', 'text_domain'),
    'items_list'                 => __('Designations list', 'text_domain'),
    'items_list_navigation'      => __('Designations list navigation', 'text_domain'),
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy('designations', 'user', $args);


  // Register Qualification Taxonomy
  $labels = array(
    'name'                       => _x('Qualifications', 'Qualifications Name', 'text_domain'),
    'singular_name'              => _x('Qualification', 'Qualification Name', 'text_domain'),
    'menu_name'                  => __('Qualifications', 'text_domain'),
    'all_items'                  => __('All Qualifications', 'text_domain'),
    'parent_item'                => __('Parent Qualification', 'text_domain'),
    'parent_item_colon'          => __('Parent Qualification:', 'text_domain'),
    'new_item_name'              => __('New Qualification Name', 'text_domain'),
    'add_new_item'               => __('Add Qualification', 'text_domain'),
    'edit_item'                  => __('Edit Qualification', 'text_domain'),
    'update_item'                => __('Update Qualification', 'text_domain'),
    'view_item'                  => __('View Qualification', 'text_domain'),
    'separate_items_with_commas' => __('Separate qualification with commas', 'text_domain'),
    'add_or_remove_items'        => __('Add or remove qualifications', 'text_domain'),
    'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
    'popular_items'              => __('Popular Qualifications', 'text_domain'),
    'search_items'               => __('Search Qualifications', 'text_domain'),
    'not_found'                  => __('Not Found', 'text_domain'),
    'no_terms'                   => __('No qualifications', 'text_domain'),
    'items_list'                 => __('Qualifications list', 'text_domain'),
    'items_list_navigation'      => __('Qualifications list navigation', 'text_domain'),
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy('qualifications', 'user', $args);

  // Register Research Taxonomy
  $labels = array(
    'name'                       => _x('Research', 'Qualifications Name', 'text_domain'),
    'singular_name'              => _x('Research', 'Research Name', 'text_domain'),
    'menu_name'                  => __('Research Interest', 'text_domain'),
    'all_items'                  => __('All Researchs', 'text_domain'),
    'parent_item'                => __('Parent Research', 'text_domain'),
    'parent_item_colon'          => __('Parent Research:', 'text_domain'),
    'new_item_name'              => __('New Research Name', 'text_domain'),
    'add_new_item'               => __('Add Research', 'text_domain'),
    'edit_item'                  => __('Edit Research', 'text_domain'),
    'update_item'                => __('Update Research', 'text_domain'),
    'view_item'                  => __('View Research', 'text_domain'),
    'separate_items_with_commas' => __('Separate research with commas', 'text_domain'),
    'add_or_remove_items'        => __('Add or remove research', 'text_domain'),
    'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
    'popular_items'              => __('Popular Research', 'text_domain'),
    'search_items'               => __('Search Research', 'text_domain'),
    'not_found'                  => __('Not Found', 'text_domain'),
    'no_terms'                   => __('No research', 'text_domain'),
    'items_list'                 => __('Research list', 'text_domain'),
    'items_list_navigation'      => __('Research list navigation', 'text_domain'),
  );
  $args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
  );
  register_taxonomy('research', 'user', $args);

}
add_action('init', 'custom_taxonomy_registration', 0);



/**
 * Admin page for the taxonomy
 */
function cb_add_taxonomy_admin_page()
{

  // Designation
  $tax = get_taxonomy('designations');

  add_users_page(
    esc_attr($tax->labels->menu_name),
    esc_attr($tax->labels->menu_name),
    $tax->cap->manage_terms,
    'edit-tags.php?taxonomy=' . $tax->name
  );

  // Qualification
  $tax = get_taxonomy('qualifications');

  add_users_page(
    esc_attr($tax->labels->menu_name),
    esc_attr($tax->labels->menu_name),
    $tax->cap->manage_terms,
    'edit-tags.php?taxonomy=' . $tax->name
  );

  // Research
  $tax = get_taxonomy('research');

  add_users_page(
    esc_attr($tax->labels->menu_name),
    esc_attr($tax->labels->menu_name),
    $tax->cap->manage_terms,
    'edit-tags.php?taxonomy=' . $tax->name
  );
}
add_action('admin_menu', 'cb_add_taxonomy_admin_page');


/**
 * Unsets the 'posts' column and adds a 'users' column on the manage designations admin page.
 */
function cb_manage_designations_user_column($columns)
{

  unset($columns['posts']);

  $columns['users'] = __('Users');

  return $columns;
}
add_filter('manage_edit-designations_columns', 'cb_manage_designations_user_column');


function cb_manage_qualifications_user_column($columns)
{

  unset($columns['posts']);

  $columns['users'] = __('Users');

  return $columns;
}
add_filter('manage_edit-qualifications_columns', 'cb_manage_qualifications_user_column');


/**
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $term_id The ID of the term being displayed in the table.
 */
function cb_manage_designations_column($display, $column, $term_id)
{

  if ('users' === $column) {
    $term = get_term($term_id, 'designations');
    echo $term->count;
  }
}
add_filter('manage_designations_custom_column', 'cb_manage_designations_column', 10, 3);


function cb_manage_qualifications_column($display, $column, $term_id)
{

  if ('users' === $column) {
    $term = get_term($term_id, 'qualifications');
    echo $term->count;
  }
}
add_filter('manage_qualifications_custom_column', 'cb_manage_qualifications_column', 10, 3);





//   /**
//  * @param int $user_id The ID of the user to save the terms for.
//  */
// function cb_save_user_designation_terms( $user_id ) {

//     $tax = get_taxonomy( 'designations' );

//     /* Make sure the current user can edit the user and assign terms before proceeding. */
//     if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
//       return false;

//     $term = $_POST['designations'];
//     $terms = is_array($term) ? $term : (int) $term; // fix for checkbox and select input field

//     /* Sets the terms (we're just using a single term) for the user. */
//     wp_set_object_terms( $user_id, $terms, 'designations', false);

//     clean_object_term_cache( $user_id, 'designations' );
//   }

//   add_action( 'personal_options_update', 'cb_save_user_designation_terms' );
//   add_action( 'edit_user_profile_update', 'cb_save_user_designation_terms' );
//   add_action( 'user_register', 'cb_save_user_designation_terms' );



/**
 * @param string $username The username of the user before registration is complete.
 */
function cb_disable_designations_username($username)
{

  if ('designations' === $username)
    $username = '';

  return $username;
}
add_filter('sanitize_user', 'cb_disable_designations_username');



/**
 * Update parent file name to fix the selected menu issue
 */
function cb_change_parentuser_file($parent_file)
{
  global $submenu_file;

  if (
    isset($_GET['taxonomy']) &&
    $_GET['taxonomy'] == 'designations' &&
    $submenu_file == 'edit-tags.php?taxonomy=designations'
  )
    $parent_file = 'users.php';

  return $parent_file;
}
add_filter('parent_file', 'cb_change_parentuser_file');
