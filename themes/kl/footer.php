				</div> <!-- content -->
			</div> <!-- page -->
			<img src="<?php bloginfo('template_directory'); ?>/images/bottom_shadow.jpg" width="960" height="45" alt="Bottom Shadow" />
		</div> <!-- wrap -->
		<div id="footer">
			<?php wp_footer(); ?>
		</div>
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("UA-100181-12");
		pageTracker._trackPageview();
		} catch(err) {}</script>
	</body>
</html>
<?php echo "<!-- Peak Memory: " . round( memory_get_peak_usage() / 1024, 2) . " KB -->"; ?>

<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->