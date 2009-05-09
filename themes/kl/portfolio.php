<?php
/*
Template Name: Portfolio
*/
?>
<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php the_content(__('Read more'));?>	
<?php endwhile; else: ?>
	<p>Sorry, nothing matches that criteria.</p>
<?php endif; ?>










<?php get_sidebar(); ?>
<?php get_footer(); ?>