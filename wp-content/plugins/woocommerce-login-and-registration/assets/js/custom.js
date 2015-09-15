jQuery(document).ready(function(){ 
  
   jQuery(".header_login").colorbox({width:"50%", height:"525px", inline:true, href:"#login_data", opacity:0.5, closeButton:true});
   jQuery(".header_signup").colorbox({width:"50%", height:"460px", inline:true, href:"#signup_data", opacity:0.5, closeButton:true});

  jQuery('#js_login').submit(function(event) {
	  
		event.preventDefault();
		var u_name = jQuery(this).find('#username').val();//jQuery('#usernamee').val();
		var u_pass = jQuery(this).find('#password').val();//jQuery('#passwordd').val();
		var u_remember = jQuery(this).find('#rememberme').val();//jQuery('#rememberme').val();
		jQuery(".loader1").show();
		jQuery.ajax({
			type: 'POST',
			url : woo_log_ajaxurl.ajaxurl,
			data : {      			
					action : 'val_header',
					username : u_name,
					password : u_pass,
					rememberme : u_remember
					}, 
			success: function(data,status) 
			{
				if(data == 'success') {
					
					jQuery(".loader1").hide();
					window.location.href = window.location.protocol+ "//" + window.location.host;
					
				}else { 
				
				   jQuery(".loader1").hide();
				   jQuery(".result1").html(data);
				   
				}
		   }
		});

   });

   jQuery('#js_signup').submit(function(event) { 
			
				event.preventDefault(); 
				var u_email = jQuery(this).find('#reg_email_header').val();//jQuery('#reg_email_header').val();
				var u_passd = jQuery(this).find('#reg_password_header').val();//jQuery('#reg_password_header').val();
				jQuery(".loader_reg").show();
				
				
			    jQuery.ajax({
					type: 'POST',
					url : woo_log_ajaxurl.ajaxurl,
					data : {      			
							action : 'val_header_signup',
							email : u_email,
							password : u_passd
							}, 
					success: function(data,status) {
					if(data == 'success'){
						
						jQuery(".loader_reg").hide();
						window.location.href = window.location.protocol+ "//" + window.location.host + "/my-account/";

					}else{ 
					   jQuery(".loader_reg").hide();
					   jQuery(".result2").html(data);
					   
					} 
				   }
				}); 
           });  
		   
   });