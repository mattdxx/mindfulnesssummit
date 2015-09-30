=== WPSSO Pro Update Manager ===
Plugin Name: WPSSO Pro Update Manager (WPSSO UM)
Plugin Slug: wpsso-um
Contributors: jsmoriss
Donate Link: https://wpsso.com/
Tags: wpsso, update, manager
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Requires At Least: 3.1
Tested Up To: 4.3.1
Stable Tag: 1.1.8

Update Manager for the WordPress Social Sharing Optimization (WPSSO) Pro plugin and its extensions.

== Description ==

<p><img src="https://surniaulula.github.io/wpsso-um/assets/icon-256x256.png" width="256" height="256" style="width:33%;min-width:128px;max-width:256px;float:left;margin:0 40px 20px 0;" />The WPSSO Pro Update Manager (WPSSO UM) extension plugin is required to enable and update the <a href="https://wpsso.com/extend/plugins/wpsso/">WordPress Social Sharing Optimization (WPSSO) Pro</a> version, including all its licensed extension plugins.</p>

<p>Simply <em>download</em>, <em>install</em>, and <em>activate</em> the plugin &mdash; there are no plugin settings to review or adjust.</p>

== Installation ==

= Semi-Automated Install =

* [Download the latest plugin archive file](http://wpsso.com/extend/plugins/wpsso-um/latest/)
* Go to the wp-admin/ section of your website
* Select the *Plugins* menu item
* Select the *Add New* sub-menu item
* Click on *Upload Plugin* link (next to the *Add Plugins* page title)
* Click the *Browse...* button
* Navigate your local folders / directories and choose the zip file you downloaded previously
* Click on the *Install Now* button
* Click the *Activate Plugin* link

= Manual Install =

* [Download and unzip the latest plugin archive file](http://wpsso.com/extend/plugins/wpsso-um/latest/)
* Upload the entire wpsso-um/ folder to your website's wordpress/wp-content/plugins/ directory
* Go to the wp-admin/ section of your website
* Select the *Plugins* menu item
* Select the *Installed Plugins* sub-menu
* Scroll down to the "WPSSO Pro Update Manager (WPSSO UM)" plugin, and click its *Activate* link

== Frequently Asked Questions ==

= Frequently Asked Questions =

* *None*

== Other Notes ==

= Additional Documentation =

* *None*

== Screenshots ==

== Changelog ==

= Free / Basic Version Repository =

* [GitHub](https://github.com/SurniaUlula/wpsso-um)

= Version 1.1.8 2015/09/29 =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* *None*
* **Developer Notes**
	* Removed unnecessary current/new comparison before saving plugin update information (current options may have been incorrectly reported as new by some caching solutions).

= Version 1.1.7 (2015/09/23) =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* Fixed an internal cache array pointer for status messages.
	* Changed from multisite to single-site options table to get / save update information.
* **Developer Notes**
	* *None*

= Version 1.1.6 (2015/09/19) =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* *None*
* **Developer Notes**
	* Added a self-deactivation feature when WPSSO UM is activated and WPSSO is missing. 

= Version 1.1.5 (2015/09/09) =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* *None*
* **Developer Notes**
	* Minor code optimization.
	* Added `WpssoUtil::save_time()` calls during activation to save install / activation / update timestamps.

= Version 1.1.4 (2015/09/03) =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* *None*
* **Developer Notes**
	* Updated the tooltip message filter names for WPSSO v3.8.

= Version 1.1.3 (2015/08/29) =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* *None*
* **Developer Notes**
	* Replaced `plugin_dir_path()` by `realpath(dirname())`.
	* Added 'sucom_ua_plugin' and 'sucom_installed_version' as generic fallback filters.

= Version 1.1.2 (2015/06/18) =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* Fixed a possible condition where the Free update information would be returned if/when newer than the cached Pro update information.
* **Developer Notes**
	* *None*

= Version 1.1.1 (2015/05/11) =

* **New Features**
	* *None*
* **Improvements**
	* *None*
* **Bugfixes**
	* Added a check for the Wpsso::get_instance() method, with a fallback to the $wpsso global variable for older WPSSO versions.

= Version 1.1 (2015/04/21) =

* **New Features**
	* *None*
* **Improvements**
	* Replaced self-deactivation by a warning notice if the WPSSO plugin is not found.
	* Added deactivate and uninstall methods to remove the cron schedule and plugin options.
* **Bugfixes**
	* *None*

== Upgrade Notice ==

= 1.1.8 =

2015/09/29 Removed unnecessary current/new comparison before saving plugin update information.

= 1.1.7 =

2015/09/23 Fixed an internal cache array pointer for status messages. Changed from multisite to single-site options table to get / save update information.

= 1.1.6 =

2015/09/19 Added a self-deactivation feature when WPSSO UM is activated and WPSSO is missing. 

