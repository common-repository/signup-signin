<?php

//Login Shortcode
function pravel_login_form(){
	if(is_user_logged_in())
	{
		return;
	}
	
	$data = '<div class="sign_up_bg" id="custom_login">
		<div class="sign_up_main">	
			<div class="sign-title">
					<h3>LOGIN</h3>
				</div>
			<form id="login-form" >
				<div class="login-form">
				
				<p class="error_pravel" style="display:none;"></p>
					<div class="input-box">
						<p>EMAIL<sup style="color:red;">*</sup></p>
						<input type="text" name="email" id="login_email" placeholder="pravel@gmail.com">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="input-box">
						<p>PASSWORD<sup style="color:red;">*</sup></p>
						<input type="password" name="password" id="login_password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
						<p class="field_error" style="display:none;"></p>
					</div>
					<div class="login-form-btn">
						<button type="button" id="login_user">LOGIN</button>
						'.wp_nonce_field( 'ajax-login-nonce', 'security' ).'
					</div>
				</div>
			</form>
			
		</div>
	</div>';
	return $data;
}

add_shortcode('pravel_login_form','pravel_login_form');

//SignUp Shortcode
function pravel_signup_form(){ 
	if(is_user_logged_in())
	{
		return;
	}
	
	$data = '
	<div class="sign_up_bg" >			
		<div class="sign_up_main">			
			<div class="sign-right">
				<div class="sign-title">
					<h3>CREATE A FREE ACCOUNT</h3>
				</div>
				<div class="sign-box">
				<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
				<div class="loading_screeen" style="display:none;">
					<img src="'. PRAVEL_SIGNUP_PLUGIN_URL .'/assets/image/loader.gif" />
				</div>
					<div class="sign-form sign-step01 form_on" style="display:none"  form_on = "form_1">
						<div class="sign-input-box ">
							<p>FIRST NAME<sup style="color:red;">*</sup></p>
							<input type="text" name="first_name" id="user_first_name" placeholder="First Name">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="sign-input-box">
							<p>LAST NAME<sup style="color:red;">*</sup></p>
							<input type="text" name="user_last_name" id="user_last_name" placeholder="Last Name">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="pravel_clearfix"></div>
						<div class="sign-input-box">
							<p>EMAIL ADDRESS<sup style="color:red;">*</sup></p>
							<input type="text" name="user_email" id="user_email" placeholder="Email Address">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="pravel_clearfix"></div>
					
						<div class="sign-input-box">
							<p>USERNAME<sup style="color:red;">*</sup></p>
							<input type="text" id="user_name"  name="user_name" placeholder="User Name">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="sign-input-box">
							<p>PASSWORD<sup style="color:red;">*</sup></p>
							<input type="password" name="user_password"id="user_password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="sign-input-box">
							<p>RETYPE PASSWORD<sup style="color:red;">*</sup></p>
							<input type="password" id="user_c_password" name="user_c_password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							<p class="field_error" style="display:none;"></p>
						</div>
						<div class="pravel_clearfix"></div>
					</div>
			   
					<div class="sign-form sign-step02 " style="display:none" form_on = "form_2">
						<div class="sign-input-box verification_code">
								<p>ENTER VERIFICATION CODE</p>
								<p class="thankyou_text_verification">Thank you – To establish your account and confirm your contact information, we have emailed you a verification code to the address you provided. Please enter the code below to complete your account setup</p> 
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}"  id="inp_1" />
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="inp_2" />
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="inp_3" />
								<input type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="inp_4" />							
								<p class="thankyou_text_verification"></p>
							</div>
					</div>
				   
				 
				   <div class="pravel_clearfix"></div>
					<div class="sign-form-bottom">
						<div class="sign-form-btn">
							<button class="blue-btn" type="button" id="next_page_show">SUBMIT</button>
						 </div>							
						 <div class="pravel_clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>';
	
	return $data;
}

add_shortcode('pravel_signup_form','pravel_signup_form');



//Forgot Password Shortcode
function pravel_forgotpassword_form(){
	if(is_user_logged_in())
	{
		return;
	}
	if(isset($_GET['forgot_key'])&& isset($_GET['user'])){
		$reset_user_id = sanitize_text_field( $_GET['user'] );
		$reset_activation_code = sanitize_text_field( $_GET['forgot_key'] );
		$data = '<div class="sign_up_bg" >
			
			<div class="sign_up_main">
				<div class="Pravel_popup_close_btn pravel_sing_close">
			        <a href="javascript:void(0)" class="pravel_close"></a>
	        </div>
				<div class="sign-left">
				</div>
				<div class="sign-right">
					<div class="sign-title">
						<h3>CHANGE PASSWORD</h3>
					</div>
					<div class="sign-box">
					<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
					<div class="loading_screeen" style="display:none;">
						<img src="'. PRAVEL_SIGNUP_PLUGIN_URL .'/assets/image/loader.gif" />
					</div>
					<div class="sign-form sign-step01" >							
							<div class="">
								<p>NEW PASSWORD</p>
								<input type="password" name="new_password_custom" id="new_password_custom" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							</div>
							<div class="">
								<p>CONFIRM PASSWORD</p>
								<input type="password" name="confirm_password_custom" id="confirm_password_custom" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;">
							</div>
							<input type="hidden" id="reset_user_id" value="'.$reset_user_id.'" />
							<input type="hidden" id="reset_activation_code" value="'.$reset_activation_code.'" />
							<div class="clearfix"></div>
						</div>
					   <div class="clearfix"></div>
						<div class="sign-form-bottom">
							<div class="sign-form-btn">
								<button class="blue-btn" type="button" id="change_password_new">SUBMIT</button>
							 </div>							 
							 <div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>	';
	}
	else{
	$data = '
	<div class="sign_up_bg" >			
		<div class="sign_up_main">				
			
			<div class="sign-right">
				<div class="sign-title">
					<h3>FORGOT PASSWORD</h3>
				</div>
				<div class="sign-box">
				<p class="show_reg_error_message" style="display:none;">Kindly fill required fields</p>
				<div class="loading_screeen" style="display:none;">
					<img src="'. PRAVEL_SIGNUP_PLUGIN_URL .'/assets/image/loader.gif" />
				</div>
				<div class="sign-form sign-step01" >
						
						<div class="">
							<p>EMAIL ADDRESS</p>
							<input type="email" name="user_email" id="user_email_fpassword" placeholder="johnsmith@gmail.com">
						</div>
						<div class="clearfix"></div>
					</div>
				   <div class="clearfix"></div>
					<div class="sign-form-bottom">
						<div class="sign-form-btn">
							<button class="blue-btn" type="button" id="forgot_password_submit">SUBMIT</button>
						 </div>							 
						 <div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div> ';
	
	}
	return $data;
}
add_shortcode('pravel_forgotpassword_form','pravel_forgotpassword_form');

