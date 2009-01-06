<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="description" content="<?php bloginfo('description'); ?>" />
	<title><?php bloginfo('name'); ?> &raquo; <?php echo $mamo_opt['mamo_pagetitle'];  if ($mamo_opt['mamo_applyaltlang']) echo ' / ' . $mamo_opt['mamo_langalttitle']; ?></title>
	
	<style type="text/css">
		<!--
		* { margin: 0; 	padding: 0; }
		body { font-family: Georgia, Arial, Helvetica, Sans Serif; font-size: 65.5%; }
		a { color: #08658F; }
		a:hover { color: #0092BF; }
		#header { color: #333; padding: 1.5em; text-align: center; font-size: 1.2em; border-bottom: 1px solid #08658F; }
		#content { font-size: 150%; width:80%; margin:0 auto; padding: 5% 0; text-align: center; }
		#content p { font-size: 1em; padding: .8em 0; }
		h1, h2 { color: #08658F; }
		h1 { font-size: 300%; padding: .5em 0; }
		#footer { position: absolute; font-family: Arial, Helvetica, Sans Serif; bottom: 2em; width: 100%; border-top: 1px solid #08658F; }
		#footer #pluginauthor { padding-left: .3em; }
		#footer #admin { float: right; padding-right: .3em; }
		-->
	</style>
	
</head>

<body>

	<div id="header">
		<h2><a title="<?php bloginfo('name'); ?>" href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a></h2>
	</div>
	
	<div id="content">
		<?php 
			$mamo_msg = stripslashes($mamo_opt['mamo_pagemsg']);
			$mamo_msg = str_replace('[blogurl]', get_settings('home'), $mamo_msg);
			$mamo_msg = str_replace('[blogtitle]', get_bloginfo('name'), $mamo_msg);
			$mamo_msg = str_replace('[backtime]', $mamo_opt['mamo_backtime'], $mamo_msg);
			echo $mamo_msg;
		?>		
		
	</div>

	<div id="footer">
		<p id="admin"><a href="<?php echo get_settings('siteurl'); ?>/wp-admin/">Administration</a></p>
		<p id="pluginauthor">Maintenance Mode plugin provided by <a href="http://sw-guide.de/">Software Guide</a>.</p>
	</div>

</body>
</html>