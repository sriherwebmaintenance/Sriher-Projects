<?php
/*
 * Plugin Name:		Profile & Dashboard fields [Modify/Disable/Remove]
 * Description:		Prevent users from modifying specific profile & dashboard fields.
 * Text Domain:		modify-profile-dashboard-fields
 * Domain Path:		/languages
 * Version:		1.07
 * WordPress URI:	https://wordpress.org/plugins/modify-profile-dashboard-fields/
 * Plugin URI:		https://puvox.software/software/wordpress-plugins/?plugin=modify-profile-dashboard-fields
 * Contributors: 	puvoxsoftware,ttodua
 * Author:		Puvox.software
 * Author URI:		https://puvox.software/
 * Donate Link:		https://paypal.me/Puvox
 * License:		GPL-3.0
 * License URI:		https://www.gnu.org/licenses/gpl-3.0.html
 
 * @copyright:		Puvox.software
*/


namespace ModifyProfileDashboardFields
{
	if (!defined('ABSPATH')) exit;
	require_once( __DIR__."/library.php" );
	require_once( __DIR__."/library_wp.php" );
	
	class PluginClass extends \Puvox\wp_plugin
	{
  
	  public function declare_settings()
	  {
		  $this->initial_static_options	= 
		  [
			  'has_pro_version'        => 0, 
			  'show_opts'              => true, 
			  'show_rating_message'    => true, 
			  'show_donation_footer'   => true, 
			  'show_donation_popup'    => true, 
			  'menu_pages'             => [
				  'first' =>[
					  'title'           => 'Modify Profile Fields', 
					  'default_managed' => 'network',            // network | singlesite
					  'required_role'   => 'install_plugins',
					  'level'           => 'submenu', 
					  'page_title'      => 'Profile & Dashboard fields [Modify/Disable/Remove]G',
					  'tabs'            => [],
				  ],
			  ]
		  ];
		
		$array = 
		[
			'dash_welcome_page'	=>true, 
			'helptab_toolbar'	=>true, 
			'custom_note'	 	=>"Dear User, whatever options you see on this page, only those fields are allowed for modification in profile.", 
			'custom_note_css'	=>"color: red; font-size: 1.4em; background-color: #e1e1e1; padding: 30px; margin: 30px;", 
			'disable_headline_fields'=>false, 
			'disabled_headline_fields'=>"Personal Options,Name,Contact Info,About Yourself,Account Management", 
		];
			foreach ($this->fields_control as $key=>$val) 
		$array["enable_".$key] = true;
		
		$this->initial_user_options	= $array;
	} 
	
	public $fields_control = ['admin_color_scheme'=>'Admin Color Scheme', 'toolbar'=>'Toolbar', 'username'=>'Username', 'first_name'=>'First Name', 'last_name'=>'Last Name', 'nickname'=>'Nickname', 'display_name'=>'Display Name', 'email'=>'Email', 'url'=>'Website', 'description'=>'Biographical Info', 'profile_picture'=>'Profile Picture', 'password'=>'Password'  ];
	 
	public function __construct_my()
	{
		add_action('admin_init', [$this, 'enable_disable_other_things'] );
		
		foreach ($this->fields_control as $key=>$val) 
			$this->enable_disable_control($key);
	}


	private function skipDisabling(){
		return current_user_can('create_users');
	}

	#region
	// Visual: wp-admin/user-edit.php
	public function enable_disable_control($what)
	{
	  // edit_user_profile, show_user_profile, admin_footer
	  $styleHook = 'show_user_profile';

	  if (!$this->opts['enable_'.$what])
	  {
		if ($what =='admin_color_scheme')
		{
			add_action( $styleHook,						[$this, 'admin_color_change_HTML'] );
			add_action( 'user_profile_update_errors',  	[$this, 'admin_color_change_BACKEND'],	10, 3);
		}
		else if ($what =='toolbar')
		{
			add_action( $styleHook,						[$this, 'toolbar_change_HTML'] );
			add_action( 'user_profile_update_errors',  	[$this, 'toolbar_change_backend'],		10, 3);
		}
		else if ($what =='username')
		{
			add_action( $styleHook,						[$this, 'username_change_HTML'] );
			add_action( 'user_profile_update_errors',	[$this, 'username_change_backend'],		10, 3);
		}
		else if ($what =='first_name')
		{
			add_action( $styleHook,						[$this, 'first_name_change_HTML'] );
			add_action( 'user_profile_update_errors',  	[$this, 'first_name_change_backend'],	10, 3);
		}
		else if ($what =='last_name')
		{
			add_action( $styleHook,						[$this, 'last_name_change_HTML'] );
			add_action( 'user_profile_update_errors',  	[$this, 'last_name_change_backend'],	10, 3);
		}
		else if ($what =='nickname')
		{
			add_action( $styleHook,						[$this, 'nickname_change_HTML'] );
			add_action( 'user_profile_update_errors',  	[$this, 'nickname_change_backend'],		10, 3);
		}
		else if ($what =='display_name')
		{
			add_action( $styleHook,						[$this, 'display_name_change_HTML'] );
			add_action( 'user_profile_update_errors',  	[$this, 'display_name_change_backend'],	10, 3);
		}
		else if ($what =='url')
		{
			add_action( $styleHook,  					[$this, 'url_change_HTML']  ); 
			add_action( 'user_profile_update_errors',  	[$this, 'url_change_BACKEND'],			10, 3);
		}
		else if ($what =='description')
		{
			add_action( $styleHook,  					[$this, 'description_change_HTML']  ); 
			add_action( 'user_profile_update_errors',  	[$this, 'description_change_BACKEND'],	10, 3);
		}
		else if ($what =='profile_picture')
		{
			add_action( $styleHook,  					[$this, 'profile_picture_change_HTML']  ); 
			add_action( 'user_profile_update_errors',  	[$this, 'profile_picture_change_BACKEND'], 10, 3);
		}

		// ### specific fields
		else if ($what =='email')
		{ 
			add_action( $styleHook,  					[$this, 'mail_change_HTML']  );
			// wp-admin/user-edit.php
			add_action( 'personal_options_update',		[$this, 'mail_change_BACKEND'], 5  );
		}
		else if ($what =='password')
		{
			add_action( $styleHook,  					[$this, 'password_change_HTML']  ); 	//add_filter( 'show_password_fields',  false ); // wp-admin/user-edit.php
			// wp-admin/includes/user.php 
			add_action( 'check_passwords',  			[$this, 'password_change_BACKEND'], 10, 3);
		}
	  }

 
	  if(!empty($this->opts['custom_note']))
	  {
		add_action($styleHook, 		[$this, 'show_extra_message'], 99 );
	  }
	  if(!empty($this->opts['disable_headline_fields']))
	  {
		add_action($styleHook, 		[$this, 'disable_headlines'], 99 );
	  }
	}


	// admin_color_scheme
	public function admin_color_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-admin-color-wrap{display:none;}</style>';
	}
	public function admin_color_change_BACKEND(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->admin_color = get_user_meta($user->ID, 'admin_color', true );
	}

	// toolbar
	public function toolbar_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-admin-bar-front-wrap{display:none;}</style>';
	}
	public function toolbar_change_backend(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->show_admin_bar_front = get_user_meta($user->ID, 'show_admin_bar_front', true );
	}

	// Username
	public function username_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-user-login-wrap{display:none;} </style>';
	}
	public function username_change_backend(&$errors, $update, &$user) { 
		//nothing needed, WP does itself
	}
	
	// First Name
	public function first_name_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-first-name-wrap{display:none;} </style>';
	}
	public function first_name_change_backend(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->first_name = get_user_meta($user->ID, 'first_name', true );
	}
	
	// Last Name
	public function last_name_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-last-name-wrap{display:none;} </style>';
	}
	public function last_name_change_backend(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->last_name = get_user_meta($user->ID, 'last_name', true );
	}
	
	// Nickname
	public function nickname_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-nickname-wrap{display:none;} </style>';
	}
	public function nickname_change_backend(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->nickname = get_user_meta($user->ID, 'nickname', true );
	}

	// Display Name
	public function display_name_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-display-name-wrap{display:none;} </style>';
	}
	public function display_name_change_backend(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->display_name = get_userdata($user->ID)->display_name;
	}

	// Url
	public function url_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-url-wrap{display:none;} </style>';
	}
	public function url_change_backend(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->user_url = get_userdata($user->ID)->user_url;
	}

	// Description
	public function description_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-description-wrap{display:none;}</style>';
	}
	public function description_change_backend(&$errors, $update, &$user) {
		if ( $this->skipDisabling() ) return; 
		if ($update) 
			$user->user_url = get_user_meta($user->ID, 'description', true );
	}
	
	// Profile Picture
	public function profile_picture_change_HTML() {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-profile-picture{display:none;}</style>';
	}
	public function profile_picture_change_backend(&$errors, $update, &$user) {
		return; //nothing needed, WP does itself
	}



	// ### Specific sensitive methods

	// email
	public function mail_change_HTML($user) {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-email-wrap{display:none;} </style>'; 
	}
	public function mail_change_BACKEND($user_id) {
		if ( $this->skipDisabling() ) return;
		//the correct place to avoid side-effects
		$user = get_user_by('id', $user_id ); $_POST['email']=$user->user_email; 
	}
	//add_action( 'admin_init', function(){   remove_action( 'personal_options_update', 'send_confirmation_on_profile_email' ); });
	//add_action( 'user_profile_update_errors', 	function ( $username ) {	$old = get_user_by('login', $username ); if( $user->user_email != $old->user_email   && (!current_user_can('create_users')) )		$user->user_email = $old->user_email; }, 10, 3 );

	
	// password
	public function password_change_HTML($user) {
		if ( $this->skipDisabling() ) return;
		echo '<style>#profile-page .user-pass1-wrap{display:none;}</style>';
	}
	public function password_change_BACKEND($username, &$pass1, &$pass2){ 
		if ( $this->skipDisabling() ) return;
		//the correct place to avoid side-effects
		$pass1=""; $pass2="";
	}
	#endregion

	// ============================================================================================================== //
	// ============================================================================================================== //
 
	
	
	public function enable_disable_other_things()
	{
		if ( $this->skipDisabling() ) return; 
		
		if (!$this->opts['dash_welcome_page'])
		{
			if ( $GLOBALS['pagenow']=='index.php' ) { 
				wp_redirect( admin_url('profile.php') ); exit; 
			}
			add_action( 'admin_head',   function () {
				foreach ( $GLOBALS['menu'] as $index=>$block ) { 
					if ( ( 'index.php' == $block[2] ) ) remove_menu_page( $block[2] ); 
				} 
			} );
			 
		}
		
		if (!$this->opts['helptab_toolbar'])
		{
			add_action('admin_head', function () { get_current_screen()->remove_help_tabs(); } );
			//if (class_exists('\WC_Admin_Help')) { remove_action( 'current_screen', ["WC_Admin_Help", 'add_tabs'], 50 ); 
		}
		
	}
	
	// some ways too: remove-dashboard-access-for-non-admins. 
	//add_action( 'admin_head',   function () { global $menu; $menu_ids = array(); foreach ( $menu as $index => $values ) { if ( isset( $values[2] ) && ( 'profile.php' != $values[2] ) ) remove_menu_page( $values[2] ); } } );



	public function show_extra_message()
	{
		if ( $this->skipDisabling() ) return; 
		?>
		<script>
		var content='<tr class="user-custommessage-wrap" id="custommessage_added_div"> <td colspan=2> <div class="custommessage"><?php echo htmlentities($this->opts["custom_note"]);?></div>			<style>.custommessage{<?php echo sanitize_text_field($this->opts["custom_note_css"]);?>}</style></td> </tr>';
		document.getElementById("your-profile").insertAdjacentHTML("afterbegin", content);
		</script>
		<?php
	}
	
	public function disable_headlines()
	{
		if ( $this->skipDisabling() ) return;  
		?>
		<script>
		(function(){
			var titles= <?php echo json_encode( array_filter( explode( ',', $this->opts["disabled_headline_fields"]) ) );?>;
			var elems= document.getElementById("profile-page").getElementsByTagName("h2");
			while(elems.length > 0) elems[0].remove();
		}());
		</script>
		<?php
	}



	// //echo '<script>document.getElementById("email").setAttribute("disabled","disabled");</script>'; 
	// =================================== Options page ================================ //
	public function opts_page_output()
	{ 
		$this->settings_page_part("start", 'first'); 
		?>

		<style>  
		.mainForm th{width:40%; min-width:200px; }
		</style>

		<?php if ($this->active_tab=="Options") 
		{ 
			//if form updated
			if( $this->checkSubmission() ) 
			{ 
				$this->opts["dash_welcome_page"]= !empty($_POST[ $this->plugin_slug ]["dash_welcome_page"]);  
				$this->opts["helptab_toolbar"]	= !empty($_POST[ $this->plugin_slug ]["helptab_toolbar"]);  
				$this->opts["custom_note"]		= str_replace(["\r\n","\n"], '<br/>', strip_tags(sanitize_text_field($_POST[ $this->plugin_slug ]["custom_note"])) ); 
				$this->opts["custom_note_css"]	= strip_tags(sanitize_text_field($_POST[ $this->plugin_slug ]["custom_note_css"]));  
				$this->opts["disable_headline_fields"]	= !empty($_POST[ $this->plugin_slug ]["disable_headline_fields"]);  
				$this->opts["disabled_headline_fields"]	= strip_tags(sanitize_text_field($_POST[ $this->plugin_slug ]["disabled_headline_fields"]));
					foreach ($this->fields_control as $key=>$val)
				$this->opts["enable_".$key]		= !empty($_POST[ $this->plugin_slug ]["enable_".$key]); 

				$this->update_opts();
			}
			?>
			
			<form class="mainForm" method="post" action="">
			<b><i><?php _e("Note, these settings doesn't affect users with privilegge of <code>create_users</code>, as they will still be able to see these fields in their/others profiles.");?></i></b>
			
			<table class="form-table">
			
				<?php foreach ($this->fields_control as $key=>$val) { ?>
				<tr class="def">
					<th scope="row">
						<?php _e("Display field: <code>$val</code>", 'modify-profile-dashboard-fields'); ?>
					</th>
					<td>
						<fieldset>
							<div class="">
								<label>
									<input name="<?php echo $this->plugin_slug;?>[enable_<?php echo $key;?>]" type="radio" value="0" <?php checked(!$this->opts['enable_'.$key]); ?>><?php _e( 'No', 'modify-profile-dashboard-fields' );?>
								</label>
								<label>
									<input name="<?php echo $this->plugin_slug;?>[enable_<?php echo $key;?>]" type="radio" value="1" <?php checked($this->opts['enable_'.$key]); ?>><?php _e( 'Yes', 'modify-profile-dashboard-fields' );?>
								</label>
							</div>
						</fieldset>
					</td>
				</tr>
				<?php } ?> 
				 
				<tr class="def">
					<th scope="row">
						<?php _e("Disable headline titles", 'modify-profile-dashboard-fields'); ?> <input name="<?php echo $this->plugin_slug;?>[disable_headline_fields]" type="checkbox" value="1" <?php checked($this->opts['disable_headline_fields']); ?>>
					</th>
					<td>
						<fieldset>
							<div class="">
							 <?php _e("List of the headlines", 'modify-profile-dashboard-fields');?>
							<input name="<?php echo $this->plugin_slug;?>[disabled_headline_fields]" type="text" class="large-text" value="<?php echo sanitize_text_field($this->opts['disabled_headline_fields']); ?>"  />
							
							</div>
						</fieldset>
					</td>
				</tr>
				 
				<tr class="def">
					<th scope="row">
						<?php _e("Add custom message/note to users profile page", 'modify-profile-dashboard-fields'); ?>
					</th>
					<td>
						<fieldset>
							<div class="">
								<input name="<?php echo $this->plugin_slug;?>[custom_note]" type="text" class="large-text" value="<?php echo sanitize_text_field($this->opts['custom_note']); ?>"  />
								<br/>
								<input name="<?php echo $this->plugin_slug;?>[custom_note_css]" type="text" class="large-text" value="<?php echo sanitize_text_field($this->opts['custom_note_css']); ?>"  />
							</div>
						</fieldset>
					</td>
				</tr>
				
				
				<tr class="def">
					<th scope="row">
						<h2><?php _e("Additional Settings", 'modify-profile-dashboard-fields'); ?></h2>
					</th>
					<td> 
					</td>
				</tr>
 
				<tr class="def">
					<th scope="row">
						<?php _e("Enable Dashboard Welcome page", 'modify-profile-dashboard-fields'); ?>
					</th>
					<td>
						<fieldset>
							<div class="">
								<label>
									<input name="<?php echo $this->plugin_slug;?>[dash_welcome_page]" type="radio" value="0" <?php checked(!$this->opts['dash_welcome_page']); ?>><?php _e( 'No', 'modify-profile-dashboard-fields' );?>
								</label>
								<label>
									<input name="<?php echo $this->plugin_slug;?>[dash_welcome_page]" type="radio" value="1" <?php checked($this->opts['dash_welcome_page']); ?>><?php _e( 'Yes', 'modify-profile-dashboard-fields' );?>
								</label>
							</div>
						</fieldset>
					</td>
				</tr>
				
				<tr class="def">
					<th scope="row">
						<?php _e("Enable Help-Tab across dashboard pages", 'modify-profile-dashboard-fields'); ?>
					</th>
					<td>
						<fieldset>
							<div class="">
								<label>
									<input name="<?php echo $this->plugin_slug;?>[helptab_toolbar]" type="radio" value="0" <?php checked(!$this->opts['helptab_toolbar']); ?>><?php _e( 'No', 'modify-profile-dashboard-fields' );?>
								</label>
								<label>
									<input name="<?php echo $this->plugin_slug;?>[helptab_toolbar]" type="radio" value="1" <?php checked($this->opts['helptab_toolbar']); ?>><?php _e( 'Yes', 'modify-profile-dashboard-fields' );?>
								</label>
							</div>
						</fieldset>
					</td>
				</tr>
				
			</table>
			
			<?php $this->nonceSubmit(); ?>

			</form>
		<?php 
		}
		

		$this->settings_page_part("end", '');
	} 



  } // End Of Class

  $GLOBALS[__NAMESPACE__] = new PluginClass();

} // End Of NameSpace

?>