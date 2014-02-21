<?php
/*
	Template Name: About Template
 */
get_header(); ?>

<section class="inner-page overflow-hidden">
	
	<div class="top-inner-title col span_12">
		<div class="inner-wrapper">
			
			<h1 class="title"><?php the_title(); ?></h1>

		</div><!-- / end inner wrapper -->
		
	</div><!-- / end col span 12, title top -->
	
	<!-- 	<img src="<?php bloginfo(template_url); ?>/app/images/divider.svg" alt="Divider" class="divider"> -->

	<div class="outer-content about row">
		
			<section class="inner-wrapper content">
				
				
				<div class="sub-content">
					
					<div class="container col span_12"> 

						<?php the_post(); ?>

						<p><?php the_content(); ?></p>

					</div><!-- / end container -->
					
					
				</div><!-- / end sub content area -->




			
			</section><!-- ./ end content inner wrapper -->
		
	</div><!-- / end outer content -->

</section><!-- / end page -->


<?php get_footer(); ?>