<?php
/**
 * Plugin Overviews.
 * @package Maps
 * @author Flipper Code <flippercode>
 **/

?>
<div class="container">
<div class="row">
    <div class="col-md-11">
           <h4 class="alert alert-info"> <?php _e( 'How to Use',WPGMP_TEXT_DOMAIN ); ?> </h4>
          <div class="wpgmp-overview">
            <blockquote><?php _e( 'Go through the steps below to create your first map.' ); ?></blockquote>
            <ol>
                <li><?php
				$url = admin_url( 'admin.php?page=wpgmp_form_location' );
				$link = sprintf( wp_kses( __( 'Use our auto suggestion enabled location box to add your location <a href="%s">here</a>. You can add multiple locations.All those locations will be available to choose when you create your map.', 'my-text-domain' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
				echo $link;?>
                </li>
                <li><?php
				$url = admin_url( 'admin.php?page=wpgmp_form_map' );
				$link = sprintf( wp_kses( __( 'Now <a href="%s">click here</a> to create a map. You can create as many maps you want to add. Using shortcode, you can add maps on posts/pages.', WPGMP_TEXT_DOMAIN ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
				echo $link;?>
                </li>
                <li><?php
				$url = admin_url( 'admin.php?page=wpgmp_manage_map' );
				$link = sprintf( wp_kses( __( 'When done with administrative tasks, you can display map on posts/pages using. You can create as many maps you want to add. Using shortcode, you can add maps on posts/pages. Enable map in the widgets section to display in sidebar.', WPGMP_TEXT_DOMAIN ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
				echo $link;?>
                </li>
            </ol>
    </div>
     <div class="col-md-11">
           <h4 class="alert alert-info"> <?php _e( 'Pro Version',WPGMP_TEXT_DOMAIN ); ?> </h4>
          <div class="wpgmp-overview">
            <blockquote><?php _e( 'Pro Edition Features',WPGMP_TEXT_DOMAIN ); ?> <a target="_blank" href="http://codecanyon.net/item/advanced-google-maps-plugin-for-wordpress/5211638">Download Pro Version.</a></blockquote>
            <ol>
<li>Ability to display location title, location category, location latitude, location longitude with location message in the infowindow.</li>
<li>Ability to Sort listing by location, category and address alphabetically in location listing.</li>
<li>Ability to display default Start and End location in directions tab.</li>
<li>Ability to display directions results in KM and MILES.</li>
<li>Ability to google maps styles from https://snazzymaps.com.</li>
<li>Better user experience on time of Choose Locations for maps.</li>
<li>Ability to use External Database or Sources to add markers on google maps using new filter wpgmp_marker_source</li>
<li>Ability to display Featured Image or Custom fields in the infowindow for geo tags using new filter wpgmp_geotags_content</li>
<li>Display Posts/Pages or Custom Post Types on google maps.</li>
<li>Create unlimited maps and display on posts/pages using shortcode or in sidebar using widget.</li>
<li>Add unlimited locations using an easy to use interface for Google Maps.</li>
<li>Customize marker image for each location separately or group wise. Choose from +500 readymade markers or pick your own image.</li>
<li>Display your map perfectly on all devices. Create 100% responsive maps effortlessly.</li>
<li>Add any number of Google maps on pages/posts/sidebars.</li>
<li>Export/Import Features using CSV/JSON/XML or EXCEL.</li>
<li>Ajax based Location Listing.</li>
<li>Searchable Location Listing.</li>
<li>Paginated Location Listing.</li>
<li>Locating Listing Placeholder.</li>
<li>Directions & Route Suggestion</li>
<li>Nearby locations based on user’s current location.</li>
<li>Display multiple coloured routes on google maps.</li>
<li>Display traffic real time conditions and overlays using Traffic Layers.</li>
<li>Add bicycle path information to your maps using the Bicycling Layer.</li>
<li>Display physical maps based on terrain information.</li>
<li>Display photos from Panoramio as a layer to your maps using the Panoramio Layer.</li>
<li>A Cross Browser Compatible plugin. Fully tested on IE8, IE9, IE10 and all major browsers</li>
<li>Enable visual refresh on any Google Map at a button’s click.</li>
<li>No need of any Google API key. Based on API Version 3.</li>
<li>Fully Responsive. Tested on real devices.</li>
<li>Display one infowindow at a time.</li>
<li>Multi-lingual Supported.</li>
<li>Multisite Enabled and ability to activate it network wide.</li>
<li>Define overlays on Google maps via an easy to use interface</li>
<li>Design your own Google map skins easily. Turn ON/OFF roads, places, water area.</li>
<li>Enable marker clusters if you have too many locations. Just activate and the plugin will handle the rest. </li>
<li>Display polygons on Google Maps with options to customize</li>
<li>Display polylines on Google Maps with several customizable options</li>
<li>Modify existing polygons/polylines by making locations draggable</li>
<li>Define KML Layers on Google Maps</li>
<li>Apply 45 Imagery view on Google Maps</li>
<li>Fusion Table Layers</li>
<li>Awesome Shortcodes to add unlimited locations by address or latitude and longitude.</li>
<li>An innovative Quick Locations feature to add locations quickly by click.</li>
<li>Hooks Supported to modify maps,locations,listing on fly. Integrate api’s using hooks to the map.</li>
<li>Display Road Map view. This is the default map type.</li>
<li>Display Google Earth satellite images on just one click.</li>
<li>Display maps in a blend of normal and satellite views.</li>
<li>Display physical maps based on terrain information.</li>
<li>Display Google Maps on sidebars using widget.</li>
<li>Apply awesome google maps design from snazzymaps.com with just copy and paste.</li>
<li>Setup POV Heading and POV Pitch of Street View to customize Street View output of a location.</li>
<li>Integrate GEOJSON in to google maps.</li>
<li>Customize Infowindow Contents with help of Placeholders</li>
<li>Load markers from external database or API sources with help of filters (Hooks).</li>
<li>Ability to display infowindow on mouse click on mouse hover.</li>
</ol>

</div>
</div>
</div>
</div>
</div>

