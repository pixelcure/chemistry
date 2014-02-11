<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Sandbox
 * @since Sandbox 2.0
 */
?>


<div class="footer col span_12">
	<div class="inner-wrapper">

		<div class="left">
			<h1>&copy; 2014 Pixel Cure All Rights Reserved</h1>
		</div><!-- / end left -->
		
		<div class="right">		
			<?php wp_nav_menu(); ?>
		</div><!-- / end right -->
		
	</div><!-- / end inner wrapper -->
</div><!-- / end footer -->


<script data-main="<?php bloginfo('template_url'); ?>/app/scripts/main.js" src="<?php bloginfo('template_url'); ?>/app/bower_components/requirejs/require.js"></script>

<?php wp_footer(); ?>

</div><!-- / end wrapper -->
</body>
</html>