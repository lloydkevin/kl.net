<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 <?php the_content(__('Read more'));?>	
<?php endwhile; else: ?>
 <p>Sorry, nothing matches that criteria.</p>
<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>