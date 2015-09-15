=== Front-End Only Users ===
Contributors: Rustaurius, EtoileWebDesign
Donate link: http://www.etoilewebdesign.com/plugin-donations/
Tags: membership, WordPress members, user management, market segmentation, personalization, front-end users, custom field registration, custom redirects, custom registration, custom registration form, custom registration page, custom user profile, customize profile, edit profile, extra user fields, front-end edit profile, front-end login, front-end register, front-end registration, front-end user listing, front-end user registration, profile builder, registration, registration page, user custom fields, user email, user listing, user login, user profile, user profile page, User Registration, user registration form, user-fields, password, profile, email, custom fields, premium content, statistics, analytics, WooCommerce
Requires at least: 3.5.0
Tested up to: 4.3
License: GPLv3
License URI:http://www.gnu.org/licenses/gpl-3.0.html

A customizable plugin lets users sign up to the front end only of your site with shortcodes for registration, login, profile editing forms and more.

== Description ==

<a href='http://www.etoilewebdesign.com/front-end-only-users-demo/'>Front-End Users Demo</a>

This  plugin allows visitors to sign up as users on the front-end on any page of your website. It is completely customizable using CSS and is easily personalized with the use of shortcodes. These shortcodes can be used to insert registration, login, or profile editing forms on any page of your website and to restrict content. Users are created in separate tables so that they have no access to the back-end of your site. Create different fields for members to fill out and customize content based on their profiles. Customize forms with CSS to suit your needs using the Admin panel.
 
Ideal for paid content, membership, dating sites and more!

= Key Features =

* Customizable membership fields
* Pure CSS-styled forms 
* Supports all input types for user fields
* Include different membership levels and restrict content accordingly
* Option to send sign-up emails and to require admin approval of users
* User input-based redirects
* Send user groups to different pages after login
* Personalize the experience of your site with the [user-data] shortcode
* UTF-8 support
* Front end features: registration, login, edit user profile, and account management
* Back end features: add new users, add new fields, email settings and options

= Premium Features =
The premium version includes lots of useful features such as:

* WooCommerce integration: Autofill WooCommerce fields for logged-in users
* Email confirmation: Require users to confirm their e-mail address before they can log in.
* Ability to restrict pages: Gives you the option of restricting pages to groups of users in the sidebar of the page editor.
* Admin Approval of Users: Require users to be approved by an administrator in the WordPress back-end before they can log in.
* User Levels: Ability to create different user levels and to specify a default user level for users to be set to when they register (created on the “Levels” tab)
* Statistics: This feature allows you to gather information about users and how they are using your site.

For a complete list of the plugin shortcodes please go to our FAQs page:
<https://wordpress.org/plugins/front-end-only-users/faq/>

= Additional Languages =

* Russian
* Brazilian Portugese (thanks to Humberto W.)
* German (Thanks to Mikkael G.)

Check out our Frequently Asked Questions here:
<https://wordpress.org/plugins/front-end-only-users/faq/>

Head over to the "Support" forum to report issues or make suggestions:
<https://wordpress.org/support/plugin/front-end-only-users>

For more FEUP videos check out FAQ page!
[youtube https://www.youtube.com/watch?v=3HI8-t8a1wA]


== Installation ==

1. Upload the `front-end-only-users` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place "[register]" on the page where you want your registration form to display
4. Place "[login]", "[logout]" and "[edit-profile]" shortcodes on pages as applicable
5. All four of the shortcodes accept an attribute, 'redirect_page', that lets you send users to a different page after submitting the form

Tutorial Part 1
[youtube http://www.youtube.com/watch?v=9WE1ftZBlPw]

Tutorial Part 2
[youtube http://www.youtube.com/watch?v=viF7-yPY4H4]

--------------------------------------------------------------

- The user registration form can be customized from the admin panel, under the "Front-End Users" tab.
- Content can be restricted to users who are logged in using the [restricted][/restricted] tag. 
- You can further restrict content to a subset of users by adding "field_name" and "field_value" attributes to the restricted shortcode.
- For example, "[restricted field_name='Country' field_value='Canada']This is Canadian content.[/restricted]" would only display the line "This is Canadian content." to those who have put their "Country" as "Canada" on their user profile.
- You can also personalize your site using the [user-data] tag.
- By default the tag will return the user's Username, but can also display any other field (ex. [user-data field_name='First Name'])


== Frequently Asked Questions ==
= What's the complete list of plugin shortcodes? =
* Register Form: [register]
* Login Form: [login]
* Logout Form:[logout]
* Edit Profile Form: [edit-profile]
* Edit Account Information: [account-details]
* Restricted Content: [restricted][/restricted]
* Inserting User Information: [user-data]
* User Search Form: [user-search]
* User List: [user-list]
* Forgot Password: [forgot-password]
= How do I add fields for my users to fill out? =

On the admin page, go to the "Fields" tab.
= How do I redirect based on a user field? =

You need to add the following attributes to your [login] or [register] shortcodes: ‘redirect_field’: the field the redirect is based off of (ex. Gender) and ‘redirect_array_string’: a comma separated list of pages to redirect to (ex. Male => http: //ManPage.com, Female => http: //WomanPage.com)
= How do I display a user's first name on a page? =

You can use the [user-data field_name='First Name'] shortcode, assuming that you called your field "First Name" for a user's first name.
= How do I restrict content to visitors who have logged in? =

Content can be restricted using the [restricted][/restricted] tag. Any content between the opening and closing tags will only be visible to those who are logged in.

= How do I approve an user? =

Click on the user you want to approve to see their details and there should be a radio button at the top of the page to approve the user.

= How do I restrict content based on the privilege levels? =

To restrict content to a certain level(X) your shortcode would be: ‘restricted level='X'’...content’/restricted’
For all levels above "X" level: [restricted minimum_level='X''...content'/restricted]
For all levels below "X" level: [restricted maximum_level='X''...content'/restricted]

= Once a user registers their information, is there a way to redirect them to a page that will have further instructions? =

You can add the attribute ‘redirect_page’ to the register tag to send newly registered users to a new page: [register redirect_page='http://www.example.com']

= When I go on the profile page, I see "You must be logged in to access this page." even though I'm already logged in. How can I fix this? =

Check the “Options” page, and make sure that 'Login Time' isn't blank. If it's blank, then you're only logged in for a second. Anything non-blank and higher than 0 should solve the problem.

= Is it possible to not the show message: "Sorry, this content is only for those whose FIELD is FIELD-value"? =

You can add the attribute [no_message='Yes'] to your shortcode, so it would look something like this: [restricted field_name='Name' field_value='Alex' no_message='Yes''/restricted]
= Is there a way to indicate to users that they are logged in? I know this can be added to a page using 'user-data', but is there a way to add it to the page header? =

You could add the [user-data] tag to your header file and wrap it in restricted tags so that only logged in users can see it.

= I can't seem to find an option that requires a user to confirm his email upon registration. How do I add this feature? =

To add the confirmation link to the email, you need to include the shortcode ‘confirmation-link’ inside the body of your e-mail.

= How do I use the forgot password shortcode? =

You would want to create a separate page with the [forgot-password] shortcode, and then another page with the ‘confirm-forgot-password’ shortcode on it. For the [forgot-password] shortcode, you would then add an attribute ‘reset_email_url’ with a value set to whatever URL you're using for the [confirm-forgot-password] shortcode.

= How do I restrict and redirect a user to the login page when user is not logged in? =

Content can be restricted using the [restricted/restricted] tag. Any content between the opening and closing tags will only be visible to those who are logged in. To redirect a user when the user in not logged in you would want to use the [login redirect_page='url'] shortcode where the url is the login page you want to redirect to.

= How do I customize the style of this plugin? I'd like to change the color of my button. Can you let me know how I can do that? =

You can customize the plugin by adding code to the "Custom CSS" box on the "Options" page. For example, if you want the button to be red you might try adding:

.ewd-feup-submit.pure-button.pure-button-primary {background: red;}

= How do I translate the plugin into my language? =
A great place to start learning about how to translate a plugin is at the link below: <http://premium.wpmudev.org/blog/how-to-translate-a-wordpress-plugin>
Once translated, you'll need to put the translated mo- and po- files directly in the lang folder and make sure they are named properly for your localization.
If you do translate the plugin, other users would love to have access to the files in your language. You can send them to us at Contact@EtoileWebDesign.com, and we’ll be sure they’re included in a future release.

= What features are included in the premium version of the plugin =
* Email confirmation: Require users to confirm their e-mail address before they can log in.
* Admin Approval of Users: Require users to be approved by an administrator in the WordPress back-end before they can log in.
* User Levels: Ability to create different user levels and to specify a default user level for users to be set to when they register (created on the “Levels” tab)
* Statistics: This feature allows you to gather information about users and how they are using your site.

For more questions and support you can post in the support forum:
<https://wordpress.org/support/plugin/front-end-only-users>

Take a look at the plugin documentation:
<http://www.etoilewebdesign.com/wp-content/uploads/2015/04/FrontEndOnlyUserPluginDocument.docx.pdf>


= Videos =

Tutorial Part 1
[youtube https://www.youtube.com/watch?v=9WE1ftZBlPw]

Tutorial Part 2
[youtube https://www.youtube.com/watch?v=viF7-yPY4H4]


== Screenshots ==

1. Sample registration page
2. Example of the edit profile page
3. Page showing user data
4. Example of a restricted page
5. The admin area

== Changelog ==
= 2.2.9 =
- Added a couple new attributes to the [user-list] shortcode
- Changed a few default options for new installs

= 2.2.8 =
- Added a new "Captcha" premium option for registration and forgot-password forms
- Fixed a couple of small errors

= 2.2.7 =
- Small CSS update

= 2.2.6 =
- Added a beta version of a new feature, the One-Click installer, which creates all of the pages needed for the plugin in one click
- Fixed small errors with the forgot-password and confirm-forgot-password shortcodes
- Added in the missing section of the account-details shortcode

= 2.2.5 =
- Small fix for users running older versions of PHP

= 2.2.4 = 
- Added WooCommerce integration to autofill fields for logged in users
- Fixed an error with one of the functions in the PHP user class

= 2.2.3 =
- CSS update that moves the plugin away from using Yahoo's Pure CSS (WARNING: if you're using your own custom CSS with this plugin, the selectors in the shortcodes are being changed)

= 2.2.2 =
- Added a bunch of new attributes for the user-search shortcode
- Fixed errors with user-search
- Fixed errors with user-list
- Fixed a registration error

= 2.2.1 =
- Fixed an error with the "user-profile" shortcode
- Fixed an error where a user's level got reset to the default level when they edited their profile

= 2.2.0 =
- Added an option to send an e-mail to the user once they've been approved
- Added a new shortcode "user-profile" which can be used to display user's profiles
- Updated the "user-list" shortcode to make it possible to display profiles
- Updated the "user-search" shortcode to make it possible to display profiles

= 2.1.4 =
- Fixed an error in the "Statistics" tab

= 2.1.3 =
- Added more summary content to the "Statistics" tab
- Now displaying a table of link clicking activity, if tracking option is activated

= 2.1.2 =
- Added in a new "login-logout-toggle" shortcode
- Added in a new "login-logout-toggle" widget
- Fixed an email on registration error
- Added in event tracking, more options coming soon!

= 2.1.1 =
- Added the ability to import users from a spreadsheet

= 2.1.0 = 
- Added the ability for premium users to restrict access to entire pages

= 2.0.3 =
- Fixed a small display error

= 2.0.2 =
- Fixed a potential error on the Emails page

= 2.0.1 =
- Fixed a potential upgrade error
- Fixed a notice on the Dashboard page

= 2.0.0 =
- Too many changes to list, be careful when upgrading on a live site as there will likely be some un-caught bugs
- Added a "Statistics" tab, to track user statistics
- Added a premium version, which earlier users have complete access to
- Improved e-mail options and settings

= 1.26 =
- Fixed a display error for the options added in version 1.25

= 1.25 =
- Added an option to use e-mails as a username
- Added a new encryption option type
- CAREFUL UPGRADING for those using the plugin in production
- Changed the password reset option

= 1.24 =
- Updated to the latest version of pure.css

= 1.23 =
- Fixes a critical error with the login checking

= 1.22 =
- Added in a forgot password form
- Fields that have "Show in Front End?" set to "No" will no longer display in the "Edit Profile" form

= 1.21 =
- Added the ability to require users to confirm their e-mail before logging in
- Added bulk approval of users
- Added bulk user level setting
- Fixed an error with apostrophes in user fields
- Eliminated a number of PHP notices

= 1.20 =
- Added the ability to export all users to Excel
- Added confirmation before deleting a user
- Added a button to delete all users from the database
- Fixed an error with a missing </div> tag in the account-details shortcode
- Fixed a link error on the dashboard page

= 1.19 =
- Allow a different SMTP username, instead of it needing to be the admin e-mail address

= 1.18 =
- Fixed a number of notice errors

= 1.17 =
- Fixed a registrations e-mail bug
- Fixed the error where being logged in meant you couldn't edit another user in the admin area

= 1.16 =
- Implemented user levels
- Fixed a registration bug
- Added tracking for user login times
- Fixed a bug so that users can be clicked from the dashboard
- Fixed a user page bug which limited the number of users that could be displayed

= 1.15 =
- Added a translation for Brazilian Portugese
- Fixed a compatibility error
- Fixed a spelling mistake

= 1.14 =
- Fixed a registration bug
- Fixed a bug which did stopped admins from being unable to "unapprove" a user

= 1.13 =
- Added "plain_text" as an attribute for the [user-data] tag
- Required fields should now actually be required on register and edit profile forms
- When a user is deleted, all of the associated user fields are now deleted as well

= 1.12 =
- Added the attribute "no_message" to the [logout] shortcode
- Fixed 2 registration errors
- Fixed an error that stopped the [account-details] shortcode from working
- Fixed an error to make translation possible
- Fixed an error where "omitted fields" in the edit profile form were being overwritten as blanks

= 1.11 =
- Fixed a small error with edit-profile
- Added language support
- Added Russian language files
 
= 1.10 = 
- [edit-profile] now accepts the attribute "omit_fields", a comma-separated list of fields to not appear in the edit profile form

= 1.9 = 
- [register] file was edited to remove PHP warning

= 1.8 = 
- Edited a number of files to remove PHP warnings

= 1.6 = 
- Tiny change

= 1.5 =
- Added "sneak peak" attributes to the [restricted] shortcode; you can now set attributes for either sneak_peak_characters or sneak_peak_words within the shortcode
- Added the ability to redirect based on a user field; to use it, see the plugin page

= 1.4 =
- Fixed a naming conflict error

= 1.3 =
- Shortcodes inside of [restricted][/restricted] tags should now work
- Added 3 new methods to the "EWD_FEUP" class to access User_ID, Username and any custom field
- Fixed a bug that prevented e-mail settings from being saved
- Fixed a bug that was causing a conflict with the options of a handful of other plugins

= 1.2 =
- Fixed a database error for new installs

= 1.1 = 
- Fixed an error with sign-up e-mails
- Fixed an error with "Admin Approval"

= 1.0 = 
- Added an "Admin Approval" of users option
- Added "Sign-up Emails" tab, options and message customization
- Added "login_page" attribute to the "restricted" shortcode
- Added an "EWD_FEUP" class, that let's template designers check whether a user is logged in or not
- Added a "file" field type, so admins can have users upload files as one of the fields
- Created a "Custom CSS" option box, so forms can be styled from the admin panel
- Added a "no_message" attribute to the "restricted" shortcode that won't display a message if a user is not logged in
- Created a "[user-list]" shortcode
- Created a "[user-search]" shortcode

= 0.5 = 
- YouTube tutorial videos added
- Fixed redirection bugs
- Fixed date and datetime input fields
- Fixed bug where users could register with the same username
- Fixed two small shortcode bugs

= 0.4 =
- Fixed an admin display bug

= 0.3 =
- Fixed a couple of small bugs

= 0.2 =
- Fixed a number of bugs that made plugin unusable

= 0.1 =
- Initial beta version. Please make comments/suggestions in the "Support" forum.

== Upgrade Notice ==

- The bugs that make plugin impossible to use for most users have been fixed

