jQuery(document).ready(function(){ 
   
   if(jQuery("#popup1").attr('checked'))
   { 
           
            
		jQuery('#popup1').click(function()
		{ 
		
			jQuery(".login").fadeToggle();
			jQuery(".signup").fadeToggle();
			if(jQuery("#popup1").attr('checked'))
			{
				jQuery("#log_url").removeAttr("required");  
				 jQuery("#sign_url").removeAttr("required");
			}
			else
			{
				jQuery("#log_url").prop("required",true);
				jQuery("#sign_url").prop("required",true);
			}
			
		});
            
    }
	else
	{
		
		jQuery(".login").show();
		jQuery(".signup").show();
		jQuery('#popup1').click(function()
		{
			jQuery(".login").fadeToggle();
			jQuery(".signup").fadeToggle();
		}); 
	
	}    
   
   
});