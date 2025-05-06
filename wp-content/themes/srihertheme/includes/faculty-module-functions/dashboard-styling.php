<?php

/***Disabling personal info,image,bio */
function disable_personal_options()
{
    echo '<style>

        .user-rich-editing-wrap,
        .user-syntax-highlighting-wrap,
        .user-language-wrap,
        .user-url-wrap,
        .user-description-wrap,
        .user-profile-picture,
        .user-admin-color-wrap,
        .user-comment-shortcuts-wrap,
        .show-admin-bar{
            display: none !important;
        }

        #your-profile h2{
            display: none !important;
        }

    </style>';
}
 add_action('admin_head-user-edit.php', 'disable_personal_options');
 add_action('admin_head-profile.php', 'disable_personal_options');


/************************************** */

/**Disabling color theme ******************** */

function remove_admin_color_scheme_options()
{
    global $_wp_admin_css_colors;

    if (isset($_wp_admin_css_colors['coffee'])) {
        unset($_wp_admin_css_colors['coffee']);
    }
    if (isset($_wp_admin_css_colors['ectoplasm'])) {
        unset($_wp_admin_css_colors['ectoplasm']);
    }
    if (isset($_wp_admin_css_colors['ocean'])) {
        unset($_wp_admin_css_colors['ocean']);
    }
    if (isset($_wp_admin_css_colors['midnight'])) {
        unset($_wp_admin_css_colors['midnight']);
    }
    if (isset($_wp_admin_css_colors['sunrise'])) {
        unset($_wp_admin_css_colors['sunrise']);
    }
    if (isset($_wp_admin_css_colors['modern'])) {
        unset($_wp_admin_css_colors['modern']);
    }
    if (isset($_wp_admin_css_colors['light'])) {
        unset($_wp_admin_css_colors['light']);
    }
    if (isset($_wp_admin_css_colors['blue'])) {
        unset($_wp_admin_css_colors['blue']);
    }
}
add_action('admin_init', 'remove_admin_color_scheme_options');

/************************************** **********************************/

/**Extra style addind ****** */

function extra_style_adding()
{
    echo '<style>

    #wpcontent{
        background: white !important;
    }

    .application-passwords.hide-if-no-js{
        display:none;
    }
        </style>';
}

add_action('admin_head-user-edit.php', 'extra_style_adding');
add_action('admin_head-profile.php', 'extra_style_adding');


?>