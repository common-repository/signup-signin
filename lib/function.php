<?php

//Logout link for loggedin user
function pravel_login_link_menu($items, $args) {	
	if (is_user_logged_in()) 
	{
		$items .='<li class="menu-item menu-item-custom"><a class="login_button" href="'. wp_logout_url( home_url() ).'">Logout</a></li>';
	}
	
	return $items; 
}
add_filter('wp_nav_menu_items', 'pravel_login_link_menu', 10, 2);

//Declare variable of home url
function pravel_add_home_url (){
	echo '<script type="text/javascript">
		var home_url = "'.home_url().'";
		var templateUrl = "'.get_bloginfo("template_url").'";
		</script>'; 
}
add_action('wp_head', 'pravel_add_home_url');

//ajax call function 
function pravel_ajax_login_init(){
	
	wp_register_script('ajax-login-script', PRAVEL_SIGNUP_PLUGIN_URL.'assets/js/ajax-login-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');
    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url(),
        'loadingmessage' => __('Sending user info, please wait...')
    ));
    add_action( 'wp_ajax_nopriv_pravel_ajaxlogin', 'pravel_ajaxlogin' );
    add_action( 'wp_ajax_nopriv_pravel_ajaxregister', 'pravel_ajaxregister' );
    add_action( 'wp_ajax_nopriv_pravel_email_check', 'pravel_email_check' );
    add_action( 'wp_ajax_nopriv_pravel_check_activation', 'pravel_check_activation' );
    add_action( 'wp_ajax_nopriv_pravel_forgot_password', 'pravel_forgot_password' );   
    add_action( 'wp_ajax_nopriv_pravel_change_password', 'pravel_change_password' );
	
}
if (!is_user_logged_in()) {
    add_action('init', 'pravel_ajax_login_init');
}
 
//Ajax Login Function
function pravel_ajaxlogin(){
	global $wpdb;
    check_ajax_referer( 'ajax-login-nonce', 'security' );	
    $username = sanitize_text_field($_POST['username']);
	$user = get_user_by( 'email', $username );
	$user_id = $user->ID;
	if($user_id == null)
	{
		$user = get_user_by( 'login', $username );
		$user_id = $user->ID;
	}
	$user_check = get_user_meta($user_id, 'has_to_be_activated' ,true);
	if($user_check != '')
	{
		echo json_encode(array('loggedin'=>false, 'message'=>__('You Need to active your account')));
	}
	else
	{
		$info = array();
		$info['user_login'] = sanitize_text_field($_POST['username']);
		$info['user_password'] = sanitize_text_field($_POST['password']);
		$user_signon = wp_signon( $info, false );
		if ( is_wp_error($user_signon) ){		
			echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong Username or Password')));
			
		} else {
			echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
		}
	}
	

    die();
}

//Ajax Signup Function
function pravel_ajaxregister(){
	global $wpdb;
	$first_name = sanitize_text_field($_POST['user_first_name']);
	$last_name = sanitize_text_field($_POST['user_last_name']);
	$user_login = sanitize_text_field($_POST['user_name']);
	$user_pass = sanitize_text_field($_POST['user_password']);	
	$user_email = sanitize_text_field($_POST['user_email']);
	
    $userdata = array();
    $userdata['display_name'] = $first_name.' '.$last_name;
	$userdata['user_login'] = $user_login;
	$userdata['user_pass'] = $user_pass;
	$userdata['user_email'] = $user_email;
	$userdata['role'] = 'pending';
	
	
	
	$user_id = wp_insert_user($userdata);
	
	if (!is_wp_error($user_id)) 
	{
		$activation_code = rand(1000,9999);		
		
		add_user_meta( $user_id, 'has_to_be_activated', $activation_code, true );
		
		$message = 'Your activation code is '.$activation_code;
					
		wp_mail( $user_email, 'User Activation', $message );
		
		update_user_meta( $user_id, 'first_name', $first_name);
		update_user_meta( $user_id, 'last_name', $last_name);		
		echo json_encode(array('signup'=>true, 'message'=>__('SignUp successful, redirecting...')));
	} 
	else 
	{
		if (isset($user_id->errors['empty_user_login'])) 
		{
		  echo json_encode(array('signup'=>false, 'message'=>__('User Name is Empty')));
		} 
		elseif (isset($user_id->errors['existing_user_login'])) 
		{
		  echo json_encode(array('signup'=>false, 'message'=>__('User Name already Exist')));
		}
		elseif (isset($user_id->errors['existing_user_email'])) 
		{
		  echo json_encode(array('signup'=>false, 'message'=>__('Email already Exist')));
		}
		else 
		{
		  echo'Error Occured please fill up the sign up form carefully.';
		}
	}

    die();
}

//Ajax Email check while signup process
function pravel_email_check(){
	global $wpdb;
	$user_email = sanitize_text_field($_POST['user_email']);	
	$user_name = sanitize_text_field($_POST['user_name']);
	
	$exists_email = email_exists( $user_email );
	$exists_username = username_exists( $user_name );
	
	if ($exists_email) 
	{		
		echo json_encode(array('signup'=>false, 'message'=>__('Email already Exist')));		
	} 
	else if($exists_username){
		echo json_encode(array('signup'=>false, 'message'=>__('UserName already Exist')));		
	}
	else
	{
		echo json_encode(array('signup'=>true, 'message'=>__('')));
	}
	
    die();
}

//Ajax activation code check
function pravel_check_activation(){
	$activation_code = sanitize_text_field($_POST['activation_code']);	
	$user = reset( get_users(
		  array(
		   'meta_key' => 'has_to_be_activated',
		   'meta_value' => $activation_code,
		   'number' => 1,
		   'count_total' => false
	  ) ) );
	$user_id = $user -> ID; 
	$wp_capabilities = 	get_user_meta( $user_id, 'wp_capabilities', true );
	unset($wp_capabilities["pending"]);	
	$wp_capabilities['subscriber'] = true;
	
	if($user_id == null)
	{
		echo json_encode(array('activation'=>false, 'message'=>__('Activation code is wrong')));		
	}
	else
	{
		$code = get_user_meta( $user_id, 'has_to_be_activated', true );
		 if ( $code == $activation_code ) {
			 update_user_meta( $user_id, 'wp_capabilities', $wp_capabilities );
			 update_user_meta( $user_id, 'show_admin_bar_front', false );
			 delete_user_meta( $user_id, 'has_to_be_activated' );
			 echo json_encode(array('activation'=>true, 'message'=>__('Successfully activation done you can login..')));	
		 }
	}
	die();
}

//Ajax forgot password 
function pravel_forgot_password(){
	$user_email = sanitize_text_field($_POST['user_email']);
	$user = get_user_by( 'email', $user_email );
	$page_url = sanitize_text_field($_POST['pageURL']);
	$user_id = $user->ID;
	
	if($user_id == null)
	{
		echo json_encode(array('activation'=>false, 'message'=>__('Email is not registered')));
	}
	else
	{	
		$activation_code = sha1( $user_id . time() );  
		
		$activation_link = $page_url.'?forgot_key='.$activation_code.'&user='.$user_id;	
		add_user_meta( $user_id, 'forgot_email', $activation_code, true );
		
		$message = 'Click here to change your password ' . $activation_link;
					
		wp_mail( $user_email, 'User Forgot Password', $message );

		echo json_encode(array('activation'=>true, 'message'=>__('Link for change password sent your mail')));
	}
	die();
}

//Change password using Mail link
function pravel_change_password(){
	$reset_user_id = sanitize_text_field($_POST['reset_user_id']);
	$new_password_custom = sanitize_text_field($_POST['new_password_custom']);
	$server_side_activation_code = get_user_meta($reset_user_id, 'forgot_email', true);
	$user_side_activation_code = sanitize_text_field($_POST['reset_activation_code']);
	
	if($user_side_activation_code == $server_side_activation_code )
	{
		wp_set_password( $new_password_custom, $reset_user_id );
		delete_user_meta( $reset_user_id, 'forgot_email' );
		echo json_encode(array('activation'=>true, 'message'=>__('Password has been changed successfully')));			
	}
	else{
		echo json_encode(array('activation'=>false, 'message'=>__('Something went wrong please try again later')));
	}
	die();
}

