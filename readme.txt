=== Linkedin_Oauth ===
Contributors: k2klettern
Tags: linkedin, linkedin-api, social, loggin, oauth2, button, widget, oauth
Requires at least: 4.0
Tested up to: 4.2.2
Stable tag: 0.1.5
License: GPLv2 or later

Linkedin_Oauth allows users to login/register into your wordpress using their linkedin account, uses shortcodes.

== Description ==

Linkedin_Oauth allows users to login or optional register into your wordpress site using their linkedin account, it will set a login button on your login page and uses a shortcode to put the button wherever you want it to show.

Major features in Linkedin_Oauth include:

* Add a button to the login page of Wordpress.
* Allow to locate the login button wherever user wants with shortcodes.
* Allow to set a redirection URL after login for any page or post.
* Allow to set if user can register with their Linkedin credentials if does not exists on Worpdress or just an error login message.

PS: You'll need to set an id and secret pass on Linkedin developers API, just go to this web site and create an application https://www.linkedin.com/secure/developer.

== Installation ==

Upload the Linkedin_Oauth plugin to your blog, Activate it, then enter your id and Secret and redirect after login URL.

1, 2, 3: You're done!

== Screenshots ==

1. Adds a Button to login your wp-admin
2. You have to set an id and secret pass on Linkedin developers API
3. Provides a back-end to include your id and secret pass also your redirect URL and if user can register with this button
4. Provide a Widget to add a Login Button with or without Title and Short Description

== Changelog ==

= 0.1.0 =
*Release Date - 05th July, 2015*

* First Release

= 0.1.2 =
*Release Date - 20 May, 2015*

* Change of fullprofile request for basicprofile request on the URL call to Linkedin as per new linkedin API rules
* Deleted some image resize function that didn't work in certain servers
* It will not show the button until you enter at least id and secret keys

= 0.1.3 =
*Release Date - 16 Jun, 2015*

*Added .pot file for translations and Spanish language for admin, also Spanish text button

= 0.1.4 =
*Release Date - 10n Jul, 2015*

*Added a Widget to put the login button, provides Title and Description Fields

= 0.1.5 =
*Release Date - 08 Aug, 2015*

*Change functions in order to work with WP4.3