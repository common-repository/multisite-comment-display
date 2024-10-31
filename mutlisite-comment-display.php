<?php
/*
Plugin Name: Multisite Comment Display
Plugin URI: http://cellarweb.com/wordpress-plugins/
Description: Displays all subsite comment, allows editing of comment, accessed via shortcodes
Version: 1.10
Tested up to: 4.8.1
Requires at least: 4.6
Requires PHP: 5.3
Author: Rick Hellewell - CellarWeb.com
Author URI: http://CellarWeb.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

*/

/*
Copyright (c) 2016-2017 by Rick Hellewell and CellarWeb.com
All Rights Reserved


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
// ----------------------------------------------------------------
// ----------------------------------------------------------------
global $mcd_version;
$mcd_version = "1.10 (30-Aug-2017)";
global $atts;		// used for the shortcode parameters
if ( !mcd_is_requirements_met())
{
	add_action('admin_init', 'mcd_disable_plugin') ;
	add_action('admin_notices', 'mcd_show_notice') ;
	add_action('network_admin_init', 'mcd_disable_plugin') ;
	add_action('network_admin_notices', 'mcd_show_notice') ;
	mcd_deregister() ;
	return ;
}

	add_filter( 'comment_edit_redirect', 'mcd_return_link' , 6, 2);

// Add settings link on plugin page
function mcd_settings_link($links) {
	$settings_link = '<a href="options-general.php?page=mcd_settings" title="Multisite Comment Display">Multisite Comment Display Info/Usage</a>' ;
	array_unshift($links, $settings_link) ;
	return $links ;
}
$plugin = plugin_basename(__FILE__) ;
add_filter("plugin_action_links_$plugin", 'mcd_settings_link') ;

//	build the class for all of this
class mcd_Settings_Page {
	
// start your engines!
	public function __construct() {
		add_action( 'admin_menu', array($this, 'mcd_add_plugin_page')) ;
	}
	
// add options page
	public function mcd_add_plugin_page() {
// This page will be under "Settings"
		add_options_page( 'Multisite Comment Display Info/Usage', 'Multisite Comment Display Info/Usage', 'manage_options', 'mcd_settings', array($this, 'mcd_create_admin_page')) ;
	}
	
// options page callback
	public function mcd_create_admin_page() {
	// Set class property
		$this->options = get_option('mcd_options') ;
	echo '<div class="wrap">';
	 mcd_info_top() ;
			mcd_info_bottom() ; 	// display bottom info stuff
		echo '</div>';
	}
	
// print the Section text
	public function mcd_print_section_info() {
		print '<h3><strong>Information about Multisite Comment Display from CellarWeb.com</strong></h3>' ;
	}
}
// end of the class stuff
if ( is_admin()) {
	$my_settings_page = new mcd_Settings_Page() ;
	// ----------------------------------------------------------------------------
	// supporting functions
	// ----------------------------------------------------------------------------
	//	display the top info part of the page
	// ----------------------------------------------------------------------------
	function mcd_info_top() {
		global $mcd_version;
		?>

<div class="wrap" >
	<h2></h2>
	<!-- empty area for any WP status areas -->
	<hr />
	<div style="background-color:#9FE8FF;padding-left:15px;padding-bottom:10px;margin-bottom:15px;"> <br />
		<h1 align="center" style="font-size:300%"><strong>Multisite Comment Display</strong></h1>
		<h3 align="center">Display/Edit All Multisite's Comment on a Page or Post</h3>
		<p>Version <?php echo $mcd_version; ?></p>
	</div>
	<hr />
	<div style="padding-left:15px;>"
	
	<p><strong>Multisite Comment Display</strong> allows you to use shortcodes to display all comments in a multisite system. It will show all subsite's comments on one screen, so you don't have to switch to each site to look at that site's comments. This is great for monitoring all the comments on all subsites. The plugin also works on standalone sites.</p>
	<p>You can also, if you are the super-admin, edit any subsite's comment. Clicking on an image will open the comment editor screen, where you can rotate the picture or change attributes like the caption.</p>
	<p>Comments are displayed  in rows of comments, grouped by subsite, in reverse (newest) order. </p>
	<h3><strong>Comment is displayed on post or page via a shortcode</strong></h3>
	<ul style="list-style-type: disc; list-style-position: inside;padding-left:12px;">
		<li>Use <strong>[mcd_display]</strong> to display comments (no editing). This shortcode can be used by sub-sites to display only their comment.</li>
		<li>Use <strong>[mcd_edit]</strong> to edit comments (only SuperAdmin users). This is best used on the main site, on a private page, although it can be used on a subsite. Non-SuperAdmin users will not see any comments. That page will check if you are logged in as the Admin, and will display a 'Not Authorized' message if you are not. </li>
		<li>After editing a comment, you are returned to that site's Comment List screen, so you can edit additional comments on that site. When done, just close that window. Refresh the Comment List page (the page you made with the shortcodes) to see the current comments.</li>
	</ul>
	<h3>There are shortcode options/parameters for:</h3>
	<ul style="list-style-type: disc; list-style-position: inside;padding-left:12px;">
		<li><strong>days=4</strong> show only the last 4 days (default all dates, option used will be shown above each sites' picture group.)</li>
		<li><strong>items=10</strong> show only the last 10 items (default all items, option used will be shown above each sites' picture group.)</li>
		<li><strong>debug=yes</strong> Debugging mode: shows the SQL query, plus the number of records found in the query. Not normally used in production, but helpful when you get strange results. (As of version 1.40)</li>
	</ul>
	<p>The parameters can be combined, as in <strong>[mcd_display days=4 items=10]</strong> or <strong>[mcd_edit days=4 items=10]</strong>. The days and items options will be shown above the comments: 'Showing last 4 days, last 10 comments.' All parameters should be in lower-case.</p>
	<p><strong>Known issue:</strong> If the plugin is not enabled on a subsite, then using the 'Update' button on the Comment edit screen will reload the shortcode-created page listing the comments. If the plugin is enabled on the subsite, then you'll be returned to the Admin Comment list screen. Since the edit links on the shortcode-created comment list open a new tab/window, you might end up with multiple tabs/windows of the shortcode-created comment list page. This can't be changed, because the links switch to the subsite containing the comment. And if that subsite doesn't have the plugin enabled, then the 'return page' can't be set by the plugin. Sort of a 'circular thing'...</p>
	<hr />
</div>
<hr />
<p><strong>Tell us how the Multisite Comment Display plugin works for you - leave a <a href="https://wordpress.org/support/view/plugin-reviews/multisite-comment-display" title="Multisite Comment Display Reviews" target="_blank" >review or rating</a> on our plugin page.&nbsp;&nbsp;&nbsp;<a href="https://wordpress.org/support/plugin/multisite-comment-display" title="Help or Questions" target="_blank">Get Help or Ask Questions here</a>.</strong></p>
<hr />
<div style="background-color:#9FE8FF;padding:3px 8px 3px 8px;">
	<p><strong>Interested in a plugin that will automatically add your Amazon Affiliate code to any Amazon links?&nbsp;&nbsp;&nbsp;Check out our nifty <a href="https://wordpress.org/plugins/amazolinkenator/" target="_blank">AmazoLinkenator</a>!&nbsp;&nbsp;It will probably increase your Amazon Affiliate revenue!</strong></p>
	<p>How about <strong>Multisite Media Display</strong> to show all media from all subsites? Or <strong>Multisite Post Display</strong> to show all posts from all subsites? Or our <strong>URL Smasher</strong> which automatically shortens URLs in pages/posts/comments? Just search for them in the Add Plugins screen - they are all free and fully featured!</p>
	<p>New plugin: <a href="https://wordpress.org/plugins/formspammertrap-for-contact-form-7/" target="_blank">FormSpammerTrap for Contact Form 7</a> . Uses the <a href="https://en-au.wordpress.org/plugins/formspammertrap-for-comments/" target="_blank">FormSpammerTrap for Comments</a> techniques to block bot spam on forms that use the Contact Form 7 plugin. See our www.<a href="http://formspammertrap.com" target="_blank">FormSpammerTrap.com sit</a>e for more info.</p>
</div>
</div>

<?php
	}
	
	// ----------------------------------------------------------------------------
	// display the copyright info part of the admin  page
	// ----------------------------------------------------------------------------
	function mcd_info_bottom() {
	// print copyright with current year, never needs updating
		$xstartyear = "2016" ;
		$xname = "Rick Hellewell" ;
		$xcompanylink1 = ' <a href="http://CellarWeb.com" title="CellarWeb" >CellarWeb.com</a>' ;
		echo '<hr><div style="background-color:#9FE8FF;padding-left:15px;padding:10px 0 10px 0;margin:15px 0 15px 0;">
<p align="center"><strong>Copyright &copy; ' . $xstartyear . '  - ' . date("Y") . ' by ' . $xname . ' and ' . $xcompanylink1 ;
		echo ' , All Rights Reserved. Released under GPL2 license.</strong></p></div><hr>' ;
		return ;
	}
	// end  copyright ---------------------------------------------------------
	
	// ----------------------------------------------------------------------------
	// ``end of admin area
	//here's the closing bracket for the is_admin thing
}
	// ----------------------------------------------------------------------------

	// register/deregister/uninstall hooks
	
	register_activation_hook( __FILE__, 'mcd_register' );
	register_deactivation_hook( __FILE__, 'mcd_deregister' );
	register_uninstall_hook(__FILE__, 'mcd_uninstall');	
	
	// register/deregister/uninstall options (even though there aren't options)
	function mcd_register() {
		return;
	}
	function mcd_deregister() {
		return;
	}
	
	function mcd_uninstall() {
	return;
	}
	//  ----------------------------------------------------------------------------
	// set up shortcodes
	
	function mcd_shortcodes_init()
		{
			add_shortcode('mcd_display', 'mcd_comment_display');
			add_shortcode('mcd-display', 'mcd_comment_display');
			add_shortcode('mcd_edit', 'mcd_comment_edit');
			add_shortcode('mcd-edit', 'mcd_comment_edit');
		}
	
	add_action('init', 'mcd_shortcodes_init');
	// ----------------------------------------------------------------------------
	// here's where we do the work!
	// ----------------------------------------------------------------------------

	function mcd_comment_display($atts = array()) {
	// ----------------------------------------------------------------
		
		//dump_filters();
		if (is_array($atts)) {
			echo "<hr>Showing: " ;
			if(($atts[days])) {echo "Last $atts[days] days.&nbsp;"; }
			//if (count($atts[items]) > 1) {echo ", ";} else {echo ".&nbsp;";}
			if (($atts[items])) {echo "Last $atts[items] comments."; }
		}
		echo "<hr>";
		// display only multisite comment 
		add_action('wp_enqueue_style', 'mcd_site_gallery_css'); 	// properly adds the css code in the above function
		mcd_get_sites_array($atts); 		// get the sites array, and loop through them in that function
		return;
	}
	
	// ----------------------------------------------------------------
	function mcd_comment_edit($atts = array()) {
		if (! is_super_admin()) {
			echo "<hr><strong>Sorry, you must be a site administrator to view this page.</strong><hr>";
			return;
		}
		if (is_array($atts)) {
			echo "<hr>Showing " ;
			if(($atts[days])) {echo "last $atts[days] days"; }
			if (count($atts[items]) > 1) {echo ", ";} else {echo ". ";}
			if (($atts[items])) {echo "last $atts[items] comments."; }
		}
		mcd_get_sites_array($atts,1); 		// get the sites array, and loop through them in that function
		return;
	}
	
// ----------------------------------------------------------------
// show comment on all multisite sub-sites. 

// ===============================================================================
//	functions to display all comment files
// ===============================================================================
/*
	 Styles and code 'functionated' for displaying all comment files 
		adapted from http://alijafarian.com/responsive-image-grids-using-css/
		*/	
// --------------------------------------------------------------------------------
// need to adjust for wp_get_sites deprecated in 4.6 for get_sites
function mcd_get_sites_array($atts, $xedit=0) {
	// needed since wp_get_sites deprecated as of 4.6+, but can't use replacement get_sites in < 4.6
	global $wp_version;

	// WordPress 4.6
		$subsites_object = get_sites();
		$subsites = mcd_objectToArray($subsites_object);
		foreach( $subsites as $subsite ) {
			  $subsite_id = $subsite ["blog_id"];
			  $subsite_name = get_blog_details($subsite_id)->blogname;
			  $subsite_path = $subsite[path];
			  $subsite_domain = $subsite[domain];
			switch_to_blog( $subsite_id );
			echo "<hr>Site:<strong> $subsite_id - $subsite_name</strong> ;   Path: <strong>$subsite_path</strong><hr>";
			$xsiteurl = $subsite_domain . $subsite_path;
			$xsitepath = $subsite_path;
			mcd_site_list_comments($xedit, $xsiteurl,$xsitepath, $atts, $subsite['blog_id']);	// '1' parameter to allow edit; second parameter for the site id
			restore_current_blog();
		}
		
return ;		// return empty array due to fail
}




// ----------------------------------------------------------------
//	 list all comment on all multisite sites
// 		inspired by https://wisdmlabs.com/blog/how-to-list-posts-from-all-blogs-on-wordpress-multisite/
/* -----------------------------------------------------------------*/
// display comments of current site

function mcd_site_list_comments($xedit=0, $xsiteurl="", $xsitepath="", $atts = "", $xblogid) {
	global $post;
	//global $atts;
	// mcd_show_array($atts);
	if (isset($atts[items])) {$items = $atts[items];} 
	if (isset($atts[days])) {$days = $atts[days]; }
	mcd_site_gallery_css() ;  
	if ($days) {$daystring = "$days day(s) ago";}		// optional parameter
	// getting all the comments via a query
	// Arguments for the query
		$args = array(
			'posts_per_page' => -1,
			'date_query' => (isset($daystring) ? array(array('after' => $daystring,  // or '-2 days'
				'inclusive' => true,)) : null),
			'number' =>(isset($items) ?  $items : null),
		);

	// The comment query
	$comments_query = new WP_Comment_Query($args);

	// mcd_show_array($args);
	$comments = $comments_query->query( $args );
	if ( isset ($atts[debug]))
	{
		echo "<hr><strong>Debug Info</strong><div style=\"margin:0 20px 0 20px;\"><strong>SQL = </strong>" . $comments_query->request . "<br>" ;
		$comments_query->store_result() ;
		$records_found = $comments_query->post_count ;
		echo "<strong>Found:</strong> " . $records_found . " records<br></div><strong>End Debug</strong><hr>" ;
	}
// The comment loop
if ( !empty( $comments ) ) {
    foreach ( $comments as $comment ) {
        echo '<hr><em>';
		echo $comment->comment_author  . ' - ' . $comment->comment_author_email . ' - ' . $comment->comment_date . "</em>  ";
		// shows comment status if super_admin
		if (is_super_admin()) {
			switch ($comment->comment_approved) {
				case "0":
					echo "(pending) ";
					break;
				case "1":
					echo "(OK) ";
					break;
				case "2":
					echo "(spam) ";
					break;
			}
			// add the edit url
			
			$xlink = get_edit_comment_link($comment->comment_ID) ;
			echo  '&nbsp;&nbsp;<a href="' . $xlink . '" target="_blank" title="Edit Comment" class="dashicons dashicons-edit"></a>&nbsp;&nbsp;';

		}
		echo "<br>";
		echo '<div class="mcd_indent_comment">';
		echo $comment->comment_content ;
		echo '</div>';
	}
} else {
    echo 'No comments found.';
}
echo "<hr>";
return;
}
// ----------------------------------------------------------------
function mcd_return_link( $location, $comment_id) {
	$xreturnlink = "edit-comments.php";
	return "edit-comments.php";
}

// ----------------------------------------------------------------
function mcd_objectToArray ($object) {		// convert object to array, required for get_sites() loop
		if(!is_object($object) && !is_array($object)) return $object;

return array_map('mcd_objectToArray', (array) $object);
}
// ----------------------------------------------------------------
// CSS code 'functionated'
		
function mcd_site_gallery_css() {
?>
<style type="text/css">
.mcd_indent_comment {
	padding-left:10px;
}
a:visited {border:none !important; outline:none !important}

</style>
<?
	return;
	}


// ===============================================================================
//	end functions to display all comment files
// ===============================================================================

// ----------------------------------------------------------------------------
// debugging function to show array values nicely formatted
function mcd_show_array( $xarray = array()) {
	echo "<pre>"; print_r($xarray);echo "</pre>";
	return;
}

// check if at least WP 4.6
// based on https://www.sitepoint.com/preventing-wordpress-plugin-incompatibilities/
function mcd_is_requirements_met()
{
	$min_wp = '4.6' ;
	$min_php = '5.3' ;
	// Check for WordPress version
	if ( version_compare( get_bloginfo('version'), $min_wp, '<' ))
	{
		return false ;
	}
	// Check the PHP version
	if ( version_compare(PHP_VERSION, $min_php, '<'))
	{
		return false ;
	}
	return true ;
}

function mcd_disable_plugin()
{
//    if ( current_user_can('activate_plugins') && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
	if ( is_plugin_active( plugin_basename(__FILE__)))
	{
		deactivate_plugins( plugin_basename(__FILE__)) ;
		// Hide the default "Plugin activated" notice
		if ( isset ($_GET['activate']))
		{
			unset ($_GET['activate']) ;
		}
	}
}

function mcd_show_notice()
{
	echo '<div class="notice notice-error is-dismissible"><p><strong>Multisite Post Reader</strong> cannot be activated - requires at least WordPress 4.6 and PHP 5.3.&nbsp;&nbsp;&nbsp;Plugin automatically deactivated.</p></div>' ;
	return ;
}

// ----------------------------------------------------------------------------
// all done!
// ----------------------------------------------------------------------------
