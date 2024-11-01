<?php
/**
* Plugin Name: SignUp & SignIn
* Plugin URI: https://pravelsolutions.com/PluginDemo
* Description: Easy way to implement signup and login process for wordpress user using shortcode.
* Version: 1.0.0
* Author: Pravel Solutions
* Author URI: https://pravelsolutions.com/
**/



function pravel_membership_init() {
	
	
	include(ABSPATH . "wp-includes/pluggable.php"); 

	define( 'PRAVEL_SIGNUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'PRAVEL_SIGNUP_PLUGIN_VERSION', '1.0.0' );
	define( 'PRAVEL_SIGNUP_PLUGIN_URL', plugin_dir_url(__FILE__) );
	define( 'PRAVEL_SIGNUP_PLUGIN_BASENAME', plugin_basename(__FILE__) );
	
	
	//Include Files
	include_once( PRAVEL_SIGNUP_PLUGIN_DIR . 'lib/function.php' );
	include_once( PRAVEL_SIGNUP_PLUGIN_DIR . 'templates/shortcodes.php' );
	
	
	
	//Add assests file
	function pravel_signup_load_scripts_front($hook)  {
		echo $hook;
		wp_enqueue_style( 'pravel_signup_stylesheet', PRAVEL_SIGNUP_PLUGIN_URL . 'assets/css/style.css', array(), PRAVEL_SIGNUP_PLUGIN_VERSION);
		
		wp_enqueue_script( 'pravel_signup_jquery', PRAVEL_SIGNUP_PLUGIN_URL . 'assets/js/main.js' , array(),  PRAVEL_SIGNUP_PLUGIN_VERSION, true);
		
		wp_enqueue_script( 'pravel_verification_code', PRAVEL_SIGNUP_PLUGIN_URL . 'assets/js/pravel-verification-code.min.js' , array(),  PRAVEL_SIGNUP_PLUGIN_VERSION, true);
	}
	add_action('wp_enqueue_scripts', 'pravel_signup_load_scripts_front');
	

		
}	
add_action( 'plugins_loaded', 'pravel_membership_init' );


function parvel_tl_save_error() {
    update_option( 'plugin_error',  ob_get_contents() );
}
add_action( 'activated_plugin', 'parvel_tl_save_error' );




