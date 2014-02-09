<?php
/**
 * The template for displaying all pages.
 *
 * @package WordPress
 * @subpackage Sandbox
 * @since Sandbox 2.0
 */
get_header(); ?>

<?php the_post(); ?>

<h1><?php the_title(); ?></h1>

<p><?php the_content(); ?></p>

<hr />

<?php get_sidebar(); ?>
<?php get_footer(); ?>