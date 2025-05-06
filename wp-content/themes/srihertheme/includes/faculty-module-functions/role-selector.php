<?php

// Add a user role dropdown to the registration form
function add_user_role_chooser_to_registration_form() {
    $allowed_roles = array('faculty','principal','hod'); // Specify the roles you want to show

    $roles = wp_roles();
    ?>
    <p>
        <label for="user_role">User Role:</label>
        <select name="user_role" id="user_role">
            <?php
            foreach ($roles->role_names as $role => $label) {
                if (in_array($role, $allowed_roles)) {
                    echo '<option value="' . $role . '">' . $label . '</option>';
                }
            }
            ?>
        </select>
    </p>
    <?php
}

add_action('register_form', 'add_user_role_chooser_to_registration_form');

// Process the user role data when a user registers
function process_user_role_registration($user_id) {
    if (isset($_POST['user_role'])) {
        $user = new WP_User($user_id);
        $selected_role = sanitize_text_field($_POST['user_role']);

        // Check if the selected role is in the allowed roles array
        if (in_array($selected_role, array('hod', 'faculty','principal'))) {
            $user->set_role($selected_role);
        } else {
            // You can handle the case where an invalid role is selected (optional).
            // For example, you can set a default role or display an error message.
        }
    }
}

add_action('user_register', 'process_user_role_registration');


?>