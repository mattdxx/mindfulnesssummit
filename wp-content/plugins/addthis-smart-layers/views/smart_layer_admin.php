<?php 
$advanced = get_option('smart_layer_settings_advanced');
?>

<?php 
$smart_layer_pro = get_option('smart_layer_pro');
if ($smart_layer_pro) {
?>
    <script>
        $ = jQuery;
        $(window).load(function() {
   			//$('.grid_5').hide();
			// $('.grid_7').hide();
			// $('#custom_api').hide();
            $('.at40-accordion-checkbox').iButton('disable');
            $("#at40-accordion-more-themesSelectBoxIt").addClass("selectboxit-disabled");
            $("a.smart-layer-trigger").unbind('click');
        });
    </script>
<?php } ?>

<?php if (isset($_POST['save_smart_layer'])) { ?>
<div class="updated settings-error" id="ettings_updated" > 
	<p><strong>Settings saved.</strong></p>
</div>
<?php } ?>

<?php if($smart_layer_pro) { ?>
    <!-- <div class="updated addthis_setup_nag">
        <p>Since you are an AddThis Pro user, your configuration options can be managed from 
            <a href="https://www.addthis.com/dashboard#gallery" target="_blank">AddThis Pro Tool Gallery</a>.<br> 
        </p>
    </div> -->
<?php } else {?>
    <div class="updated addthis_setup_nag">
        <p>AddThis Pro now available - start your trial at 
            <a href="http://www.addthis.com" target="_blank">www.addthis.com</a> 
            and get premium widgets, personalized content recommendations, 
            advanced customization options and priority support.
        </p>
    </div>
<?php } ?>

<div class="smart-layer-wrap">
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
 <form method="post" action="#" id="smartlayers-getthecode" class="smartlayers-section addthis-tab active">
    <span id="profile_id" style="float: left;border: none;padding-right: 0px;font-size: 12px;width: 30%;" data-content="By specifying your AddThis Profile ID, you will have access to analytics measuring your shares, follows and clicks. You can find your AddThis Profile ID in the <a href='https://www.addthis.com/settings/user' target='_blank'>Settings</a> section of <a href='www.addthis.com' target='_blank'>www.addthis.com"</a> 
        <p style="margin: 0; font-weight: bold; margin: 5px; width: 130px; float: left;"><?php _e("AddThis Profile ID:", 'addthis_trans_domain' ); ?></p>
        <input id="addthis_profile" type="text" name="addthis_profile" value="<?php echo $smart_layer_id;?>" autofill='off' autocomplete='off' />
        (<a href="#">?</a>)
	</span>
<?php if(!$smart_layer_pro) { ?>
<div class="smart-layer-tab" id="custom_api">
<a class="smart-layer-trigger" title="Developers: Edit your Smart Layer plugin code using our API to unlock additional features.">Custom API Configuration</a>
</div>
<?php } ?>
<div class="smart-layer-container">
	<div class="container_12 mt20">
		<div class="grid_12">
	    	<h3>Make your site smarter. Increase traffic, engagement and revenue by instantly showing the right social tools and content to every visitor.</h3>
	    <?php if($smart_layer_pro) { ?>
	    	<p>Since you are an AddThis Pro user, your configuration options can be managed from 
            <a href="https://www.addthis.com/dashboard#gallery" target="_blank">AddThis Pro Tool Gallery</a>.
       		</p>
        <?php }?>
	    </div>
	    <div class="clear"></div>
	    <div class="mb20"></div>
	    <?php if(!$smart_layer_pro) { ?>

  		  <div class="grid_5">
    		<ul class="at40-accordion">
		      <li class="at40-accordion-row at40-accordion-follow first" data-type="follow">
		        <div class="at40-accordion-icon-and-text">
		          <span class="at40-accordion-icon ui-icon ui-icon-triangle-1-e"></span>
		          <span class="at40-accordion-text">Follow</span>
		        </div>
		        <div class="at40-accordion-checkbox-container">
		          <input id="at40-accordion-follow-checkbox" class="at40-accordion-checkbox" data-surface="follow" type="checkbox">
		        </div>
		      </li>
        	  <div class="at40-accordion-hidden at40-accordion-follow-hidden">
            	<div class="at40-accordion-hidden-inner">
                  <label>Buttons:</label>
				    <div class="rows">
				      <div class="row">
						<div class="facebookAndTwitterPopover" data-content="Please enter your social profiles into each text box to start creating your dynamic AddThis follow buttons.">
						 <div class="row-left">
						   <input type="checkbox" id="facebook-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="facebook" checked>
						   <div class="at40-follow-icon at40-follow-icon-facebook at40-follow-icon-label"></div>
						 </div>
						 <div class="row-right">
						   <div class="">facebook.com/ <input id="follow-facebook" name="uid[facebook]" type="text" size="15" class="ml5" value="YOUR-PROFILE" data-service="facebook" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://facebook.com.  Click your profile icon.  Then copy the string of letters and/or numbers after &quot;facebook.com/&quot; in the URL.  It will look like: &quot;https://facebook.com/greg.franko.10&quot;" data-original-title="Facebook">(<a href="#">?</a>)</span></div>
						 </div>
						 <div class="hide row-right connect-row">
						   <button class="btn btn-small connect-button" data-service="facebook" data-baseurl="http://facebook.com/" type="button" data-content="Look up your Facebook page" data-original-title="Facebook">Find</button>
						   <button class="btn btn-small btn-danger disconnect-button hide" data-service="facebook" data-baseurl="http://facebook.com/" type="button" data-content="Disconnect to manually type in your Facebook page" data-original-title="Disconnect Facebook">Disconnect</button>
						 </div>
						 <div class="clear"></div>
						</div>
				        <div class="row">
				          <div class="row-left">
				            <input type="checkbox" id="twitter-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="twitter">
				            <div class="at40-follow-icon at40-follow-icon-twitter at40-follow-icon-label"></div>
				          </div>
				          <div class="row-right">
				            <div class="">twitter.com/ <input id="follow-twitter" name="uid[twitter]" type="text" size="15" class="ml5" data-service="twitter" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://twitter.com.  Click the &quot;Me&quot; tab.  Then copy the string of letters and/or numbers after &quot;twitter.com/&quot; in the URL.  It will look like: &quot;https://twitter.com/GregFranko&quot;" data-original-title="Twitter">(<a href="#">?</a>)</span></div>
				          </div>
				          <div class="hide row-right connect-row">
				            <button class="btn btn-small connect-button" data-service="twitter" data-baseurl="http://twitter.com/" type="button" data-content="Look up your Twitter username" data-original-title="Twitter">Find</button>
				            <button class="btn btn-small btn-danger disconnect-button hide" data-service="twitter" data-baseurl="http://twitter.com/" type="button" data-content="Disconnect to manually type in your Twitter page" data-original-title="Disconnect Twitter">Disconnect</button>
				          </div>
				          <div class="clear"></div>
				        </div>
                        <div class="row">
                          <div class="row-left">
                            <input type="checkbox" id="linkedin-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="linkedin">
                            <div class="at40-follow-icon at40-follow-icon-linkedin at40-follow-icon-label"></div>
                          </div>
                          <div class="row-right">linkedin.com/in/ <input id="follow-linkedin" name="uid[linkedin]" type="text" size="15" class="ml5" data-service="linkedin" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://linkedin.com/profile/public-profile-settings.  Then copy your current profile url (it will be visible beneath the &quot;Your public profile URL&quot; section.  It will look like: &quot;www.linkedin.com/in/gregfranko&quot;" data-original-title="LinkedIn User">(<a href="#">?</a>)</span></div>
                          <div class="clear"></div>
                        </div>
				        <div class="row">
				          <div class="row-left">
				            <input type="checkbox" id="gplus-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="gplus">
				            <div class="at40-follow-icon at40-follow-icon-gplus at40-follow-icon-label"></div>
				          </div>
				          <div class="row-right">plus.google.com/ <input id="follow-gplus" name="uid[google]" type="text" size="15" class="ml5" data-service="gplus" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://plus.google.com.  Click the &quot;Profile&quot; tab.  Then copy the string of numeric digits before &quot;/posts&quot; in the URL.  It will look like: &quot;plus.google.com/110725518355805130542/posts&quot;" data-original-title="Google Plus">(<a href="#">?</a>)</span></div>
				          <div class="clear"></div>
				        </div>
        			</div>
        			<a href="#" class="show-more-follow-options-link">Show More</a>
        			<div class="show-more-follow-options">
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="linkedin-company-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="linkedin" data-company="true">
			              <div class="at40-follow-icon at40-follow-icon-linkedin-company at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">linkedin.com/company/ <input id="follow-linkedin-company" name="uid[linkedin]" type="text" size="15" class="ml5" data-service="linkedin" data-company="true" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://linkedin.com and sign in.  Then search for your company using the company search in the top right corner of the page.  Click on your company page.  Then copy the id in the URL.  It will look like: &quot;http://www.linkedin.com/company/167173&quot;" data-original-title="LinkedIn Company">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="youtube-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="youtube">
			              <div class="at40-follow-icon at40-follow-icon-youtube at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">youtube.com/user/ <input id="follow-youtube" name="uid[youtube]" type="text" size="15" class="ml5" data-service="youtube" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://youtube.com/user.  Your username will automatically appear in the address bar.  It will look like: &quot;youtube.com/user/gregfranko&quot;" data-original-title="YouTube">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="flickr-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="flickr">
			              <div class="at40-follow-icon at40-follow-icon-flickr at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">flickr.com/photos/ <input id="follow-flickr" name="uid[flickr]" type="text" size="15" class="ml5" data-service="flickr" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://flickr.com.  Click the &quot;You&quot; tab.  Then copy the string of letters and/or numbers after &quot;/photos/&quot; in the URL.  It will look like: &quot;http://flickr.com/photos/90503806@N02&quot;" data-original-title="Flickr">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="vimeo-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="vimeo">
			              <div class="at40-follow-icon at40-follow-icon-vimeo at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">vimeo.com/ <input id="follow-vimeo" name="uid[vimeo]" type="text" size="15" class="ml5" data-service="vimeo" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://vimeo.com.  Click the &quot;Me&quot; tab. Then copy the string of letters and/or numbers after &quot;vimeo.com/&quot; in the URL.  It will look like: &quot;https://vimeo.com/user5795445&quot;" data-original-title="Vimeo">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="pinterest-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="pinterest">
			              <div class="at40-follow-icon at40-follow-icon-pinterest at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">pinterest.com/ <input id="follow-pinterest" name="uid[pinterest]" type="text" size="15" class="ml5" data-service="pinterest" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://pinterest.com.  Click your profile picture.  Then copy the string of letters and/or numbers after &quot;pinterest.com/&quot; in the URL.  It will look like: &quot;https://pinterest.com/gregfranko&quot;" data-original-title="Pinterest">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="instagram-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="instagram">
			              <div class="at40-follow-icon at40-follow-icon-instagram at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">instagram.com/ <input id="follow-instagram" name="uid[instagram]" type="text" size="15" class="ml5" data-service="instagram" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://followgram.me.  View your username beneath the &quot;Share your vanity page&quot; section.  It will look like: &quot;http://followgram.me/gregfranko&quot;" data-original-title="Followgram">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="foursquare-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="foursquare">
			              <div class="at40-follow-icon at40-follow-icon-foursquare at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">foursquare.com/ <input id="follow-foursquare" name="uid[foursquare]" type="text" size="15" class="ml5" data-service="foursquare" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="Go to http://foursquare.com.  Click your profile picture.  Click &quot;view this page&quot;.  Then copy the string of letters and/or numbers after &quot;foursquare.com/&quot; in the URL.  It will like: &quot;https://foursquare.com/gregfranko&quot;" data-original-title="Foursquare">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="tumblr-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="tumblr">
			              <div class="at40-follow-icon at40-follow-icon-tumblr at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right"> <input id="follow-tumblr" name="uid[tumblr]" type="text" size="15" class="ml5" data-service="tumblr" data-updated="updated" style="border-color: rgb(204, 204, 204);"> &nbsp;.tumblr.com<span class="follow-tooltip" data-content="Go to http://www.tumblr.com.  Click your account details (the gear icon).  Click on your blog tab on the left side of the page.  Then copy the string of letters and/or numbers next to the &quot;url&quot; section.  It will look like: &quot;http://gfranko.tumblr.com&quot;" data-original-title="Tumblr">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
			          <div class="row">
			            <div class="row-left">
			              <input type="checkbox" id="rss-follow-checkbox" class="at40-accordion-follow-checkbox" data-service="rss">
			              <div class="at40-follow-icon at40-follow-icon-rss at40-follow-icon-label"></div>
			            </div>
			            <div class="row-right">http:// <input id="follow-rss" name="uid[rss]" type="text" size="15" class="ml5" data-service="rss" data-updated="updated" style="border-color: rgb(204, 204, 204);"><span class="follow-tooltip" data-content="The easiest way to get your RSS feed address, no matter what web browser you're using, is to look at the HTML source of your page." data-original-title="RSS Feed">(<a href="#">?</a>)</span></div>
			            <div class="clear"></div>
			          </div>
                      <a href="#" class="show-less-follow-options-link">Show Less</a>
        			  </div>
      				</div>
                </div>
             </div>
	        <li class="at40-accordion-row at40-accordion-share" data-type="share">
	            <div class="at40-accordion-icon-and-text">
	                <span class="at40-accordion-icon ui-icon ui-icon-triangle-1-e"></span>
	                <span class="at40-accordion-text">Share</span>
	            </div>
	            <div class="at40-accordion-checkbox-container">
	                <input id="at40-accordion-share-checkbox" class="at40-accordion-checkbox" data-surface="share" type="checkbox" checked="checked">
	            </div>
	            <div class="at40-accordion-hidden">
	            </div>
	        </li>
	        <div class="at40-accordion-hidden at40-accordion-share-hidden">
	            <div class="at40-accordion-hidden-inner">
	                <div class="share-desktop-options">
	                  Desktop Options
	                </div>
	                <div class="at40-accordion-radio-label">
	                    <label>Position:</label>
	                </div>
	                <input type="radio" name="position" id="left-share-position" checked="checked"><label for="left-share-position">Left</label>
	                <input type="radio" name="position" id="right-share-position"><label for="right-share-position">Right</label>
	                <div class="padding-small">
	                <div class="floatLeft share-buttons-label">
	                    <label>Buttons:</label>
	                </div>
	                <select id="at40-accordion-share-buttons">
	                    <option value="1">1</option>
	                    <option value="2">2</option>
	                    <option value="3">3</option>
	                    <option value="4">4</option>
	                    <option value="5" selected>5</option>
	                    <option value="6">6</option>
	                </select>
	                </div>
	            </div>
	        </div>
	        <li class="at40-accordion-row at40-accordion-toaster" data-type="toaster">
	            <div class="at40-accordion-icon-and-text">
	                <span class="at40-accordion-icon ui-icon ui-icon-triangle-1-e"></span>
	                <span class="at40-accordion-text">What's Next</span>
	            </div>
	            <div class="at40-accordion-checkbox-container">
	                <input id="at40-accordion-toaster-checkbox" class="at40-accordion-checkbox" data-surface="toaster" type="checkbox" checked="checked">
	            </div>
	            <div class="at40-accordion-hidden">
	            </div>
	        </li>
	        <div class="at40-accordion-hidden at40-accordion-toaster-hidden">
	            <div class="at40-accordion-hidden-inner">
	                <p class="toaster-text">
	                    When a desktop visitor scrolls down your page, a box will slide in at the bottom to promote the best next step. It will show one action (e.g. share button, follow button, or recommended link).
	                    <br>
	                    <strong>This feature will not appear in mobile browsers.</strong>
	                </p>
	            </div>
	        </div>
	        <li class="at40-accordion-row at40-accordion-trending" data-type="trending">
	            <div class="at40-accordion-icon-and-text">
	                <span class="at40-accordion-icon ui-icon ui-icon-triangle-1-e"></span>
	                <span class="at40-accordion-text">Recommended Content</span>
	            </div>
	            <div class="at40-accordion-checkbox-container">
	                <input id="at40-accordion-trending-checkbox" class="at40-accordion-checkbox" data-surface="trending" type="checkbox" checked="checked">
	            </div>
	            <div class="at40-accordion-hidden">
	            </div>
	        </li>
	        <div class="at40-accordion-hidden at40-accordion-trending-hidden">
	            <div class="at40-accordion-hidden-inner">
	                <div class="at40-accordion-radio-label">
	                    <label>Header:</label>
	                </div>
	                <textarea id="at40-accordion-trending-textarea" cols="30" rows="2" placeholder="Please enter a Header" class="at40-accordion-trending-textarea">Recommended for you:</textarea>
	                <p class="recommended-text">
	                  This layer boosts traffic recirculation by promoting your site's most popular trending content. Note, these may take up to 24 hours to appear. For more information, please refer to our <a href="http://support.addthis.com/customer/portal/articles/1216215-what-are-smart-layers" target="_new">Smart Layers FAQ</a>.
	                </p>
	            </div>
	        </div>
	        <li class="at40-accordion-row at40-accordion-more" data-type="more">
	            <div class="at40-accordion-icon-and-text">
	                <span class="at40-accordion-icon ui-icon ui-icon-triangle-1-s"></span>
	                <span class="at40-accordion-text">More Options</span>
	            </div>
	        </li>
	        <div class="at40-accordion-hidden at40-accordion-more-hidden">
	            <div class="at40-accordion-hidden-inner">
	                <div class="floatLeft share-buttons-label">
	                    <label>Theme:</label>
	                </div>
	                <select id="at40-accordion-more-themes">
	                    <option value="transparent">Transparent</option>
	                    <option value="light">Light</option>
	                    <option value="gray">Gray</option>
	                    <option value="dark">Dark</option>
	                </select>
	                 <input type="hidden" id="pub" name="pub" data-anonymous="true" value="xa-520db0574cb912a6">
	            </div>
	        </div>
    	</ul>
		<a class="at40-restore-default-options">Restore default options</a>
  	</div>
    <div class="grid_7">
      <div class="preview-headers">
        <h3 class="helv org preview-header">Preview</h3>
        <div class="btn-group at40-device-type-buttons">
          <button class="btn btn-primary" data-device="desktop">Desktop</button>
          <button class="btn" data-device="tablet">Tablet</button>
          <button class="btn" data-device="phone">Phone</button>
        </div>
      </div>

      <div class="at40-preview mb40" align="center">
        <div id="preview" class="addthis_toolbox">
          <!-- Desktop -->
            <img class="at40-browser-img" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/preview_browser.png';?>" />
            <img class="at40-toaster-img desktop-device" data-previewService="toaster" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/preview_toaster.png';?>" data-toggle="tooltip" data-placement="left" title="" data-original-title="What's Next" />
            <img class="at40-preview-trending-img desktop-device" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/preview_trending.png';?>" />
            <img class="at40-desktop-trending at40-trending-img desktop-device" data-previewService="trending" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/surface_recommended.png';?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Recommended Content" />
            <div class="at40-trending-text animated" data-previewService="trending"></div>
            <div class="at40-sharing-vertical animate-background desktop-device" data-previewService="share" data-toggle="tooltip" data-placement="right" title="" data-original-title="Share Buttons">
                <div class="at40-share-icon at40-share-icon-facebook"></div>
                <div class="at40-share-icon at40-share-icon-twitter"></div>
                <div class="at40-share-icon at40-share-icon-gplus"></div>
                <div class="at40-share-icon at40-share-icon-linkedin"></div>
                <div class="at40-share-icon at40-share-icon-pinterest"></div>
                <div class="at40-share-icon at40-share-icon-email"></div>
                <div class="at40-share-icon at40-share-icon-gmail"></div>
                <div class="at40-share-icon at40-share-icon-email"></div>
                <div class="at40-share-icon at40-share-icon-print"></div>
                <div class="at40-share-icon at40-share-icon-favorite"></div>
                <div class="at40-share-icon at40-share-icon-addthis"></div>
            </div>
            <div class="at40-follow-horizontal animate-background desktop-device" data-previewService="follow" data-toggle="tooltip" data-placement="left" title="" data-original-title="Follow Buttons">
                <div class="at40-follow-icon at40-follow-icon-facebook"></div>
                <div class="at40-follow-icon at40-follow-icon-twitter"></div>
                <div class="at40-follow-icon at40-follow-icon-linkedin"></div>
                <div class="at40-follow-icon at40-follow-icon-linkedin-company"></div>
                <div class="at40-follow-icon at40-follow-icon-gplus"></div>
                <div class="at40-follow-icon at40-follow-icon-youtube"></div>
                <div class="at40-follow-icon at40-follow-icon-flickr"></div>
                <div class="at40-follow-icon at40-follow-icon-vimeo"></div>
                <div class="at40-follow-icon at40-follow-icon-pinterest"></div>
                <div class="at40-follow-icon at40-follow-icon-instagram"></div>
                <div class="at40-follow-icon at40-follow-icon-foursquare"></div>
                <div class="at40-follow-icon at40-follow-icon-tumblr"></div>
                <div class="at40-follow-icon at40-follow-icon-rss"></div>
            </div>
            <!-- Tablet Preview -->
            <img class="at40-tablet-img hidden-off-screen" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/preview_tablet.png';?>" />
            <img class="at40-tablet-background tablet-device animated smartlayers-hidden" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/preview_phone_background.png';?>">
            <img class="at40-tablet-share tablet-device smartlayers-hidden" data-previewService="share" data-toggle="tooltip" data-placement="right" title="" data-original-title="Share Button" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/half_tablet_transparent_share.png';?>">
            <div class="at40-tablet-follow-divider transparent-divider tablet-device smartlayers-hidden"></div>
            <div class="at40-tablet-share-divider transparent-divider tablet-device smartlayers-hidden"></div>
            <img class="at40-tablet-follow tablet-device smartlayers-hidden" data-previewService="follow" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/half_tablet_transparent_follow.png';?>" data-toggle="tooltip" data-placement="left" title="" data-original-title="Follow Button">
            <img class="at40-tablet-trending tablet-device smartlayers-hidden" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/tablet_preview_recommended.png';?>" data-previewService="trending" data-toggle="tooltip" data-placement="top" title="" data-original-title="Recommended Content">
            <img class="at40-tablet-arrow tablet-device smartlayers-hidden" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/tablet_tab_transparent.png';?>">
            <!-- Phone Preview -->
            <img class="at40-phone-img hidden-off-screen" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/preview_phone.png';?>" />
            <img class="at40-phone-trending phone-device smartlayers-hidden" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/preview_phone_recommended.png';?>" data-previewService="trending" data-toggle="tooltip" data-placement="left" title="" data-original-title="Recommended Content">
            <img class="at40-phone-share phone-device smartlayers-hidden" data-previewService="share" data-toggle="tooltip" data-placement="right" title="" data-original-title="Share Button" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/half_mobile_transparent_share.png';?>">
            <div class="at40-phone-follow-divider transparent-divider phone-device smartlayers-hidden"></div>
            <div class="at40-phone-share-divider transparent-divider phone-device smartlayers-hidden"></div>
            <img class="at40-phone-follow phone-device smartlayers-hidden" data-previewService="follow" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/half_mobile_transparent_follow.png';?>" data-toggle="tooltip" data-placement="left" title="" data-original-title="Follow Button">
            <img class="at40-phone-arrow phone-device smartlayers-hidden" src="<?php echo plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/img/mobile_tab_transparent.png';?>">
        </div>
        <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js"></script>
      </div>
    </div>
    <?php } //Displayed only if pro user ?>
	</div>
	<input type="hidden" name="save_smart_layer" value="save_smart_layer" />
	<input type="hidden" name="saved_settings" value="<?php get_option('smart_layer_settings');?>"/>
	<input type="button" name="Submit" value="<?php _e('Save Changes') ?>" class="save-profile button-primary button" style="float:left; clear:both;"/>
	</form>
</div>
<!-- profile data -->
<div class='clear'>&nbsp;</div>
<!-- dialog box -->
<div class="smart-layer-dialog" style="display:none">
    <p>Are you sure you want to specify a custom API configuration?  This will override any configuration you specified through the WYSIWYG editor.</p>
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
</div>
<script type="text/javascript">
    var currentPage = 'smart-layers',
    	changes = false;

    $ = jQuery;
    $('.ml5').change(function() { 
    	changes = true;
    });
    $('#at40-accordion-more-themesSelectBoxItText').change(function() {
		changes = true;
    });
    $('#at40-accordion-share-buttonsSelectBoxIt').change(function() {
		changes = true;
    });
    $('input[name="position"]').change(function() {
		changes = true;
    });
    $('.at40-accordion-checkbox-container').click(function(){
   		changes = true;
   	});
   	$('.at40-restore-default-options').click(function() {
		changes = true;
   	});
    $('.save-profile, .smart-layer-trigger, .smart-layer-dialog-ok').click(function() {
		changes = false;
    });

	$(window).on('beforeunload', function(){
		if (changes) return "You have unsaved changes that will be lost if you leave this page.";
	});

</script>
<script id="generated-code" type="text/template">
<@ if(shareIsTurnedOn || followIsTurnedOn || toasterIsTurnedOn || trendingIsTurnedOn) { @>
{
    'theme' : '<@= theme @>',<@ if(shareIsTurnedOn) { @>
    'share' : {
        'position' : '<@= sharePosition @>',
        'numPreferredServices' : <@= numPreferredServices @>
    }<@ if(followIsTurnedOn || toasterIsTurnedOn || trendingIsTurnedOn) { @>,<@ } @><@ } @> <@ if(followIsTurnedOn) { @>
    'follow' : {
        'services' : <@= followServices @>
    }<@ if(toasterIsTurnedOn || trendingIsTurnedOn) { @>,<@ } @> <@ }@> <@ if(toasterIsTurnedOn) { @>
    'whatsnext' : {}<@ if(trendingIsTurnedOn) { @>,<@ } @> <@ }@> <@ if(trendingIsTurnedOn) { @>
    'recommended' : { <@ if (trendingLabel != "Recommended for you:") { @>'title': '<@= trendingLabel @>'<@ } @>
    } <@ }@>
}
<@ } else { @> {} <@ } @>
</script>