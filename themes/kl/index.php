<?php get_header(); ?>

<div id="post">
 <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 <p>Written on <?php the_time('F j, Y'); ?> at <?php the_time() ?>, by <?php the_author() ?></p>

 <?php the_content(__('Read more'));?>

 <div id="postmeta">
  <p><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?><!-- | <?php trackback_rdf(); ?>--></p>
  <p>Category <?php the_category(', ') ?> | Tags: <?php the_tags(' ', ',', ' '); ?></p>
 </div><!-- end #postmeta -->

 <?php endwhile; else: ?>
  <p><strong>There has been a glitch in the Matrix.</strong></p>
  <p>Sorry, there's nothing to see right now.</p>
 <?php endif; ?>

 <div id="postnavigation">
  <p><?php next_posts_link('&laquo; Older Entries') ?><?php previous_posts_link(' | Newer Entries &raquo;') ?></p>
 </div> <!-- end #postnavigation -->
</div> <!-- end #post -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>