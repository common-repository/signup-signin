jQuery(document).ready(function(){ 
	
	
//Login user JS code
  jQuery('#login_user').click(function(){
		jQuery('p.error_pravel').hide();
		jQuery('.input-box').removeClass('input_field_error');
		jQuery('.field_error').hide();
		var data_email = jQuery('#login_email').val();
		var data_password = jQuery('#login_password').val();
		if(data_password == ''){
			jQuery('#login_password').focus();
			jQuery('#login_password').parent('.input-box').addClass('input_field_error');
			jQuery('#login_password').parent('.input-box').children('.field_error').text('Password is Required');
			jQuery('#login_password').parent('.input-box').children('.field_error').show();
		}
		if(data_email == ''){
			jQuery('#login_email').focus();
			jQuery('#login_email').parent('.input-box').addClass('input_field_error');
			jQuery('#login_email').parent('.input-box').children('.field_error').text('Email is Required');
			jQuery('#login_email').parent('.input-box').children('.field_error').show();
		}
		if(data_email == '' || data_password == '')
		{			
			return;
		}
		
		jQuery('p.error_pravel').text(ajax_login_object.loadingmessage);
		jQuery('p.error_pravel').show();
		jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'pravel_ajaxlogin',
                'username': data_email, 
                'password':data_password, 
                'security': jQuery('#login-form #security').val() 
			},
            success: function(data){
                if(data.loggedin == true){
                    jQuery('p.error_pravel').addClass('success');
                    jQuery('p.error_pravel').text(data.message);
                    setTimeout(function(){
                        window.location.href = home_url;
                    },2000);
                }
                else
                {
                    jQuery('p.error_pravel').removeClass('success');
			        jQuery('p.error_pravel').text(data.message);
                }
            }
        }); 
   });
   
   
   //Forgot password JS code
   jQuery('#forgot_password_submit').click(function(){
		var user_email = jQuery('#user_email_fpassword').val();
		var pageURL = jQuery(location).attr("href");
	
		if(user_email == ''){			
			jQuery('.show_reg_error_message').show().text('Enter your registered email');
			return;
		}
		if(!validateEmail(user_email))
		{			
			jQuery('.show_reg_error_message').show().text('Email is not vaild');
			return;
		}
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_login_object.ajaxurl,
			data: { 
				'action': 'pravel_forgot_password',				
				'user_email':user_email, 
				'pageURL':pageURL,
			},
			beforeSend: function() {
				jQuery('#forgot_password_submit').prop('disabled', true);
				jQuery('.loading_screeen').show();
			},
			success: function(data){
				jQuery('.loading_screeen').hide();
				jQuery('#forgot_password_submit').prop('disabled', false);
				if(data.activation == false)
				{
					jQuery('p.show_reg_error_message').removeClass('success');
					jQuery('.show_reg_error_message').show().text(data.message);
				}
				else
				{
					jQuery('p.show_reg_error_message').addClass('success');
					jQuery('.show_reg_error_message').show().text(data.message);
					setTimeout(function(){ 
						window.location.href = home_url;
					}, 2000);
					
				}
			}
		});
	})
   
    //Change Password JS code
	jQuery('#change_password_new').click(function(){
		var new_password_custom = jQuery('#new_password_custom').val();
		var confirm_password_custom = jQuery('#confirm_password_custom').val();
		var reset_user_id = jQuery('#reset_user_id').val();
		var reset_activation_code = jQuery('#reset_activation_code').val();
		if(!pravel_strong_password(new_password_custom)){
	        jQuery('.show_reg_error_message').show().text('You need to add strong password with 8 character long and also include atleast 1 digit, 1 special character and 1 Uppercase letter');
			return;
	    }
		if(new_password_custom == '' || confirm_password_custom == '' || new_password_custom != confirm_password_custom){			
			jQuery('.show_reg_error_message').show().text('Enter same password for both field');
			return;
		}
	    
		
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_login_object.ajaxurl,
			data: { 
				'action': 'pravel_change_password',				
				'new_password_custom':new_password_custom, 				
				'reset_user_id':reset_user_id, 				
				'reset_activation_code':reset_activation_code, 				
			},
			beforeSend: function() {
				jQuery('#change_password_new').prop('disabled', true);
				jQuery('.show_reg_error_message').hide();
				jQuery('.loading_screeen').show();
			},
			success: function(data){
				jQuery('.loading_screeen').hide();
				jQuery('#change_password_new').prop('disabled', false);
				if(data.activation == false)
				{
					jQuery('p.show_reg_error_message').removeClass('success');
					jQuery('.show_reg_error_message').show().text(data.message);
				}
				else
				{
					jQuery('p.show_reg_error_message').addClass('success');
					jQuery('.show_reg_error_message').show().text(data.message);
					setTimeout(function(){ 
						window.location.href = home_url;
					}, 2000);
					
				}
			}
		});
	});
	
	//Signup JS code
   jQuery('#next_page_show').click(function(){
	 
		var current_form = jQuery('.sign-form.form_on').attr('form_on');		
		
		switch(current_form) {
			case 'form_1':				
				var user_first_name = jQuery.trim(jQuery('#user_first_name').val());
				var user_last_name = jQuery('#user_last_name').val();
				var user_email = jQuery('#user_email').val();							
				var user_name = jQuery('#user_name').val();
				var user_password = jQuery('#user_password').val();
				var user_c_password = jQuery('#user_c_password').val();		
				jQuery('.sign-input-box').removeClass('input_field_error');
				jQuery('.field_error').hide();
				
				if(user_c_password == ''){
					jQuery('#user_c_password').focus();
					jQuery('#user_c_password').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_c_password').parent('.sign-input-box').children('.field_error').text('Confirm Password is Required');
					jQuery('#user_c_password').parent('.sign-input-box').children('.field_error').show();
				}
				if(user_password == ''){
					jQuery('#user_password').focus();
					jQuery('#user_password').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_password').parent('.sign-input-box').children('.field_error').text('Password is Required');
					jQuery('#user_password').parent('.sign-input-box').children('.field_error').show();
				}
				if(user_name == ''){
					jQuery('#user_name').focus();
					jQuery('#user_name').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_name').parent('.sign-input-box').children('.field_error').text('User Name is Required');
					jQuery('#user_name').parent('.sign-input-box').children('.field_error').show();
				}
				if(user_email == ''){
					jQuery('#user_email').focus();
					jQuery('#user_email').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_email').parent('.sign-input-box').children('.field_error').text('Email is Required');
					jQuery('#user_email').parent('.sign-input-box').children('.field_error').show();
				}
				if(user_last_name == ''){
					jQuery('#user_last_name').focus();
					jQuery('#user_last_name').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_last_name').parent('.sign-input-box').children('.field_error').text('Last is Required');
					jQuery('#user_last_name').parent('.sign-input-box').children('.field_error').show();
				}
				if(user_first_name == ''){
					jQuery('#user_first_name').focus();
					jQuery('#user_first_name').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_first_name').parent('.sign-input-box').children('.field_error').text('First Name is Required');
					jQuery('#user_first_name').parent('.sign-input-box').children('.field_error').show();
				}
							
				if(user_first_name == '' || user_last_name == '' || user_email == '' || user_name == '' || user_password == '' || user_c_password == '')
				{
					return;
				}
				else if( !validateEmail(user_email)) {
					jQuery('#user_email').focus();
					jQuery('#user_email').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_email').parent('.sign-input-box').children('.field_error').text('Email is not valid');
					jQuery('#user_email').parent('.sign-input-box').children('.field_error').show();				
				}
				else if(!pravel_strong_password(user_password)){
					jQuery('#user_password').focus();
					jQuery('#user_password').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_password').parent('.sign-input-box').children('.field_error').text('You need to add strong password with 8 character long and also include atleast 1 digit, 1 special character and 1 Uppercase letter');
					jQuery('#user_password').parent('.sign-input-box').children('.field_error').show();	
				}
				else if( user_password != user_c_password) {
					jQuery('#user_c_password').focus();
					jQuery('#user_c_password').parent('.sign-input-box').addClass('input_field_error');
					jQuery('#user_c_password').parent('.sign-input-box').children('.field_error').text('Confirm password is not same as Password');
					jQuery('#user_c_password').parent('.sign-input-box').children('.field_error').show();					
				}
				else
				{
					
					jQuery.ajax({
						type: 'POST',
						dataType: 'json',
						url: ajax_login_object.ajaxurl,
						data: { 
							'action': 'pravel_email_check',							 
							'user_email':user_email, 
							'user_name':user_name,
							'security': jQuery('#login-form #security').val() 
						},
						beforeSend: function() {
							jQuery('.loading_screeen').show();
						},
						success: function(data){
							jQuery('.loading_screeen').hide();
							if(data.signup == false)
							{
								jQuery('.show_reg_error_message').show().text(data.message);
							}	
							else
							{
								
								jQuery.ajax({
									type: 'POST',
									dataType: 'json',
									url: ajax_login_object.ajaxurl,
									data: { 
										'action': 'pravel_ajaxregister',
										'user_first_name': user_first_name, 
										'user_last_name':user_last_name, 
										'user_email':user_email, 							
										'user_name':user_name, 
										'user_password':user_password, 
										'security': jQuery('#login-form #security').val()
									},
									beforeSend: function() {
										jQuery('.loading_screeen').show();
									},
									success: function(data){
										
										jQuery('.loading_screeen').hide();
										jQuery('p.show_reg_error_message').addClass('success');
										jQuery('p.show_reg_error_message').show().text(data.message);
										
										jQuery('.show_reg_error_message').hide();
										jQuery('.sign-form.form_on').removeClass('form_on');
										jQuery('.sign-form.sign-step02').addClass('form_on');					
									}
								});
							}
						}
					});
					
				}
						
			break;			
			
			
		
			
			case 'form_2':
				
				var inp_1 = jQuery('#inp_1').val();
				var inp_2 = jQuery('#inp_2').val();
				var inp_3 = jQuery('#inp_3').val();
				var inp_4 = jQuery('#inp_4').val();				
				var activation_code = inp_1 + inp_2 + inp_3 + inp_4;
				
				jQuery.ajax({
					type: 'POST',
					dataType: 'json',
					url: ajax_login_object.ajaxurl,
					data: { 
						'action': 'pravel_check_activation',
						'activation_code': activation_code, 							
					},
					beforeSend: function() {
						jQuery('.loading_screeen').show();
					},
					success: function(data){						
						jQuery('.loading_screeen').hide();
						if(data.activation == false)
						{
							jQuery('p.show_reg_error_message').removeClass('success');
							jQuery('.show_reg_error_message').show().text(data.message);
						}
						else
						{
							jQuery('p.show_reg_error_message').addClass('success');
							jQuery('.show_reg_error_message').show().text(data.message);
							setTimeout(function(){ 
								window.location.href = home_url;
							}, 2000);
							
						}
						
					}
				});			
			break;
		}
	
	});
	
	//Verification code js
	jQuery(".verification_code input").jqueryPincodeAutotab();

});



//Check valid email JS
function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}


function pravel_strong_password(value) {
	return /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/.test(value);
}