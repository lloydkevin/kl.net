<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
 <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
 <title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
 <meta name="generator" content="WordPress" /> <!-- leave this for stats -->
 <meta name="author" content="Brian Purkiss" />
 <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
 <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
 <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
 <?php wp_head(); ?>	
</head>
<body>
	<div id="header">
		<div class="container">
			<h1 id="logo">
				<a href=""<?php echo get_option('home'); ?>/""><span></span><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?></a>
			</h1>
			<?php wp_page_menu('show_home=1&sort_column=menu_order'); ?>
		</div> <!-- container -->
	</div> <!-- header -->
	
	<div id="wrap" class="container">
		<div id="page">
			<div id="content">
				<div class="spacer"></div>	
<!-- 
 <h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
 <p><?php bloginfo('description'); ?></p>
-->