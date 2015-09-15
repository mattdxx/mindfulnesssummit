jQuery(document).ready(function($) {  
	var addthis_credential_validation_status = $("#addthis_credential_validation_status");
    var addthis_validation_message = $("#addthis-credential-validation-message");
    var addthis_profile_validation_message = $("#addthis-profile-validation-message");
    
  //Validate the Addthis credentials
    window.skipValidationInternalError = false;
    function validate_addthis_credentials() {
         $.ajax(
             {"url" : smart_layer_option_params.wp_ajax_url,
              "type" : "post",
              "data" : {"action" : smart_layer_option_params.smart_layer_validate_action,
                       "addthis_profile" : $("#addthis_profile").val(),
                       "addthis_username" : $("#addthis_username").val(),
                       "addthis_password" : $("#addthis_password").val()
                   },
              "dataType" : "json",
              "beforeSend" : function() {
                  $(".addthis-admin-loader").show();
                  addthis_validation_message.html("").next().hide();
                  addthis_profile_validation_message.html("").next().hide();
              },
              "success": function(data) {
                  addthis_validation_message.show();
                  addthis_profile_validation_message.show();

                  if (data.credential_message == "error" || (data.profile_error == "false" && data.credential_error == "false")) {
                      if (data.credential_message != "error") {
                          addthis_credential_validation_status.val('1');
                      } else {
                          window.skipValidationInternalError = true;
                      }
                      $("#addthis_settings").submit();
                  } else {
                      addthis_validation_message.html(data.credential_message);
                      addthis_profile_validation_message.html(data.profile_message);
                  }

              },
              "complete" :function(data) {
                  $(".addthis-admin-loader").hide();
              },
              "error" : function(jqXHR, textStatus, errorThrown) {
                  console.log(textStatus, errorThrown);
              }
          });
     }
    
  //Prevent default form submission
    $("#addthis_settings").submit(function(){
        if(window.skipValidationInternalError) {
            return true;
        }
        var isProfileEmpty = $.trim($("#addthis_profile").val()) == "";
        var isUsernameEmpty = $.trim($("#addthis_username").val()) == "";
        var isPasswordEmpty = $.trim($("#addthis_password").val()) == "";
        var isAnyFieldEmpty = isProfileEmpty || isUsernameEmpty || isPasswordEmpty;
        var validationRequired = addthis_credential_validation_status.val() == 0;
        
        if(isUsernameEmpty != isPasswordEmpty) {
            var emptyLabel = isUsernameEmpty ? "username" : "password";
            addthis_validation_message.html("&#x2716; AddThis " + emptyLabel + " is required to view analytics.").next().hide();
            return false;
        } else if (isProfileEmpty && !isUsernameEmpty && !isPasswordEmpty) {
            addthis_profile_validation_message.html("&#x2716; AddThis profile ID is required to view analytics.").next().hide();
            return false;
        } else if (!validationRequired || isAnyFieldEmpty) {
            return true;
        } else if(!isAnyFieldEmpty && validationRequired) {
            validate_addthis_credentials();
            return false;
        }
    });
    
    $("#addthis_username, #addthis_password, #addthis_profile").change(function(){
        addthis_credential_validation_status.val(0);
        if($.trim($("#addthis_profile").val()) == "") {
             addthis_profile_validation_message.next().hide();
        }
        if(($.trim($("#addthis_username").val()) == "") || ($.trim($("#addthis_password").val()) == "")) {
             addthis_validation_message.next().hide();
        }
     });
    
      
});