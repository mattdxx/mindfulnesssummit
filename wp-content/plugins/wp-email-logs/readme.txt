=== WP Email Logs Plugin ===
Contributors: aheadzen
Tags: wp_email, email log
Requires at least : 3.0.0
Tested up to: 4.0
Stable tag: 1.0.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display list of all sent email logs of your wordpress site via wordpress function("wp_mail()") function only.

== Description ==

Whenever any email sent via wordpress email function to any one any time, the plugin store the details to database table as logs to display.
The site admin can see the list of all email list from wp-admin > Email Logs (left menu) > List of all email logs.
It display mainly all the email list sent via wordpress funciton "wp_mail()" only.
With each mail detail, it will stored from & to user ids, form & to email address, mail subject, mail content, component, type, send date etc...

<h4>Features :</h4>
<ul>
<li>• Never work for WP Multi-site. </li>
<li>• All email log via wp_mail().</li>
<li>• See all emails on wp-admin any time.</li>
<li>• Filter email by date,components & type</li>
<li>• Component(db table field) is the plugin name or file name ...</li>
<li>• Type(db table field) is the function name which sending the mail</li>
<li>• Localization ready.</li>
</ul>


== Installation ==
1. Unzip and upload plugin folder to your /wp-content/plugins/ directory  OR Go to wp-admin > plugins > Add new Plugin & Upload plugin zip.
2. Go to wp-admin > Plugins(left menu) > Activate the plugin
3. See the plugin option link with plugin description on plugin activation page or directly access from wp-admin > Email Logs (left menu)

== Screenshots ==
1. Plugin Activation
2. Email Logs Listing
3. Email Message Content


== Changelog ==

= 1.0.0 =
* Fresh Public Release.

= 1.0.1 =
* Buddypress functions created problems while buddypress plugin not installed - SOLVED.

