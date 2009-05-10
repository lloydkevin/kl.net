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


<!-- Portfolio Items -->
<?php
$temp = $wp_query;
$wp_query= null;
$wp_query = new WP_Query();
$wp_query->query('showposts=5'.'&paged='.$paged.'&cat=3');
?>
<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
<?php
	$img = get_post_meta($post->ID, 'img', true);
	$thumb = get_post_meta($post->ID, 'thumb', true);
	$link = get_post_meta($post->ID, 'link', true);
	$title = the_title('','',false);
	
	if (empty($link)) {
		$heading = '<h4>'. $title .'</h4>';
	} else	{
		$heading = '<h4><a href="'. $link .'" target="_blank" title="'. $title .'">'. $title .'</a></h4>';
	}
	
	$image = '<a href="'. $img .'"><img src="'. $thumb .'" alt="'. $title .'" title="'. $title .'" /></a>';
?>
	<div class="portfolio-item">
		<?= $heading ?>
		<?= $image ?>
		<div class="portfolio-content">
			<?php the_content(); ?>
		</div>
	</div>
	
<?php endwhile; ?>

<!-- Portfolio Items -->

<!-- Navigation -->
<div class="navigation">
  <div class="alignleft"><?php previous_posts_link('&laquo; Previous') ?></div>
  <div class="alignright"><?php next_posts_link('More &raquo;') ?></div>
</div>
<!-- End Navigation -->
<?php $wp_query = null; $wp_query = $temp;?>

<?php get_footer(); ?>