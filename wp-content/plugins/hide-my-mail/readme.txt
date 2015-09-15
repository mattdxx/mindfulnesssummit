=== Hide My Mail ===
Contributors: Patrick de Koning
Author URI: http://hmm.wordpress.pdkwebs.nl
Tags: hide, email address, mail, bots, unicode
Requires at least: 1.1
Tested up to: 4.1
Stable tag: trunk
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide all Email Addresses from bots by displaying it with JavaScript and Unicode.

== Description ==
With Hide My Mail you can easily hide all e-mail addresses from bots. They are being translated into a computer-unreadable format.

Features:
<ul>
<li>Translate normal e-mail addresses to unreadable Javascript Unicode text.</li>
<li>Translate clickable e-mail addresses to unreadable Javascript Unicode text.</li>
</ul>

<a href="http://hmm.wordpress.pdkwebs.nl" target="_blank"><strong>Visit Our Website</strong></a>


== Installation ==
1. Upload the 'hidemymail' folder to the /wp-content/plugins directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.


== Examples ==
1. hmm@wordpress.pdkwebs.nl will be translated into:
<script type='text/javascript'>document.write('\u0068\u006D\u006D','\u0040\u0077\u006F','\u0072\u0064','\u0070\u0072','\u0065','\u0073','\u0073\u002E','\u0070','\u0064','\u006B\u0077\u0065\u0062','\u0073\u002E\u006E','\u006C','');</script>

2. <a href="hmm@wordpress.pdkwebs.nl">hmm@wordpress.pdkwebs.nl</a> will be translated into:
<script type='text/javascript'>document.write('\u003C\u0061','\u0020\u0068','\u0072','\u0065','\u0066','\u003D\u0022','\u0068','\u006D','\u006D\u0040','\u0077','\u006F','\u0072\u0064','\u0070','\u0072','\u0065','\u0073','\u0073\u002E\u0070\u0064\u006B','\u0077','\u0065','\u0062\u0073\u002E','\u006E\u006C','\u0022','\u003E','\u0068','\u006D','\u006D','\u0040','\u0077','\u006F\u0072','\u0064','\u0070\u0072\u0065','\u0073','\u0073\u002E\u0070\u0064\u006B','\u0077\u0065\u0062\u0073','\u002E','\u006E','\u006C\u003C\u002f\u0061','\u003E');</script>


== Frequently Asked Questions ==
Have a question? Email me at hmm@wordpress.pdkwebs.nl


== Changelog ==
= 1.0 =
* Set up basic functionality

= 1.1 =
* Works now also for Widgets and Posts
* Some general bugfixes

= 1.2 =
* Works now also for shortcodes in Pages, Posts and Widgets

== Upgrade Notice ==
= 1.1 =
* Hide My Mail works now also for Widgets and Posts!

= 1.2 =
* Hide My Mail works now also for shortcodes in Pages, Posts and Widgets!
