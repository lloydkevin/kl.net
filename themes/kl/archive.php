<?php get_header(); ?>

<?php is_tag(); ?>
 <?php if (have_posts()) : ?>

  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
  <?php /* If this is a category archive */ if (is_category()) { ?>
   <h2>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
   <h2>Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
   <h2>Archive for <?php the_time('F jS, Y'); ?></h2>
  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
   <h2>Archive for <?php the_time('F, Y'); ?></h2>
  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
   <h2>Archive for <?php the_time('Y'); ?></h2>
  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
   <h2>Author Archive</h2>
  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
   <h2>Blog Archives</h2>
  <?php } ?>

 <div id="postnavigation">
  <p><?php next_posts_link('&laquo; Older Entries') ?> | <?php previous_posts_link('Newer Entries &raquo;') ?></p>
 </div> <!-- end #postnavigation -->

 <?php while (have_posts()) : the_post(); ?>
 <div class="post">
  <h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
  <p><?php the_time('l, F jS, Y') ?></p>
  <?php the_content() ?>
  
  <div id="postmeta">
   <p><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?><!-- | <?php trackback_rdf(); ?>--></p>
   <p>Category <?php the_category(', ') ?> | Tags: <?php the_tags('Tags: ', ', ', ', '); ?></p>
   <?php edit_post_link('Edit', '', ' | '); ?>
  </div><!-- end #postmeta -->

 </div><!-- end #post -->

 <?php endwhile; ?>

 <div id="postnavigation">
  <p><?php next_posts_link('&laquo; Older Entries') ?> | <?php previous_posts_link('Newer Entries &raquo;') ?></p>
 </div> <!-- end #postnavigation -->
 
<?php else : ?>

 <h2>Not Found</h2>
 <p>Try using the search form below</p>
 <?php include (TEMPLATEPATH . '/searchform.php'); ?>

<?php endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>