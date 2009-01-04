<?php get_header(); ?>

<div id="post">
 <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 <p>Written on <?php the_time('F j, Y'); ?> at <?php the_time() ?>, by <?php the_author() ?></p>

 <?php the_content(__('Read more'));?>

 <div id="postmeta">
  <p><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?><!-- | <?php trackback_rdf(); ?>--></p>
  <p>Category <?php the_category(', ') ?> | Tags: <?php the_tags(' ', ',', ' '); ?></p>
  <!-- Social Networking Links - If you're interested. Please note, that the following networking links contain invalid XHTML. -->
  <p>Social Networks : <a href="http://technorati.com/faves?add=<?php the_permalink();?>">Technorati</a>, <a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>">Stumble it!</a>, <a href="http://digg.com/submit?phase=2&url= <?php the_permalink();?>&title=<?php the_title();?>">Digg</a>, <a href="http://del.icio.us/post?v=4&noui&jump=close&url=<?php the_permalink();?>&title=<?php the_title();?>">de.licio.us</a>, <a href="http://myweb.yahoo.com/myresults/bookmarklet? t=<?php the_title();?>&u=<?php the_permalink();?>&ei=UTF">Yahoo</a>, <a href="http://reddit.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" >reddit</a>, <a href="http://blogmarks.net/my/new.php? title=<?php the_title();?>&url=<?php the_permalink();?>">Blogmarks</a>, <a href="http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk=<?php the_permalink();?>&title=<?php the_title();?>">Google</a>, <a href="http://ma.gnolia.com/bookmarklet/add? url=<?php the_permalink();?>&title=<?php the_title();?>">Magnolia</a>.</p>
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