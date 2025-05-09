<?php
/**
 * Class to work with user capability
 *
 * @package    User-Role-Editor
 * @subpackage Admin
 * @author     Vladimir Garagulya <support@role-editor.com>
 * @copyright  Copyright (c) 2010 - 2021, Vladimir Garagulya
 **/

class URE_Capability {
    
    public static function escape( $cap_id ) {
        
        $search = array(' ', '/', '|', '{', '}', '$');
        $replace = array('_', '_', '_', '_', '_', '_');
        
        $cap_id_esc = str_replace( $search, $replace, $cap_id );
                
        return $cap_id_esc;
    }
    // end escape()

    
    // Sanitize user input for security
    // do not allow to use internally used capabilities
    public static function validate( $cap_id_raw ) {
        $match = array();
        $found = preg_match('/[A-Za-z0-9_\-]*/', $cap_id_raw, $match );
        if ( !$found || ($found && ( $match[0]!=$cap_id_raw ) ) ) { // some non-alphanumeric charactes found!    
            $data = array(
                'result'=>false, 
                'message'=>esc_html__('Error: Capability name must contain latin characters and digits only!', 'user-role-editor'),
                'cap_id'=>''
                );
            return $data;
        } 
        
        $cap_id = strtolower( $match[0] );
        if ( $cap_id=='do_not_allow' ) {
            $data = array(
                'result'=>false, 
                'message'=>esc_html__('Error: this capability is used internally by WordPress', 'user-role-editor'),
                'cap_id'=>'do_not_allow'
                );
            return $data;
        }
        if ( $cap_id=='administrator' ) {
            $data = array(
                'result'=>false, 
                'message'=>esc_html__('Error: this word is used by WordPress as a role ID', 'user-role-editor'),
                'cap_id'=>'administrator'
                );
            return $data;
        }
        
        $data = array(
            'result'=>true, 
            'message'=>'Success',
            'cap_id'=>$cap_id
            );
        
        return $data;
    }
    // end of validate()
    
    
    /**
     * Add new user capability
     * 
     * @global WP_Roles $wp_roles
     * @return string
     */
    public static function add( $ure_object ) {
        global $wp_roles;

        $response = array(
            'result'=>'error', 
            'capability_id'=>'', 
            'html'=>'', 
            'message'=>''
            );
        if ( !current_user_can( 'ure_create_capabilities' ) ) {
            $response['message'] = esc_html__( 'Insufficient permissions to work with User Role Editor', 'user-role-editor' );
            return $response;
        }
        
        $mess = '';
        if ( !isset( $_POST['capability_id'] ) || empty( $_POST['capability_id'] ) ) {
            $response['message'] = esc_html__( 'Wrong Request', 'user-role-editor' );
            return $response;
        }
        
        $data = self::validate( $_POST['capability_id'] );
        if ( !$data['result'] ) {
            $response['message'] = $data['message'];
            return $response;
        }
        
        $cap_id = $data['cap_id'];                
        $lib = URE_Lib::get_instance();
        $full_capabilities = $lib->init_full_capabilities( $ure_object );
        if ( !isset( $full_capabilities[$cap_id] ) ) {
            $admin_role = $lib->get_admin_role();
            $use_db = $wp_roles->use_db;
            $wp_roles->use_db = true;
            $wp_roles->add_cap( $admin_role, $cap_id );
            $wp_roles->use_db = $use_db;
            $response['result'] = 'success';
            // translators: placeholder %s is replaced by added user capability id string value
            $response['message'] = sprintf( esc_html__( 'Capability %s was added successfully', 'user-role-editor' ), $cap_id );
        } else {
            // translators: placeholder %s is replaced by existed user capability id string value
            $response['message']  = sprintf( esc_html__( 'Capability %s exists already', 'user-role-editor' ), $cap_id );
        }
        
        return $response;
    }
    // end of add()
    
    
    /**
     * Extract capabilities selected for deletion from the $_POST global
     * 
     * @return array
     */
    private static function get_caps_for_deletion_from_post( $caps_allowed_to_remove ) {
        
        if ( isset( $_POST['values'] ) ) {
            $input_buff = $_POST['values'];
        } else {
            $input_buff = $_POST;
        }
        
        $caps = array();
        foreach( $input_buff as $key=>$value ) {
            if ( substr( $key, 0, 3 )!=='rm_' ) {
                continue;
            }
            if ( !isset( $caps_allowed_to_remove[$value])  ) {
                continue;
            }
            $caps[] = $value;
        }
        
        return $caps;
    }
    // end of get_caps_for_deletion_from_post()
            
        
    private static function revoke_caps_from_user( $user_id, $caps ) {
        
        $user = get_user_to_edit( $user_id );
        foreach( $caps as $cap_id ) {
            if ( !isset( $user->caps[$cap_id] ) ) {
                continue;
            }
            // Prevent sudden revoke role 'administrator' from a user during 'administrator' capability deletion.
            if ( $cap_id=='administrator') { 
                continue;
            }
            $user->remove_cap( $cap_id );            
        }
    }
    // end of revoke_caps_from_user()
    
    
    private static function revoke_caps_from_role( $wp_role, $caps ) {
        
        foreach( $caps as $cap_id ) {
            if ( $wp_role->has_cap( $cap_id ) ) {
                $wp_role->remove_cap( $cap_id );
            }
        }
        
    }
    // end of revoke_caps_from_role()
    
    
    private static function revoke_caps( $caps ) {
        global $wpdb, $wp_roles;
        
        // remove caps from users
        $users_ids = $wpdb->get_col("SELECT $wpdb->users.ID FROM $wpdb->users");
        foreach ( $users_ids as $user_id ) {
            self::revoke_caps_from_user( $user_id, $caps );
        }

        // remove caps from roles
        foreach ( $wp_roles->role_objects as $wp_role ) {
            self::revoke_caps_from_role( $wp_role, $caps );
        }        
    }
    // end of revoke_caps()
    
            
    /**
     * Delete capability
     * 
     * @global WP_Roles $wp_roles
     * @return string - information message
     */
    public static function delete() {        
               
        if ( !current_user_can( 'ure_delete_capabilities' ) ) {
            return esc_html__( 'Insufficient permissions to work with User Role Editor','user-role-editor' );
        }
                        
        $capabilities = URE_Capabilities::get_instance();
        $mess = '';                
        $caps_allowed_to_remove = $capabilities->get_caps_to_remove();
        if ( !is_array( $caps_allowed_to_remove ) || count( $caps_allowed_to_remove )==0 ) {
            return esc_html__( 'There are no capabilities available for deletion!', 'user-role-editor' );
        }
        
        $caps = self::get_caps_for_deletion_from_post( $caps_allowed_to_remove );
        if ( empty( $caps ) ) {
            return esc_html__( 'There are no capabilities available for deletion!', 'user-role-editor' );
        }

        self::revoke_caps( $caps );
        
        if ( count( $caps )==1 ) {
            // translators: placeholder %s is replaced by removed user capability id string value
            $mess = sprintf( esc_html__( 'Capability %s was removed successfully', 'user-role-editor' ), $caps[0] );
        } else {
            $lib = URE_Lib::get_instance();
            $short_list_str = $lib->get_short_list_str( $caps );
            $mess = count( $caps ) .' '. esc_html__( 'capabilities were removed successfully', 'user-role-editor' ) .': '. 
                    $short_list_str;
        }

        // Escape every capability ID to remove from the HTML markup related div by ID
        $esc_caps = array();
        foreach( $caps as $key=>$cap ) {
            $esc_caps[$key] = self::escape( $cap );
        }
        return array('message'=>$mess, 'deleted_caps'=>$esc_caps);
    }
    // end of delete()
    
}
// end of class URE_Capability
