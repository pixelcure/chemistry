<?php
/**
 * The template for displaying all pages.
 *
 * @package WordPress
 * @subpackage Sandbox
 * @since Sandbox 2.0
 */
get_header(); ?>

<section class="inner-page overflow-hidden">
	
	<div class="top-inner-title col span_12">
		<div class="inner-wrapper">
			
			<h1 class="title"><?php the_title(); ?></h1>

		</div><!-- / end inner wrapper -->
		
	</div><!-- / end col span 12, title top -->
	
	<!-- 	<img src="<?php bloginfo(template_url); ?>/app/images/divider.svg" alt="Divider" class="divider"> -->

	<div class="outer-content">
		
			<section class="inner-wrapper content">
				
				<div class="sub-header">
					
					<p>
						Below are some of the recent projects I've been working on. Click on the image to learn more.
					</p>	

				</div><!-- / end sub header -->
				
				<div class="sub-content">


						<ul class="projects-container">
							
							<li class="item">
								<div class="mask"></div><!-- / end mask -->
								<span>MPC 2000 XL</span>
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />

							</li><!-- / end item -->							

							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->							

							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->	
							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->								
							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->		
							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->			
							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->							

							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->	
							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->								
							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->		
							<li class="item">
								<img src="<?php bloginfo(template_url); ?>/app/images/design/mpc.png" alt="MPC 2000 XL" />
							</li><!-- / end item -->					

						</ul><!-- / end projects container -->
					
				</div><!-- / end sub content area -->




				<?php the_post(); ?>
				
				<p><?php the_content(); ?></p>
			
			</section><!-- ./ end content inner wrapper -->
		
	</div><!-- / end outer content -->

</section><!-- / end page -->


<?php get_footer(); ?>