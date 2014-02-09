<?php
/**
 * The Header for our theme.
 *
 * @package WordPress
 * @subpackage Sandbox
 * @since Sandbox 2.0
 */
?><!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<title><?php wp_title(''); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>">
<!-- <link rel="stylesheet" href="<?php bloginfo(template_url); ?>/styles/layout.css"> -->

 
	<!-- build:css(.tmp) styles/main.css -->
	<link rel="stylesheet" href="<?php bloginfo(template_url); ?>/app/styles/main.css">
	<!-- endbuild -->
	<!-- build:js scripts/vendor/modernizr.js -->
	<script src="<?php bloginfo(template_url); ?>/app/bower_components/modernizr/modernizr.js"></script>
	<!-- endbuild -->


<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php bloginfo('url'); ?>/favicon.ico">
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div class="wrapper row">
	<section class="outer-header overflow-hidden">	
		
		<section class="header col span_12">

			<div class="inner-wrapper">
				
				<aside class="logo left col span_6">
					<a href="/">
						<img src="<?php bloginfo(template_url); ?>/app/images/logo.svg" alt="Pixel Cure" />
					</a>	
				</aside><!-- / end logo -->

				<aside class="nav-outer col span_6">
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu' => 'menu' ) ); ?>
				</aside><!-- / end col span 6 nav outer -->
			
			</div><!-- / end inner wrapper -->
		</section>
		
	
	</section><!-- end header -->