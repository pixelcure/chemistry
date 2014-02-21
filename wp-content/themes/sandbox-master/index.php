<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage Sandbox
 */
 
	get_header(); 
?>

<?php
 
if(is_home){

?>	
	<section class="row top">

			<div class="intro col span_7">
				<img id="introCross" class="cross" src="<?php bloginfo(template_url); ?>/app/images/square-bg.svg" alt="Pixel Cure" />
				<h1>
					I'm a designer and developer living in Boston
				</h1>
				<p>
					Currently, I'm a UI developer at <a href="http://www.genuineinteractive.com" target="_blank">Genuine Interactive</a>
				</p>
			</div><!-- end intro -->
		

		<div class="recipe col span_5" id="recipeHero">
			<h1>Let me fill you in on some of my secret <span class="teal">ingredients</span> to my recipe..</h1>
			<h1 class="hidden">Scroll to View my Labratory . . .</h1>
		</div><!-- end recipe -->
		
		<div class="clear"></div><!-- end clear -->

	</section><!-- end row -->

	<section class="row bottom">

		<div class="callouts inner-wrapper">
			
			<aside class="callout the-doctor col span_6" id="chemistCallout">
				<h1>The Chemist</h1>
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
					Nam recusandae nemo necessitatibus sint expedita tempore dolores 
					quod cumque atque provident amet sapiente aperiam molestiae earum 
					assumenda. Quas ullam pariatur error
				</p>
				<a href="#" class="learn-more">The Chemist</a>
			</aside><!-- end design column -->		

			<aside class="callout scroll-now col span_6">
				<img class="left" src="<?php bloginfo(template_url); ?>/app/images/scroll.svg" alt="Scroll Down" />
				<h1>Scroll down to view the labratory</h1>
			</aside><!-- end scroll now -->

			<div class="clear"></div><!-- end clear -->

		</div><!-- end callouts inner wrapper -->
		<!-- * Divider */ -->
		<!-- */ Divider */ -->

	</section> <!-- / end bottom section row -->
	<div class="divider-outer overflow-hidden">
		<img class="home-divider" src="<?php bloginfo(template_url); ?>/app/images/divider.svg" alt="Pixel Cure" >
	</div><!-- / end divider outer -->
		

	<section class="col span_12 beaker-row beaker-set-default" id="beakerRowDev">
		<h1 class="hidden-title" id="devHiddenTitle">Development</h1>
		<div id="devCopy" class="beaker-content">
			<h1>Development</h1>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
				Nam recusandae nemo necessitatibus sint expedita tempore dolores 
				quod cumque atque provident amet sapiente aperiam molestiae earum 
				assumenda. Quas ullam pariatur error
			</p>
			<a href="#" class="learn-more" id="exploreDev">Explore</a>		
		</div><!-- / end beaker content -->

		<div class="beaker-holder-container" id="beakerDev">
			
			<div class="beaker-holder">
				<img src="<?php bloginfo(template_url); ?>/app/images/table/beaker-holder-reverse.svg" alt="Beaker Holder" />
			</div><!-- / end beaker holder -->

			<div class="stir">
				<img src="<?php bloginfo(template_url); ?>/app/images/table/stir.svg" alt="stir" />
			</div><!-- / end beaker stir -->
			

			<div class="beaker-container">

				<div class="beaker">
				
					<div class="beaker-1">
						<img src="<?php bloginfo(template_url); ?>/app/images/table/beaker-1.svg" alt="Beaker" />
					</div><!-- / end beaker 1 -->
				
				</div><!-- / end beaker -->
				

				<div class="beaker-contents">
					<img id="devContents" src="<?php bloginfo(template_url); ?>/app/images/table/beaker-1-contents.svg" alt="Beaker Contents" />
				</div><!-- / end beaker contents -->		

			</div><!-- / end beaker container -->

		</div> <! -- / end beaker container -->	


		<div class="table" id="devTable">
			<img src="<?php bloginfo(template_url); ?>/app/images/table/tabletop.svg" alt="Table" />
		</div><!-- / end table -->

	</section><!-- / end section beaker set one -->


	<section class="col span_12 beaker-row beaker-set-two beaker-set-two" id="beakerRowDesign">
		<h1 class="hidden-title" id="designHiddenTitle">Design</h1>
		<div id="designCopy" class="beaker-content">
			<h1>Design</h1>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
				Nam recusandae nemo necessitatibus sint expedita tempore dolores 
				quod cumque atque provident amet sapiente aperiam molestiae earum 
				assumenda. Quas ullam pariatur error
			</p>
			<a href="#" class="learn-more" id="exploreDesign">Explore</a>		
		</div><!-- / end beaker content -->

		<div class="beaker-holder-container" id="beakerDesign">
			
			<div class="beaker-holder">
				<img src="<?php bloginfo(template_url); ?>/app/images/table/beaker-holder.svg" alt="Beaker Holder" />
			</div><!-- / end beaker holder -->

			<div class="stir">
				<img src="<?php bloginfo(template_url); ?>/app/images/table/stir.svg" alt="stir" />
			</div><!-- / end beaker stir -->
			

			<div class="beaker-container">

				<div class="beaker">
				
					<div class="beaker-1">
						<img src="<?php bloginfo(template_url); ?>/app/images/table/beaker-2.svg" alt="Beaker" />
					</div><!-- / end beaker 1 -->
				
				</div><!-- / end beaker -->
				

				<div class="beaker-contents">
					<img id="designContents" src="<?php bloginfo(template_url); ?>/app/images/table/beaker-2-contents.svg" alt="Beaker Contents" />
				</div><!-- / end beaker contents -->		

			</div><!-- / end beaker container -->

		</div> <!-- / end beaker container --> 

		<div class="table" id="designTable">
			<img src="<?php bloginfo(template_url); ?>/app/images/table/tabletop.svg" alt="Table" />
		</div><!-- / end table -->
	</section><!-- / end section beaker set two -->


	<section class="col span_12 beaker-row beaker-set-default last" id="beakerCreative">
		<h1 class="hidden-title" id="creativeHiddenTitle">Creative Mind</h1>
		<div id="creativeCopy" class="beaker-content">
			<h1>Creative Mind</h1>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
				Nam recusandae nemo necessitatibus sint expedita tempore dolores 
				quod cumque atque provident amet sapiente aperiam molestiae earum 
				assumenda. Quas ullam pariatur error
			</p>
			<a href="#" class="learn-more" id="exploreCreative">Explore</a>			
		</div><!-- / end beaker content -->

		<div class="beaker-holder-container" id="beakerCreative">
			
			<div class="beaker-holder">
				<img src="<?php bloginfo(template_url); ?>/app/images/table/beaker-holder-reverse.svg" alt="Beaker Holder" />
			</div><!-- / end beaker holder -->

			<div class="stir">
				<img src="<?php bloginfo(template_url); ?>/app/images/table/stir.svg" alt="stir" />
			</div><!-- / end beaker stir -->
			

			<div class="beaker-container">

				<div class="beaker">
				
					<div class="beaker-1">
						<img src="<?php bloginfo(template_url); ?>/app/images/table/beaker-1.svg" alt="Beaker" />
					</div><!-- / end beaker 1 -->
				
				</div><!-- / end beaker -->
				

				<div class="beaker-contents">
					<img id="creativeContents" src="<?php bloginfo(template_url); ?>/app/images/table/beaker-1-contents.svg" alt="Beaker Contents" />
				</div><!-- / end beaker contents -->		

			</div><!-- / end beaker container -->

		</div> <! -- / end beaker container -->	

		<div class="table" id="creativeTable">
			<img src="<?php bloginfo(template_url); ?>/app/images/table/tabletop.svg" alt="Table" />
		</div><!-- / end table -->
	</section><!-- / end section beaker set three -->
		

<?php }else{

	include(get_template_directory().'/loops/loop.php');

}

 ?>

	<?php get_footer(); 

?>