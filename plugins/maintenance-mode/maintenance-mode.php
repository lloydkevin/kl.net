<?php

/*
Plugin Name: Maintenance Mode
Plugin URI: http://sw-guide.de/wordpress/plugins/maintenance-mode/
Description: Adds a splash page to your blog that lets visitors know your blog is down for maintenance. Logged in administrators get full access to the blog including the front-end. Navigate to <a href="admin.php?page=maintenance-mode.php">Options &rarr; Maintenance Mode</a> to get started.
Version: 3.2
Author: Michael Woehrer
Author URI: http://sw-guide.de/
 
    ----------------------------------------------------------------------------
   	      ____________________________________________________
         |                                                    |
         |                 Maintenance Mode                   |
         |____________________________________________________|

	            Copyright © 2006-2007 Michael Woehrer 
	                    <http://sw-guide.de>
                (michael dot woehrer at gmail dot com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License <http://www.gnu.org/licenses/> for 
	more details.

	----------------------------------------------------------------------------

	ACKNOWLEDGEMENTS
	- Thanks to Angsuman Chakraborty (http://blog.taragana.com/) for his plugin
	  "Site Unavailable".
	- Thanks to Frank Bueltge (http://bueltge.de/). He modified/extended the
	  "Site Unavailable" and I took his plugin and extended it. 

	----------------------------------------------------------------------------
*/

################################################################################
# Get options
################################################################################
$mamo_opt = get_option('plugin_maintenancemode2');

if ( !is_array($mamo_opt) ) {
	// Options do not exist or have not yet been loaded so we define standard options
	$mamo_opt = array(
		'mamo_activate' => 'off',
		'mamo_excludepaths' => '',
		'mamo_backtime' => '60',
		'mamo_pagetitle' => 'Maintenance Mode',
		'mamo_pagemsg' => '<h1>Maintenance Mode</h1>' . "\n" . '<p><a title="[blogtitle]" href="[blogurl]">[blogtitle]</a> is currently undergoing scheduled maintenance.<br />' . "\n" . 'Please try back <strong>in [backtime] minutes</strong>.</p>' . "\n" . '<p>Sorry for the inconvenience.</p>' . "\n\n" . '<!-- GERMAN' . "\n" . '<h1>Wartungsmodus</h1>' . "\n" . '<p>Derzeit werden auf <a title="[blogtitle]" href="[blogurl]">[blogtitle]</a> Wartungsarbeiten durchgef&uuml;hrt.<br />Bitte versuchen Sie es <strong>in [backtime] Minuten</strong> nochmal.</p>' . "\n" . '<p>Vielen Dank f&uuml;r Ihr Verst&auml;ndnis.</p>' . "\n" . '-->',
		'mamo_503' => '',
		);
}

################################################################################
# Template Tags for using in themes
################################################################################
# You can display a warning message in the front-end if you are logged in and the Maintenance Mode is activated
# to remember you to deactivate the Maintenance Mode.
function is_maintenance() {
	global $mamo_opt;
	if ( $mamo_opt['mamo_activate'] == 'on' ) {
		return true;
	} else {
		return false;
	}
}



################################################################################
# Apply Maintenance Mode
################################################################################
if(	   !strstr($_SERVER['PHP_SELF'], 'feed/') 
	&& !strstr($_SERVER['PHP_SELF'], 'trackback/')
	&& !is_admin()  
	&& !strstr($_SERVER['PHP_SELF'], 'wp-login.php')
	&& !in_array($_SERVER['REQUEST_URI'], explode(' ', $mamo_opt['mamo_excludepaths']) )
	&& !mw_current_user_can_access_on_maintenance()
	&& ($mamo_opt['mamo_activate'] == 'on')
	) {
		# Apply HTTP header
    	if ($mamo_opt['mamo_503'] == '1') mamo_http_header_unavailable();
		# Display splash page
		include( dirname(__FILE__) . '/maintenance-mode_site.php');
	    exit();    
} elseif( ($mamo_opt['mamo_activate'] == 'on') && (strstr($_SERVER['PHP_SELF'], 'feed/') || strstr($_SERVER['PHP_SELF'], 'trackback/') ) ) {
	# HTTP header for feed and trackback
	mamo_http_header_unavailable(); 
    exit();    
}

################################################################################
# Display information in administration when Maintenance Mode is activated.
################################################################################
if ( is_admin() && ($mamo_opt['mamo_activate'] == 'on') && ($_GET['page'] != 'maintenance-mode.php') )  {
	add_action('admin_notices', 'mamo_display_admin_msg');
}
function mamo_display_admin_msg() { echo '<div class="error"><p>The Maintenance Mode is activated. Please don\'t forget to <a href="admin.php?page=' . basename(__FILE__) . '">deactivate</a> it as soon as you are done.</p></div>'; }




################################################################################
# Apply the admin menu
################################################################################
add_action('admin_menu', 'mamo_add_options_to_admin');



################################################################################
# Checks if the current user can access to front-end on maintenance
################################################################################
function mw_current_user_can_access_on_maintenance() {

	global $wp_version, $mamo_opt;

	// For "wp_get_current_user();". We need to include now since it is by default included AFTER plugins are being loaded.
	// We differentiate between WP versions since as of WP 2.1.x, the file 'pluggable-functions.php' was renamed to 'pluggable.php'
	if ( version_compare($wp_version, '2.1', '<') ) {
		require (ABSPATH . WPINC . '/pluggable-functions.php');		// < WP 2.1
	} else {
		require (ABSPATH . WPINC . '/pluggable.php');				// >= WP 2.1	
	}

	$admin_role = get_role('administrator');
	$admin_caps = $admin_role->capabilities;
	if ( array_key_exists('access_on_maintenance', $admin_caps) ) {
		# Capability for Administrator role does exist, so we don't add or modify it
	} else {
		# Maintenance Capability for Administrator role DOES NOT EXIST, so we add the capability and grant it.
		$admin_role->add_cap('access_on_maintenance', true);
	}

	return current_user_can('access_on_maintenance');
	
}

################################################################################
# Add admin menu
################################################################################
function mamo_add_options_to_admin() {
    if (function_exists('add_options_page')) {
		add_options_page('Maintenance Mode', 'Maintenance Mode', 8, basename(__FILE__), 'mamo_admin_options');
    }
}


################################################################################
# Plugin Options
################################################################################
function mamo_admin_options() {

	global $wp_version, $mamo_opt;

	add_option('plugin_maintenancemode2', $mamo_opt, 'Maintenance Mode Plugin Options');

	/* Check form submission and update options if no error occurred */
	if (isset($_POST['submit']) ) {
		$mamo_opt_update = array (
			'mamo_activate' => $_POST['mamo_activate'],
			'mamo_excludepaths' => mamo_linebreak_to_whitespace($_POST['mamo_excludepaths']),
			'mamo_backtime' => $_POST['mamo_backtime'],
			'mamo_pagetitle' => $_POST['mamo_pagetitle'],
			'mamo_pagemsg' => $_POST['mamo_pagemsg'],
			'mamo_applyaltlang' => $_POST['mamo_applyaltlang'],
			'mamo_langalttitle' => $_POST['mamo_langalttitle'],
			'mamo_langaltmessage' => $_POST['mamo_langaltmessage'],
			'mamo_503' => $_POST['mamo_503'],
		);
		update_option('plugin_maintenancemode2', $mamo_opt_update);
	}

	/* Get options */
	$mamo_opt = get_option('plugin_maintenancemode2');


	
?>


	<style type="text/css">
		table#outer { width: 100%; border: 0 none; padding:0; margin:0; }
		table#outer td.left, table#outer td.right { vertical-align:top; }
		table#outer td.left {  padding: 0 10px 0 0; }
		table#outer td.right { width: 200px; padding: 0 0 0 10px; }
		.right a { background: no-repeat; padding-left: 20px; border: 0 none; }
		.right a.lhome { background-image:url(<?php echo mamo_get_resource_url('sw-guide.png'); ?>); }
		.right a.lpaypal { background-image:url(<?php echo mamo_get_resource_url('paypal.png'); ?>); }
		.right a.lamazon { background-image:url(<?php echo mamo_get_resource_url('amazon.png'); ?>); }
		.right a.lwp { background-image:url(<?php echo mamo_get_resource_url('wp.png'); ?>); }
		td.right dl { border: 1px solid #f4f4f4; margin:0 0 20px 0; padding: 1px; }  /* Box */
		td.right dt { background-color: #247fab; color: white; display:block; margin:0; padding:2px 5px; }  /* Title */
		td.right dd { display:block; margin:0; padding:5px 10px; }  /* Content */
		td.right dd ul, td.right dd ul li { list-style: none; margin:0; padding:0; background: 0 none; }
		td.right dd ul li { padding:3px 0;  }
		td.right dd p { margin: 0; padding:0; }
		td.right dd p.donate { font-size:90%; }
	</style>

	<div class="wrap">

	<h2>Maintenance Mode Options</h2>

	<table id="outer"><tr><td class="left">
	<!-- *********************** BEGIN: Main Content ******************* -->

			
	<?php if (version_compare($wp_version, '2.0.9', '<')) echo '<p style="color: red; font-weight: bold">You are using an outdated Wordpress version which is not supported by this plugin. Get the latest version at <a href="http://wordpress.org/download/">wordpress.org</a>.</p>'; ?>

	<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . basename(__FILE__); ?>&updated=true">

	<fieldset class="options"> 
		<legend>Activate/Deactivate Maintenance Mode</legend>

		<table border="0"><tr>
			<td width="150">
				<p style="margin-left: 25px; font-weight: bold;">
					<input id="radioa1" type="radio" name="mamo_activate" value="on" <?php echo ($mamo_opt['mamo_activate']=='on'?'checked="checked"':'') ?> />
					<label for="radioa1">Activated</label>
					<br />					
					<input id="radioa2" type="radio" name="mamo_activate" value="off" <?php echo ($mamo_opt['mamo_activate']!='on'?'checked="checked"':'') ?> />
					<label for="radioa2">Deactivated</label>
				</p>				
			</td>
			<td>
				<div class="submit" style="text-align: left;">
					<input type="submit" name="submit" value="<?php _e('Update Options') ?> &raquo;" />
				</div>			
			</td>
		</tr></table>

		<hr />

		<legend>Backtime</legend>

		<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
		<tr valign="center"> 
			<th width="170px" scope="row"><label for="mamo_backtime">Backtime in minutes:</label></th> 
			<td width="30px"><input name="mamo_backtime" type="text" id="mamo_backtime" value="<?php echo $mamo_opt['mamo_backtime']; ?>" size="3" /></td> 
			<td style="color: #555; font-size: .85em;">A special HTML header for feed and trackback will be applied (&laquo;503 Service Unavailable&raquo;) including "retry after x minutes".
			Enter here the approx. time in minutes to retry. Also, by using the placeholder <strong>[backtime]</strong> below, the time in minutes 
			be displayed to the visitors as well.</td>
		</tr> 
		</table>

		<legend>Message</legend>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
		<tr valign="center"> 
			<th width="170px" scope="row"><label for="mamo_pagetitle">Title:</label></th> 
			<td width="500px"><input name="mamo_pagetitle" type="text" id="mamo_pagetitle" value="<?php echo htmlspecialchars(stripslashes($mamo_opt['mamo_pagetitle'])); ?>" size="40" /></td>
		</tr>
		<tr valign="top"> 
			<th width="170px" scope="row"><label for="mamo_pagemsg">Message:</label></th> 
			<td width="500px"><textarea style="font-size: 90%" name="mamo_pagemsg" id="mamo_pagemsg" cols="100%" rows="15" ><?php echo htmlspecialchars(stripslashes($mamo_opt['mamo_pagemsg'])); ?></textarea>
			<p style="color: #555; font-size: .85em;">Use HTML only, no PHP allowed. You can use <strong>[blogurl]</strong>, <strong>[blogtitle]</strong> and <strong>[backtime]</strong> as placeholders.</p>
			</td>
		</tr>
		</table>

		<legend>Paths to be still accessable</legend>
		<p style="margin-left: 25px; color: #555; font-size: .85em;">
			Enter paths that shall be excluded and still be accessable. Separate multiple paths with line breaks.
			<br />Example: If you want to exclude <em>http://site.com/about/</em>, then enter <em>/about/</em>
		</p>
		<textarea style="margin-left: 25px" name="mamo_excludepaths" id="mamo_excludepaths" cols="100%" rows="2" ><?php echo mamo_whitespace_to_linebreak($mamo_opt['mamo_excludepaths']); ?></textarea>


		<br /><br /><legend>Miscellaneous Settings</legend>
		<p style="margin-left: 25px;">
			<input name="mamo_503" type="checkbox" id="mamo_503" value="1" <?php checked('1', $mamo_opt['mamo_503']); ?>"  /> 
			<label for="mamo_503">Apply HTTP header "503 Service Unavailable" and "Retry-After &lt;backtime&gt;" to splash page</label>
		</p>

		<br /><br /><legend>Access to the blog's front-end</legend>
		<p style="margin-left: 25px;">When you activate the maintenance mode, it adds a splash page to your blog that lets visitors know your blog is down for maintenance. 
		Logged in administrators get full access to the blog including the front-end.<br />

		This plugin adds the capability "access_on_maintenance" to the role "Administrator". 
		If you now want to achieve, that for example users with the role "Editor" do also get full access to the blog when being logged in,
		use the plugin <a href="http://www.im-web-gefunden.de/wordpress-plugins/role-manager/">Role Manager</a> and grant the capability "Access On Maintenance" for the role "Editor" or for any other role of your choice.
		Check out <a href="http://codex.wordpress.org/Roles_and_Capabilities">WordPress Codex > Roles and Capabilities</a> for further information.
		</p>

	</fieldset>


	<div class="submit">
		<input type="submit" name="submit" value="<?php _e('Update Options') ?> &raquo;" />
	</div>

	</form>
	
	<!-- *********************** END: Main Content ********************* -->
	</td><td class="right">
	<!-- *********************** BEGIN: Sidebar ************************ -->

	<dl>
	<dt>Plugin</dt>
	<dd>
		<ul>
			<li><a class="lhome" href="http://sw-guide.de/wordpress/plugins/maintenance-mode/">Plugin's Homepage</a></li>
			<li><a class="lwp" href="http://wordpress.org/support/">WordPress Support</a></li>
		</ul>			
	</dd>
	</dl>

	<dl>
	<dt>Donation</dt>
	<dd>
		<ul>
			<li><a class="lpaypal" href="http://sw-guide.de/donation/paypal/">Donate via PayPal</a></li>
			<li><a class="lamazon" href="http://sw-guide.de/donation/amazon/">My Amazon Wish List</a></li>
		</ul>			
		<p class="donate">I spend a lot of time on the plugins I've written for WordPress.
		Any donation would by highly appreciated.</p>

	</dd>
	</dl>


	<dl>
	<dt>Miscellaneous</dt>
	<dd>
		<ul>
			<li><a class="lhome" href="http://sw-guide.de/wordpress/plugins/">WP Plugins I've Written</a></li>
		</ul>
	</dd>
	</dl>



		<!-- *********************** END: Sidebar ************************ -->
		</td></tr></table>
	
	
	
	
	<p style="text-align: center; font-size: .85em;">&copy; Copyright 2006-2007&nbsp;&nbsp;<a href="http://sw-guide.de">Michael W&ouml;hrer</a></p>

	</div> <!-- [wrap] -->

<?php


} // mamo_admin_options



################################################################################
# Converts textarea content (separated by line break) to space separated string
# since we want to store it like this in the database
################################################################################
function mamo_linebreak_to_whitespace($input) {

	// Remove white spaces
	$input = str_replace(' ', '', $input);

	// Replace linebreaks with white space, considering both \n and \r
	$input = preg_replace("/\r|\n/s", ' ', $input);

	// Create result. We create an array and loop thru it but do not consider empty values. 
	$sourceArray = explode(' ', $input);
	$loopcount = 0;
	$result = '';
	foreach ($sourceArray as $loopval) {

		if ($loopval <> '') {

			// Create separator
			$sep = '';
			if ($loopcount >= 1) $sep = ' ';
			
			// result
			$result .= $sep . $loopval;
		
			$loopcount++;				
		}
	}
	return $result;

}

################################################################################
# Replace white space with new line for displaying in text area
################################################################################
function mamo_whitespace_to_linebreak($input) {

	$output = str_replace(' ', "\n", $input);
	
	return $output;

}

################################################################################
# Apply HTTP header
################################################################################
function mamo_http_header_unavailable() {
	global $mamo_opt;

   	header('HTTP/1.0 503 Service Unavailable');

	$backtime = intval($mamo_opt['mamo_backtime']);
	if ( $backtime > 1 ) {
    	# Apply return-after only if value > 0. Also, intval returns 0 on failure; empty arrays and objects return 0, non-empty arrays and objects return 1
		header('Retry-After: ' . $backtime * 60 );
	}

}



################################################################################
# Icons
################################################################################
if( isset($_GET['resource']) && !empty($_GET['resource'])) {
	# base64 encoding performed by base64img.php from http://php.holtsmark.no 
	$resources = array(
		'paypal.png' =>
			'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAFfKj/FAAAAB3RJTUUH1wYQEhELx'.
			'x+pjgAAAAlwSFlzAAALEgAACxIB0t1+/AAAAARnQU1BAACxjwv8YQUAAAAnUExURZ'.
			'wMDOfv787W3tbe55y1xgAxY/f39////73O1oSctXOUrZSlva29zmehiRYAAAABdFJ'.
			'OUwBA5thmAAAAdElEQVR42m1O0RLAIAgyG1Gr///eYbXrbjceFAkxM4GzwAyse5qg'.
			'qEcB5gyhB+kESwi8cYfgnu2DMEcfFDDNwCakR06T4uq5cK0n9xOQPXByE3JEpYG2h'.
			'KYgHdnxZgUeglxjCV1vihx4N1BluM6JC+8v//EAp9gC4zRZsZgAAAAASUVORK5CYI'.
			'I=',
		'amazon.png' => 
			'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAFfKj/FAAAAB3RJTUUH1wYQESUI5'.
			'3q1mgAAAAlwSFlzAAALEgAACxIB0t1+/AAAAARnQU1BAACxjwv8YQUAAABgUExURe'.
			'rBhcOLOqB1OX1gOE5DNjc1NYKBgfGnPNqZO4hnOEM8NWZSN86SO1pKNnFZN7eDOuW'.
			'gPJRuOVBOTpuamo+NjURCQubm5v///9rZ2WloaKinp11bW3Z0dPPy8srKyrSzs09b'.
			'naIAAACiSURBVHjaTY3ZFoMgDAUDchuruFIN1qX//5eNYJc85EyG5EIBBNACEibsi'.
			'mi5UaUURJtI5wm+KwgSJflVkOFscBUTM1vgrmacThfomGVLO9MhIYFsF8wyx6Jnl8'.
			'8HUxEay+wYmlM6oNKcNYrIC58iHMcIyQlZRNmf/2LRQUX8bYwh3PCYWmOGrueargd'.
			'XGO5d6UGm5FSmBqzXEzK2cN9PcXsD9XsKTHawijcAAAAASUVORK5CYII=',
		'sw-guide.png' => 
			'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAFfKj/FAAAAB3RJTUUH1wYQEhckO'.
			'pQzUQAAAAlwSFlzAAALEgAACxIB0t1+/AAAAARnQU1BAACxjwv8YQUAAABFUExURZ'.
			'wMDN7e3tbW1oSEhOfn54yMjDk5OTExMWtra7W1te/v72NjY0pKSs7OzpycnHNzc8b'.
			'Gxr29vff3962trVJSUqWlpUJCQkXEfukAAAABdFJOUwBA5thmAAAAlUlEQVR42k2O'.
			'WxLDIAwD5QfQEEKDob3/UevAtM1+LRoNFsDgCGbEAE7ZwBoe/maCndaRyylQTQK2S'.
			'XPpXjTvq2osRUCyAPEEaKvM6LWFKcFGnCI1Hc+WXVRFk07ROGVBoNpvVAJ3Pzjee5'.
			'7fdh9dfcUItO5UD8T6aVs69jheJlegFyFmPlj/wZZC3ssKSH+wB9/9C8IH45EIdeu'.
			'A/YIAAAAASUVORK5CYII=',
		'wp.png' => 
			'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAFfKj/FAAAAB3RJTUUH1wYQEiwG0'.
			'0adjQAAAAlwSFlzAAALEgAACxIB0t1+/AAAAARnQU1BAACxjwv8YQUAAABOUExURZ'.
			'wMDN7n93ut1kKExjFjnHul1tbn75S93jFrnP///1qUxnOl1sbe71KMxjFrpWOUzjl'.
			'7tYy13q3G5+fv95y93muczu/39zl7vff3//f//9Se9dEAAAABdFJOUwBA5thmAAAA'.
			's0lEQVR42iWPUZLDIAxDRZFNTMCllJD0/hddktWPRp6x5QcQmyIA1qG1GuBUIArwj'.
			'SRITkiylXNxHjtweqfRFHJ86MIBrBuW0nIIo96+H/SSAb5Zm14KnZTm7cQVc1XSMT'.
			'jr7IdAVPm+G5GS6YZHaUv6M132RBF1PopTXiuPYplcmxzWk2C72CfZTNaU09GCM3T'.
			'Ww9porieUwZt9yP6tHm5K5L2Uun6xsuf/WoTXwo7yQPwBXo8H/8TEoKYAAAAASUVO'.
			'RK5CYII=',
	); // $resources = array
				
	if(array_key_exists($_GET['resource'],$resources)) {

		$content = base64_decode($resources[ $_GET['resource'] ]);

		$lastMod = filemtime(__FILE__);
		$client = ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false );
		// Checking if the client is validating his cache and if it is current.
		if (isset($client) && (strtotime($client) == $lastMod)) {
			// Client's cache IS current, so we just respond '304 Not Modified'.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 304);
			exit;
		} else {
			// Image not cached or cache outdated, we respond '200 OK' and output the image.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 200);
			header('Content-Length: '.strlen($content));
			header('Content-Type: image/' . substr(strrchr($_GET['resource'], '.'), 1) );
			echo $content;
			exit;
		}	
	}
}

////////////////////////////////////////////////////////////////////////////////
// Display Icons
////////////////////////////////////////////////////////////////////////////////
function mamo_get_resource_url($resourceID) {
	return trailingslashit(get_bloginfo('siteurl')) . '?resource=' . $resourceID;
}






?>