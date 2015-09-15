<?php
if (isset($_POST['save_my_smart_layer'])) { ?>
<div class="updated settings-error" id="ettings_updated" > 
	<p><strong>Settings saved.</strong></p>
</div>
<?php } ?>
<div class="updated addthis_setup_nag">
    <p>AddThis Pro now available - start your trial at 
        <a href="http://www.addthis.com" target="_blank">www.addthis.com</a> 
        and get premium widgets, personalized content recommendations, 
        advanced customization options and priority support.
    </p>
</div>
<div class="smart-layer-wrap advanced">
<div class="smart-layer-tab"><a class="smart-layer-trigger">Revert to WYSIWYG</a></div>
<p>
	<img alt="addthis" src="//cache.addthis.com/icons/v1/thumbs/32x32/more.png" class="header-img">
    <span class="smart-layer-title">AddThis</span> <span class="smart-layer-name">Smart Layers</span>
</p>
<?php 
	    	if (get_option('smart_layer_profile') != "") {
	    		$smart_layer_id = get_option('smart_layer_profile');
	    	}
	    	else {
	    		global $addthis_addjs;
				$smart_layer_id = $addthis_addjs->pubid ;
	    	}

			?>
<form action="#" method="post">
<span id="profile_id" style="float: left;border: none;padding-right: 0px;font-size: 12px;width: 100%;" data-content="By specifying your AddThis Profile ID, you will have access to analytics measuring your shares, follows and clicks. You can find your AddThis Profile ID in the <a href='https://www.addthis.com/settings/user' target='_blank'>Settings</a> section of <a href='www.addthis.com' target='_blank'>www.addthis.com"</a> 
        <p style="margin: 0; font-weight: bold; margin: 5px; width: 130px; float: left;"><?php _e("AddThis Profile ID:", 'addthis_trans_domain' ); ?></p>
        <input id="addthis_profile" type="text" name="addthis_profile" value="<?php echo $smart_layer_id;?>" autofill='off' autocomplete='off' />
        (<a href="#">?</a>)
	</span>
<div class="smart-layer-container">
	<div>	
	<div class="lwrOpt ml10">
        <div class="wbcHdr">
        	<h3>Here you can specify a custom API configuration.  This will give you the ability to use all the different API options available in the <a href="http://support.addthis.com/customer/portal/articles/1200473-smart-layers-api#.Uk0EmW6Mi0w" target="_blank">Smart Layers API.</a></h3>
        </div>
	</div>
	<div class="clear"></div>
	<div class="clear"></div>
</div>
<div class="codeRt">
	<div class="instructions">
		<div>
			<span class="copyLabel" style="float: left;width: 500px;">Edit your settings.</span>
			<span class="copyLabel">Example</span>
		</div>
    	<div class="RDcodesize">
             <div class="clear"></div>
        </div>
    </div>
    <div class="copyCode">
    	
			<?php //settings_fields('smart_layer_settings'); ?>
			<?php $options = get_option('smart_layer_settings'); ?>		
			<textarea id="wbCode" name="smart_layer_settings" style="resize:both;height:400px;background:#fff;"><?php echo $options; ?></textarea>
			<div class="clear"></div><div class="clear"></div><div class="clear"></div>
			<input type="hidden" name="save_my_smart_layer" value="save_my_smart_layer" />
			<?php if(current_user_can('unfiltered_html')) {
				submit_button();
			} ?> 
        </form>
        <span class="legal">By publishing this code, you are accepting our <a href="http://www.addthis.com/tos" target="_blank">Terms of Service</a></span>
		<div class="clear"></div><div class="clear"></div><div class="clear"></div><div class="clear"></div>
		<div class="clear"></div><div class="clear"></div><div class="clear"></div><div class="clear"></div>
    </div>
    
    <div class="copyCode">
    	<pre class="prettyprint" style="margin-top:10px">
{
theme : 'transparent',
share : {
    'position' : 'left',
    'services' : 'facebook,twitter,email,print,more',
    //'numPreferredServices' : 5,
    'postShareTitle' : 'Thanks for sharing!',
    'postShareFollowMsg' : 'Follow us',
    'postShareRecommendedMsg' : 'Recommended for you'
},
follow : {
    'services' : [
        {'service' : 'facebook', 'id' : 'AddThis'},
        {'service' : 'twitter', 'id' : 'AddThis'}
    ],
    'title' : 'Follow',
    'postFollowTitle' : 'Thanks for following!',
    'postFollowRecommendedMsg' : 'Recommended for you'
},
whatsnext : {
    'recommendedTitle' : 'Recommended for you',
    'shareMsg' : 'Share to [x]',
    'followMsg' : 'Follow us on [x]'
},
recommended : {
    'title' : 'Recommended for you'
},
mobile : {
    'buttonBarPosition' : 'top',
    'buttonBarTheme' : 'transparent'
}
}
    	</pre>


    </div>
    <div class="clear"></div><div class="clear"></div><div class="clear"></div>
</div>
</div>
<div class="smart-layer-dialog" style="display:none">
    <p>Are you sure you want to go back to the WYSIWYG editor?  You will lose your custom API configuration and you will not be able to recover it.</p>
    <form class="smart-layer-dialog-buttons" method="post" action="options.php" style="float:left;margin-top:2px;padding-top:0">
		<?php settings_fields('smart_layer_settings_advanced'); ?>
		<?php $options = get_option('smart_layer_settings_advanced'); ?>
		<input type="hidden" name="smart_layer_settings_advanced" value = "<?php if( get_option('smart_layer_settings_advanced') != '0') { echo '0';} else { echo '1';} ?>" />
        <input id="smart-layer-dialog-ok" class="button button-highlighted" type="submit" value="OK" />		
	</form>
	<button id="smart-layer-dialog-cancel" class="button" value="Cancel" />
		Cancel
	</button>
</div>
<script type="text/javascript">
    var currentPage = 'smart-layers';
</script>