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


<h3>Recent Articles</h3>
<ul>
<?php
$temp = $wp_query;
$wp_query= null;
$wp_query = new WP_Query();
$wp_query->query('showposts=1'.'&paged='.$paged.'&cat=3');
?>
<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
<?php endwhile; ?>
</ul>
<div class="navigation">
  <div class="alignleft"><?php previous_posts_link('&laquo; Previous') ?></div>
  <div class="alignright"><?php next_posts_link('More &raquo;') ?></div>
</div>
<?php $wp_query = null; $wp_query = $temp;?>








<?php get_sidebar(); ?>
<?php get_footer(); ?>