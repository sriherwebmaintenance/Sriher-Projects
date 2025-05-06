<?php

function is_user_profile_page() {
    global $pagenow;
    return (is_admin() && $pagenow === 'profile.php');
}