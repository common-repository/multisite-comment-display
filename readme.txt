=== Multisite Comment Display ===

Contributors: Rick Hellewell
Donate link: http://cellarweb.com/wordpress-plugins/
Tags: Multisite Comment Display
Requires at least: 4.6
Requires PHP: 5.3
Tested up to: 5.3
Version: 1.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use shortcodes on a page/post to display/edit all comment items on all multisite subsites. 
 
== Description ==

Creates shortcodes to display/edit Comment items from posts across all subsites on a multisite installation. Can also be used on single sites. Shortcode parameters allow selection of last x days, items displayed, number of words of each comment to show. 

== Installation ==

1. Download the zip file, uncompress, then upload to `/wp-content/plugins/` directory. Or download, then upload/install via the Add Plugin page.

1. Activate the plugin through the 'Plugins' menu in WordPress.

1. Usage information in Settings, 'Multisite Comment Display Info/Usage'.

== Frequently Asked Questions ==

= What are the shortcodes used? =

Use the *[mcd_display]* to display the comments on your post/page. Use the *[mcd_edit]* shortcode to display the comments; click on a picture to get to the Media edit screen.

= Where do I use the shortcodes? = 

Just place the shortcode on a post/page. The *[mcd_display*] shortcode can be used on any public or private page/post where the plugin is activated. The *[mcd_edit]* shortcode is only be available to the SuperAdmin, and in fact will not display comments unless the SuperAdmin is logged in. (You don't want non-SuperAdmins to be able to see other subsites media.) We use the shortcodes only on our master site, and on a private page - it's just for our use and convenience.

= How are the comments displayed? = 

The comments are shown in one-column list (depends on the size of the text area on a page/post with your theme). The comment author name and date are shown, along with the status (OK, Pending, Spam). There is some minor CSS to display the comments, but the overall 'look' of the page/post will depend on your theme. Admins will see the edit link pencil. Look at the screenshot for an example.

= Will this work with any theme? =

More than likely. All our CSS to display the comments is contained within a DIV, so theme CSS is not affected.

= Are there any settings? = 

Nope. The 'Info/Usage' (Settings) screen just contains information on the plugin, and the shortcodes used.

= Can I limit the number of comments displayed, or show the last 10 days? = 

Yes. Just use a shortcode similar to [mcd_display days=4 items=10] or [mcd_edit days=5 items=10]. These options will be shown above the comments: 'Showing last 4 days, last 10 comments. 

= Why did you write this? = 

I needed to monitor comments on all sites, but didn't want to get an email every time a comment was entered. And didn't find anyway to do this across all subsites on a multisite installation.

Yes, you can do something similar with the 'Recent Comments' widget, but that will only show comments on the current subsite, not all sites. So the plugin allows you to create a shortcode that you can use on any page/post on any subsite to show all comments from all subsites.

= Are there known issues? = 

If the plugin is not activated on a subsite, the comments are still shown when you display a shortcoded page from an activated site. But editing then updating a comment on that subsite will display another comment list page. Normally you would create the shortcode page on your main site (for editing purposes). But you might want to enable the plugin on all sites.

The plugin uses standard WP syntax, so (assuming your theme does the same) it should work just fine.

= What if I have problems or suggestions? =

Just contact us via the plugin's Support page. Or via www.CellarWeb.com . 

= Do you have other plugins? = 

Yes! 

* **Multisite Media Display** : shows all media on a subsites on a single page. Has similar parameters to show subset of media. SuperAdmins will be able to click on a link to edit the media, so it's great for finding pictures that need to be rotated.

* **Multisite Post Display** : shows all posts on all subsites on a single page; similar parameters to show a subset. SuperAdmins get a link to edit the post.

* **FormSpammerTrap for Comments** : enhances comment forms so that bots can't spam your comments. Uses a more clever technique than just hidden fields or captchas or other things that don't always work. Also lets you change the text/headings of the comment form. (We also have a free standalone version; take a look at www.FormSpammerTrap.com (that's the page that comment bots will see, but also contains all the info about the 'trap').

* **URL Smasher** : automatically shortens URLs on all URLs in pages/posts.

* **AmazoLinkenator** : adds your Amazon Affiliate ID to any Amazon product link in pages/posts/comments. It's your site, so use your Amazon Affiliate ID. 

All plugins are free and full-functioned. No premium features. Just search for them on the Add Plugins page.

== Screenshots ==

1. An example display of comments from a multisite installation.  Shows the first two site's comments, along with the selection criteria on top. 

== Changelog ==

= 1.10 (30 Aug 2017) = 
* Plugin now checks for WP 4.6+, and PHP 5.3+. Plugin will automatically deactivate if those minimum versions are not found.
* removed code using deprecated wp_get_sites function; deprecated as of WP 4.6. 
* The above should fix fatal errors due to older (pre version 4.6) WP versions.
* Added a new 'debug=yes' optional parameter so developers can see the SQL statement used, and the number of records found. Should not be used on 'live' sites. 
* Minor text changes to the instructions/info screen to also include 'debug=yes' optional parameter information
* remove obsolete and testing code

= 1.03 (29 Aug 2017) = 
* fixed bug relating to items and days parameters
* tested for 4.8.1
* tested new install (not upgrade) to ensure activation OK

= 1.02 (28 Aug 2017) = 
* minor code efficiencies

= 1.01 (8 Feb 2017) = 
* fixed bug of missing function for options (function not needed, because no options settings)

= 1.0 (7 Feb 2017) = 
* initial testing / release


== Upgrade Notice ==

= 1.03 (29 Aug 2017) = 
* fixed bug relating to items and days parameters
* tested for 4.8.1
* tested new install (not upgrade) to ensure activation OK

= 1.02 (28 Aug 2017) = 
* minor code efficiencies

= 1.01 (8 Feb 2017) = 
* fixed bug of missing function for options (function not needed, because no options settings)

= 1.0 (7 Feb 2017) = 
* initial testing / release

