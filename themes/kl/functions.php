<?php
 if ( function_exists('register_sidebar') )
 	register_sidebar();

function featured() {
	?>

<div id="feature">
	<div id="tag">
		Web Developer, Web Designer, &amp; All Around Programmer. Started in '97 with Pascal; Migrated to C++, C#, PHP, JavaScript, Ruby on Rails, and more.
	</div>
	<ul>
		<li>&gt; <a href="/portfolio">Check out my Portfolio</a></li>
		<li>&gt; <a href="/wp-content/uploads/kevinlloyd_resume.pdf">Download My Resume</a></li>
		<li>&gt; <a href="/hire-me"><strong>Hire Me!</strong></a></li>
	</ul>
</div>
	
<?php	
}
?>